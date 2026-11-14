<?php
namespace App\Models;

use CodeIgniter\Model;

class PurchaseModel extends Model
{
    protected $table = 'tbl_purchase_order';
    protected $primaryKey = 'id';

    public function generatePoNo($businessId)
    {
        $today = date('Ymd');
        $count = $this->db->table('tbl_purchase_order')
            ->where('business_id', $businessId)
            ->where('DATE(created_at)', date('Y-m-d'))
            ->countAllResults();

        return 'PO-' . $today . '-' . str_pad($count + 1, 4, '0', STR_PAD_LEFT);
    }

    public function generateGrnNo($businessId)
    {
        $today = date('Ymd');
        $count = $this->db->table('tbl_goods_receipt')
            ->where('business_id', $businessId)
            ->where('DATE(created_at)', date('Y-m-d'))
            ->countAllResults();

        return 'GRN-' . $today . '-' . str_pad($count + 1, 4, '0', STR_PAD_LEFT);
    }

    public function createPO($header, $items)
    {
        $this->db->transStart();

        $header['po_no'] = $this->generatePoNo($header['business_id']);
        $this->db->table('tbl_purchase_order')->insert($header);
        $poId = $this->db->insertID();

        foreach ($items as $item) {
            $item['po_id'] = $poId;
            $this->db->table('tbl_purchase_order_item')->insert($item);
        }

        $this->db->transComplete();
        return $this->db->transStatus() ? $poId : false;
    }

    public function getPO($id)
    {
        $po = $this->db->table('tbl_purchase_order po')
            ->select('po.*, s.name as supplier_name, s.phone as supplier_phone, st.name as store_name, u.full_name as created_by_name')
            ->join('tbl_supplier s', 's.id = po.supplier_id')
            ->join('tbl_store st', 'st.id = po.store_id')
            ->join('tbl_user u', 'u.id = po.created_by', 'left')
            ->where('po.id', $id)
            ->get()->getRow();

        if ($po) {
            $po->items = $this->db->table('tbl_purchase_order_item poi')
                ->select('poi.*, p.name as product_name, p.sku as product_sku, u.name as unit_name, u.abbreviation as unit_abbr')
                ->join('tbl_product p', 'p.id = poi.product_id')
                ->join('tbl_product_unit u', 'u.id = poi.unit_id', 'left')
                ->where('poi.po_id', $id)
                ->get()->getResult();
        }

        return $po;
    }

    public function getAllPOs($businessId, $storeId = null)
    {
        $builder = $this->db->table('tbl_purchase_order po')
            ->select('po.*, s.name as supplier_name, st.name as store_name, u.full_name as creator_name')
            ->join('tbl_supplier s', 's.id = po.supplier_id')
            ->join('tbl_store st', 'st.id = po.store_id')
            ->join('tbl_user u', 'u.id = po.created_by', 'left')
            ->where('po.business_id', $businessId);

        if ($storeId) {
            $builder->where('po.store_id', $storeId);
        }

        return $builder->orderBy('po.id', 'DESC')->get()->getResult();
    }

    public function approvePO($id, $userId)
    {
        return $this->db->table('tbl_purchase_order')
            ->where('id', $id)
            ->update([
                'status'      => 'approved',
                'approved_by' => $userId,
                'updated_at'  => date('Y-m-d H:i:s')
            ]);
    }

    // GRN (Goods Received Note) Methods
    public function createGRN($header, $items)
    {
        $this->db->transStart();

        $header['grn_no'] = $this->generateGrnNo($header['business_id']);
        $this->db->table('tbl_goods_receipt')->insert($header);
        $grnId = $this->db->insertID();

        foreach ($items as $item) {
            $item['grn_id'] = $grnId;
            $this->db->table('tbl_goods_receipt_item')->insert($item);
        }

        $this->db->transComplete();
        return $this->db->transStatus() ? $grnId : false;
    }

    public function getGRN($id)
    {
        $grn = $this->db->table('tbl_goods_receipt grn')
            ->select('grn.*, s.name as supplier_name, st.name as store_name, w.name as warehouse_name, u.full_name as created_by_name')
            ->join('tbl_supplier s', 's.id = grn.supplier_id')
            ->join('tbl_store st', 'st.id = grn.store_id')
            ->join('tbl_warehouse w', 'w.id = grn.warehouse_id')
            ->join('tbl_user u', 'u.id = grn.created_by', 'left')
            ->where('grn.id', $id)
            ->get()->getRow();

        if ($grn) {
            $grn->items = $this->db->table('tbl_goods_receipt_item gri')
                ->select('gri.*, p.name as product_name, p.sku as product_sku, loc.name as location_name, u.name as unit_name')
                ->join('tbl_product p', 'p.id = gri.product_id')
                ->join('tbl_stock_location loc', 'loc.id = gri.location_id')
                ->join('tbl_product_unit u', 'u.id = gri.unit_id', 'left')
                ->where('gri.grn_id', $id)
                ->get()->getResult();
        }

        return $grn;
    }

