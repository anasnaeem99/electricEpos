<?php
namespace App\Models;

use CodeIgniter\Model;

class TransferModel extends Model
{
    protected $table = 'tbl_stock_transfer';
    protected $primaryKey = 'id';

    public function generateTransferNo($businessId)
    {
        $today = date('Ymd');
        $count = $this->db->table('tbl_stock_transfer')
            ->where('business_id', $businessId)
            ->where('DATE(created_at)', date('Y-m-d'))
            ->countAllResults();

        return 'TRF-' . $today . '-' . str_pad($count + 1, 4, '0', STR_PAD_LEFT);
    }

    public function createTransfer($header, $items)
    {
        $this->db->transStart();

        $header['transfer_no'] = $this->generateTransferNo($header['business_id']);
        $this->db->table('tbl_stock_transfer')->insert($header);
        $trfId = $this->db->insertID();

        foreach ($items as $item) {
            $item['transfer_id'] = $trfId;
            $this->db->table('tbl_stock_transfer_item')->insert($item);
        }

        $this->db->transComplete();
        return $this->db->transStatus() ? $trfId : false;
    }

    public function getTransfer($id)
    {
        $trf = $this->db->table('tbl_stock_transfer t')
            ->select('t.*, fs.name as from_store_name, fwh.name as from_wh_name, ts.name as to_store_name, twh.name as to_wh_name, u.full_name as creator_name')
            ->join('tbl_store fs', 'fs.id = t.from_store_id')
            ->join('tbl_warehouse fwh', 'fwh.id = t.from_wh_id')
            ->join('tbl_store ts', 'ts.id = t.to_store_id')
            ->join('tbl_warehouse twh', 'twh.id = t.to_wh_id')
            ->join('tbl_user u', 'u.id = t.created_by', 'left')
            ->where('t.id', $id)
            ->get()->getRow();

        if ($trf) {
            $trf->items = $this->db->table('tbl_stock_transfer_item ti')
                ->select('ti.*, p.name as product_name, p.sku as product_sku, pb.batch_no, u.name as unit_name')
                ->join('tbl_product p', 'p.id = ti.product_id')
                ->join('tbl_product_batch pb', 'pb.id = ti.batch_id', 'left')
                ->join('tbl_product_unit u', 'u.id = ti.unit_id', 'left')
                ->where('ti.transfer_id', $id)
                ->get()->getResult();
        }

        return $trf;
    }

    public function getAllTransfers($businessId, $storeId = null)
    {
        $builder = $this->db->table('tbl_stock_transfer t')
            ->select('t.*, fs.name as from_store_name, fwh.name as from_wh_name, ts.name as to_store_name, twh.name as to_wh_name')
            ->join('tbl_store fs', 'fs.id = t.from_store_id')
            ->join('tbl_warehouse fwh', 'fwh.id = t.from_wh_id')
            ->join('tbl_store ts', 'ts.id = t.to_store_id')
            ->join('tbl_warehouse twh', 'twh.id = t.to_wh_id')
            ->where('t.business_id', $businessId);

        if ($storeId) {
            $builder->groupStart()
                ->where('t.from_store_id', $storeId)
                ->orWhere('t.to_store_id', $storeId)
            ->groupEnd();
        }

        return $builder->orderBy('t.id', 'DESC')->get()->getResult();
    }

    /**
     * Dispatch: Reduces source warehouse available stock and sets items in-transit
     */
    public function dispatchTransfer($id, $userId)
    {
        $trf = $this->getTransfer($id);
        if (!$trf || ($trf->status !== 'draft' && $trf->status !== 'authorized')) {
            return false;
        }

        $this->db->transStart();

        $stockModel = new StockModel();

        foreach ($trf->items as $it) {
            // Find default source shelf location
            $loc = $this->db->table('tbl_stock_location')
                ->where('warehouse_id', $trf->from_wh_id)
                ->where('location_type', 'shelf')
                ->get()->getRow() ?? $this->db->table('tbl_stock_location')->where('warehouse_id', $trf->from_wh_id)->get()->getRow();

            $locId = $loc->id ?? 1;

            // Reduce source balance
            $stockModel->upsertBalance($trf->business_id, $trf->from_store_id, $trf->from_wh_id, $locId, $it->product_id, $it->batch_id ?: 0, -$it->requested_qty);

            // Record TRANSFER_OUT movement
            $this->db->table('tbl_stock_movement')->insert([
                'business_id'    => $trf->business_id,
                'store_id'       => $trf->from_store_id,
                'warehouse_id'   => $trf->from_wh_id,
                'location_id'    => $locId,
                'product_id'     => $it->product_id,
                'batch_id'       => $it->batch_id ?: null,
                'movement_type'  => 'TRANSFER_OUT',
                'qty'            => -$it->requested_qty,
                'unit_id'        => $it->unit_id,
                'reference_type' => 'stock_transfer',
                'reference_id'   => $trf->id,
                'note'           => 'Dispatched Transfer: ' . $trf->transfer_no,
                'created_by'     => $userId,
            ]);

            // Set dispatched qty on item
            $this->db->table('tbl_stock_transfer_item')
                ->where('id', $it->id)
                ->update(['dispatched_qty' => $it->requested_qty]);
        }

        $this->db->table('tbl_stock_transfer')
            ->where('id', $id)
            ->update([
                'status'      => 'dispatched',
                'dispatch_at' => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ]);

        $this->db->transComplete();
        return $this->db->transStatus();
    }

