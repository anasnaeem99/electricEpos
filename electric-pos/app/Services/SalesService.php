<?php
namespace App\Services;

use App\Models\StockModel;

class SalesService
{
    protected $db;
    protected $stockModel;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->stockModel = new StockModel();
    }

    public function generateInvoiceNo($businessId, $storeId)
    {
        $store = $this->db->table('tbl_store')->where('id', $storeId)->get()->getRow();
        $storeCode = $store ? $store->code : 'STR';
        $today = date('Ymd');

        $count = $this->db->table('tbl_sale')
            ->where('store_id', $storeId)
            ->where('DATE(created_at)', date('Y-m-d'))
            ->countAllResults();

        return 'INV-' . $storeCode . '-' . $today . '-' . str_pad($count + 1, 4, '0', STR_PAD_LEFT);
    }

    /**
     * ATOMIC SALE COMMIT:
     * - Validates idempotency UUID
     * - Creates sale record
     * - Inserts sale items with full pricing snapshot
     * - Deducts stock balance and logs SALE_OUT movement
     * - Records payments (cash, card, etc.)
     * - Queues loyalty points as pending (24-hour credit delay per rules)
     */
    public function commitSale(array $data)
    {
        $uuid = $data['local_txn_uuid'] ?? ('UUID-' . uniqid() . '-' . rand(100, 999));

        // Idempotency check: If already processed, return existing sale
        $existing = $this->db->table('tbl_sale')->where('local_txn_uuid', $uuid)->get()->getRow();
        if ($existing) {
            return [
                'success'    => true,
                'sale_id'    => $existing->id,
                'invoice_no' => $existing->invoice_no,
                'is_repeat'  => true
            ];
        }

        $this->db->transStart();

        $invoiceNo = $this->generateInvoiceNo($data['business_id'], $data['store_id']);

        $saleRecord = [
            'business_id'      => $data['business_id'],
            'store_id'         => $data['store_id'],
            'register_id'      => $data['register_id'] ?? 1,
            'device_id'        => $data['device_id'] ?? null,
            'shift_id'         => $data['shift_id'] ?? null,
            'cashier_id'       => $data['cashier_id'],
            'customer_id'      => !empty($data['customer_id']) ? $data['customer_id'] : null,
            'local_txn_uuid'   => $uuid,
            'invoice_no'       => $invoiceNo,
            'sale_date'        => date('Y-m-d H:i:s'),
            'subtotal'         => (float)$data['subtotal'],
            'item_discount'    => (float)($data['item_discount'] ?? 0),
            'bill_discount'    => (float)($data['bill_discount'] ?? 0),
            'discount_amount'  => (float)($data['discount_amount'] ?? 0),
            'tax_amount'       => (float)($data['tax_amount'] ?? 0),
            'loyalty_redeemed' => (float)($data['loyalty_redeemed'] ?? 0),
            'total_amount'     => (float)$data['total_amount'],
            'paid_amount'      => (float)$data['paid_amount'],
            'change_amount'    => (float)($data['change_amount'] ?? 0),
            'sale_status'      => 'completed',
            'sync_status'      => 'synced',
            'synced_at'        => date('Y-m-d H:i:s'),
            'note'             => $data['note'] ?? null,
        ];

        $this->db->table('tbl_sale')->insert($saleRecord);
        $saleId = $this->db->insertID();

        // 2. Loop Cart Items
        foreach ($data['items'] as $item) {
            $prodId = (int)$item['product_id'];
            $qty = (float)$item['qty'];

            // Find default shelf location for store
            $location = $this->db->table('tbl_stock_location')
                ->where('store_id', $data['store_id'])
                ->where('location_type', 'shelf')
                ->get()->getRow() ?? $this->db->table('tbl_stock_location')->where('store_id', $data['store_id'])->get()->getRow();

            $locationId = $item['location_id'] ?? ($location->id ?? 1);
            $batchId = $item['batch_id'] ?? 0;

            // Product snapshot
            $prod = $this->db->table('tbl_product')->where('id', $prodId)->get()->getRow();

            $saleItem = [
                'sale_id'        => $saleId,
                'product_id'     => $prodId,
                'batch_id'       => $batchId ?: null,
                'location_id'    => $locationId,
                'unit_id'        => $item['unit_id'] ?? ($prod->base_unit_id ?? 1),
                'product_name'   => $item['product_name'] ?? ($prod->name ?? 'Item'),
                'product_sku'    => $item['product_sku'] ?? ($prod->sku ?? ''),
                'barcode'        => $item['barcode'] ?? null,
                'unit_name'      => $item['unit_name'] ?? 'pcs',
                'qty'            => $qty,
                'unit_price'     => (float)$item['unit_price'],
                'item_discount'  => (float)($item['discount'] ?? 0),
                'tax_rate'       => (float)($item['tax_rate'] ?? 0),
                'tax_amount'     => (float)($item['tax_amount'] ?? 0),
                'line_total'     => (float)$item['line_total'],
                'promotion_disc' => (float)($item['promotion_disc'] ?? 0),
            ];

            $this->db->table('tbl_sale_item')->insert($saleItem);

            // Deduct Stock Balance
            $this->stockModel->upsertBalance($data['business_id'], $data['store_id'], $location->warehouse_id ?? 1, $locationId, $prodId, $batchId, -$qty);

            // Log SALE_OUT Movement
            $this->db->table('tbl_stock_movement')->insert([
                'business_id'    => $data['business_id'],
                'store_id'       => $data['store_id'],
                'warehouse_id'   => $location->warehouse_id ?? null,
                'location_id'    => $locationId,
                'product_id'     => $prodId,
                'batch_id'       => $batchId ?: null,
                'movement_type'  => 'SALE_OUT',
                'qty'            => -$qty,
                'unit_id'        => $saleItem['unit_id'],
                'unit_cost'      => $prod->purchase_price ?? null,
                'reference_type' => 'sale',
                'reference_id'   => $saleId,
                'note'           => 'Sale ' . $invoiceNo,
                'created_by'     => $data['cashier_id'],
            ]);
        }

        // 3. Payments
        if (!empty($data['payments'])) {
            foreach ($data['payments'] as $pay) {
                $this->db->table('tbl_sale_payment')->insert([
                    'sale_id'           => $saleId,
                    'payment_method_id' => $pay['method_id'] ?? 1,
                    'amount'            => (float)$pay['amount'],
                    'reference'         => $pay['reference'] ?? null,
                ]);
            }
        } else {
            // Default cash payment
            $this->db->table('tbl_sale_payment')->insert([
                'sale_id'           => $saleId,
                'payment_method_id' => 1,
                'amount'            => (float)$data['paid_amount'],
            ]);
        }

        // 4. Loyalty points queue (if customer attached & eligible)
        if (!empty($data['customer_id']) && $data['total_amount'] >= 100) {
            $eligibleAmount = max(0, $data['total_amount'] - ($data['loyalty_redeemed'] ?? 0) - ($data['tax_amount'] ?? 0));
            $earnedPts = floor($eligibleAmount * 0.01); // 1% points

            if ($earnedPts > 0) {
                // Ensure loyalty account exists
                $account = $this->db->table('tbl_loyalty_account')
                    ->where('customer_id', $data['customer_id'])
                    ->where('store_id', $data['store_id'])
                    ->get()->getRow();

                if (!$account) {
                    $this->db->table('tbl_loyalty_account')->insert([
                        'customer_id'      => $data['customer_id'],
                        'store_id'         => $data['store_id'],
                        'program_id'       => 1,
                        'available_points' => 0.00,
                        'pending_points'   => 0.00,
                    ]);
                    $accountId = $this->db->insertID();
                } else {
                    $accountId = $account->id;
                }

                $availableAt = date('Y-m-d H:i:s', strtotime('+24 hours'));
                $expiresAt   = date('Y-m-d H:i:s', strtotime('+2 months', strtotime($availableAt)));

                // Create pending points lot
                $this->db->table('tbl_loyalty_points_lot')->insert([
                    'account_id'    => $accountId,
                    'sale_id'       => $saleId,
                    'earned_points' => $earnedPts,
                    'remaining_pts' => $earnedPts,
                    'available_at'  => $availableAt,
                    'expires_at'    => $expiresAt,
                    'status'        => 'pending',
                ]);

                // Update account pending points
                $this->db->table('tbl_loyalty_account')
                    ->where('id', $accountId)
                    ->set('pending_points', 'pending_points + ' . $earnedPts, false)
                    ->update();

                // Log transaction
                $this->db->table('tbl_loyalty_transaction')->insert([
                    'account_id' => $accountId,
                    'txn_type'   => 'earn',
                    'points'     => $earnedPts,
                    'ref_type'   => 'sale',
                    'ref_id'     => $saleId,
                    'note'       => 'Pending credit for sale ' . $invoiceNo . ' (Available in 24h)',
                ]);
            }
        }

        $this->db->transComplete();

        return [
            'success'    => $this->db->transStatus(),
            'sale_id'    => $saleId,
            'invoice_no' => $invoiceNo,
            'change'     => $data['change_amount'] ?? 0,
        ];
    }

    public function processSaleReturn($saleId, array $returnedLines, $note, $userId)
    {
        $sale = $this->db->table('tbl_sale')->where('id', $saleId)->get()->getRow();
        if (!$sale) return false;

        $this->db->transStart();

        $returnNo = 'RET-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));
        $totalRefund = 0;

        $this->db->table('tbl_sale_return')->insert([
            'business_id'      => $sale->business_id,
            'store_id'         => $sale->store_id,
            'original_sale_id' => $sale->id,
            'cashier_id'       => $userId,
            'return_no'        => $returnNo,
            'total_refund'     => 0,
            'note'             => $note,
        ]);
        $returnId = $this->db->insertID();

        foreach ($returnedLines as $line) {
            $saleItemId = (int)$line['sale_item_id'];
            $qty = (float)$line['qty'];
            if ($qty <= 0) continue;

            $origItem = $this->db->table('tbl_sale_item')->where('id', $saleItemId)->get()->getRow();
            if (!$origItem) continue;

            $unitRefund = $origItem->line_total / $origItem->qty;
            $lineRefund = $qty * $unitRefund;
            $totalRefund += $lineRefund;

            $this->db->table('tbl_sale_return_item')->insert([
                'return_id'           => $returnId,
                'sale_item_id'        => $saleItemId,
                'product_id'          => $origItem->product_id,
                'batch_id'            => $origItem->batch_id,
                'restock_location_id' => $origItem->location_id,
                'unit_id'             => $origItem->unit_id,
                'qty'                 => $qty,
                'unit_price'          => $origItem->unit_price,
                'refund_amount'       => $lineRefund,
            ]);

            // Restock shelf stock balance
            $this->stockModel->upsertBalance($sale->business_id, $sale->store_id, 1, $origItem->location_id, $origItem->product_id, $origItem->batch_id ?: 0, $qty);

            // Record SALE_RETURN_IN movement
            $this->db->table('tbl_stock_movement')->insert([
                'business_id'    => $sale->business_id,
                'store_id'       => $sale->store_id,
                'location_id'    => $origItem->location_id,
                'product_id'     => $origItem->product_id,
                'batch_id'       => $origItem->batch_id,
                'movement_type'  => 'SALE_RETURN_IN',
                'qty'            => $qty,
                'unit_id'        => $origItem->unit_id,
                'reference_type' => 'sale_return',
                'reference_id'   => $returnId,
                'note'           => 'Sale Return: ' . $returnNo,
                'created_by'     => $userId,
            ]);
        }

        $this->db->table('tbl_sale_return')->where('id', $returnId)->update(['total_refund' => $totalRefund]);
        $this->db->table('tbl_sale')->where('id', $saleId)->update(['sale_status' => 'returned']);

        $this->db->transComplete();
        return $this->db->transStatus() ? ['return_id' => $returnId, 'refund_amount' => $totalRefund] : false;
    }
}