    public function getAllGRNs($businessId, $storeId = null)
    {
        $builder = $this->db->table('tbl_goods_receipt grn')
            ->select('grn.*, s.name as supplier_name, st.name as store_name, w.name as warehouse_name')
            ->join('tbl_supplier s', 's.id = grn.supplier_id')
            ->join('tbl_store st', 'st.id = grn.store_id')
            ->join('tbl_warehouse w', 'w.id = grn.warehouse_id')
            ->where('grn.business_id', $businessId);

        if ($storeId) {
            $builder->where('grn.store_id', $storeId);
        }

        return $builder->orderBy('grn.id', 'DESC')->get()->getResult();
    }

    /**
     * POST GRN - THIS IS WHERE STOCK IS ATOMICALLY INCREASED
     */
    public function postGRN($grnId, $userId)
    {
        $grn = $this->getGRN($grnId);
        if (!$grn || $grn->status !== 'draft') {
            return false;
        }

        $this->db->transStart();

        // 1. Mark GRN as posted
        $this->db->table('tbl_goods_receipt')
            ->where('id', $grnId)
            ->update([
                'status'     => 'posted',
                'posted_at'  => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

        // 2. Loop each received item: update batch, stock balance & ledger
        foreach ($grn->items as $item) {
            $batchId = $item->batch_id ?: 0;

            // If product has batch tracking and new batch specified
            if (!empty($item->expiry_date) && empty($batchId)) {
                $batchNo = 'B-' . date('ymd') . '-' . $item->product_id;
                $this->db->table('tbl_product_batch')->insert([
                    'product_id'  => $item->product_id,
                    'batch_no'    => $batchNo,
                    'expiry_date' => $item->expiry_date,
                    'cost'        => $item->unit_cost,
                    'is_active'   => 1
                ]);
                $batchId = $this->db->insertID();
            }

            // A. Update / Insert into tbl_stock_balance
            $existingBalance = $this->db->table('tbl_stock_balance')
                ->where('store_id', $grn->store_id)
                ->where('location_id', $item->location_id)
                ->where('product_id', $item->product_id)
                ->where('batch_id', $batchId)
                ->get()->getRow();

            if ($existingBalance) {
                $this->db->table('tbl_stock_balance')
                    ->where('id', $existingBalance->id)
                    ->update([
                        'quantity'   => $existingBalance->quantity + $item->received_qty,
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
            } else {
                $this->db->table('tbl_stock_balance')->insert([
                    'business_id'  => $grn->business_id,
                    'store_id'     => $grn->store_id,
                    'warehouse_id' => $grn->warehouse_id,
                    'location_id'  => $item->location_id,
                    'product_id'   => $item->product_id,
                    'batch_id'     => $batchId,
                    'quantity'     => $item->received_qty,
                    'reserved_qty' => 0.000,
                ]);
            }

            // B. Immutable Stock Movement Ledger
            $this->db->table('tbl_stock_movement')->insert([
                'business_id'    => $grn->business_id,
                'store_id'       => $grn->store_id,
                'warehouse_id'   => $grn->warehouse_id,
                'location_id'    => $item->location_id,
                'product_id'     => $item->product_id,
                'batch_id'       => $batchId,
                'movement_type'  => 'PURCHASE_IN',
                'qty'            => $item->received_qty,
                'unit_id'        => $item->unit_id,
                'unit_cost'      => $item->unit_cost,
                'reference_type' => 'goods_receipt',
                'reference_id'   => $grn->id,
                'note'           => 'GRN Posted: ' . $grn->grn_no,
                'created_by'     => $userId,
            ]);

            // C. If item was tied to PO item, update received quantity
            if (!empty($item->poi_id)) {
                $poi = $this->db->table('tbl_purchase_order_item')->where('id', $item->poi_id)->get()->getRow();
                if ($poi) {
                    $this->db->table('tbl_purchase_order_item')
                        ->where('id', $item->poi_id)
                        ->update(['received_qty' => $poi->received_qty + $item->received_qty]);
                }
            }
        }

        // 3. Update PO overall status if applicable
        if (!empty($grn->po_id)) {
            $this->db->table('tbl_purchase_order')
                ->where('id', $grn->po_id)
                ->update(['status' => 'received']);
        }

        // 4. Update Supplier Payable Ledger (Credit increases what we owe supplier)
        $supplierModel = new SupplierModel();
        $supplierModel->addLedgerEntry($grn->supplier_id, 'goods_receipt', $grn->id, 0, $grn->total_amount, 'GRN: ' . $grn->grn_no);

        $this->db->transComplete();
        return $this->db->transStatus();
    }
}