    /**
     * Receive: Increases destination warehouse stock and records any discrepancies
     */
    public function receiveTransfer($id, $receivedItems, $note, $userId)
    {
        $trf = $this->getTransfer($id);
        if (!$trf || $trf->status !== 'dispatched') {
            return false;
        }

        $this->db->transStart();

        $stockModel = new StockModel();

        // Receipt record
        $this->db->table('tbl_stock_transfer_receipt')->insert([
            'transfer_id' => $trf->id,
            'received_by' => $userId,
            'note'        => $note,
        ]);

        foreach ($receivedItems as $itId => $data) {
            $recQty = (float)($data['received_qty'] ?? 0);
            $dmgQty = (float)($data['damage_qty'] ?? 0);
            $locId  = (int)($data['location_id'] ?? 1);

            $line = $this->db->table('tbl_stock_transfer_item')->where('id', $itId)->get()->getRow();
            if (!$line) continue;

            $this->db->table('tbl_stock_transfer_item')
                ->where('id', $itId)
                ->update([
                    'received_qty' => $recQty,
                    'damage_qty'   => $dmgQty,
                ]);

            // Increase destination stock by good received qty
            if ($recQty > 0) {
                $stockModel->upsertBalance($trf->business_id, $trf->to_store_id, $trf->to_wh_id, $locId, $line->product_id, $line->batch_id ?: 0, $recQty);

                $this->db->table('tbl_stock_movement')->insert([
                    'business_id'    => $trf->business_id,
                    'store_id'       => $trf->to_store_id,
                    'warehouse_id'   => $trf->to_wh_id,
                    'location_id'    => $locId,
                    'product_id'     => $line->product_id,
                    'batch_id'       => $line->batch_id ?: null,
                    'movement_type'  => 'TRANSFER_IN',
                    'qty'            => $recQty,
                    'unit_id'        => $line->unit_id,
                    'reference_type' => 'stock_transfer',
                    'reference_id'   => $trf->id,
                    'note'           => 'Received Transfer: ' . $trf->transfer_no,
                    'created_by'     => $userId,
                ]);
            }

            // If damaged items recorded, place them in damaged/quarantine location
            if ($dmgQty > 0) {
                $dmgLoc = $this->db->table('tbl_stock_location')
                    ->where('warehouse_id', $trf->to_wh_id)
                    ->where('location_type', 'damaged')
                    ->get()->getRow();

                $dLocId = $dmgLoc ? $dmgLoc->id : $locId;

                $stockModel->upsertBalance($trf->business_id, $trf->to_store_id, $trf->to_wh_id, $dLocId, $line->product_id, $line->batch_id ?: 0, $dmgQty);

                $this->db->table('tbl_stock_movement')->insert([
                    'business_id'    => $trf->business_id,
                    'store_id'       => $trf->to_store_id,
                    'warehouse_id'   => $trf->to_wh_id,
                    'location_id'    => $dLocId,
                    'product_id'     => $line->product_id,
                    'batch_id'       => $line->batch_id ?: null,
                    'movement_type'  => 'DAMAGE_OUT',
                    'qty'            => $dmgQty,
                    'unit_id'        => $line->unit_id,
                    'reference_type' => 'stock_transfer',
                    'reference_id'   => $trf->id,
                    'note'           => 'Damaged in transit: ' . $trf->transfer_no,
                    'created_by'     => $userId,
                ]);
            }
        }

        $this->db->table('tbl_stock_transfer')
            ->where('id', $id)
            ->update([
                'status'      => 'received',
                'received_at' => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ]);

        $this->db->transComplete();
        return $this->db->transStatus();
    }
}
