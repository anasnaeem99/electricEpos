<?php
namespace App\Models;

use CodeIgniter\Model;

class AdjustmentModel extends Model
{
    protected $table = 'tbl_stock_adjustment';
    protected $primaryKey = 'id';

    public function generateAdjNo($businessId)
    {
        $today = date('Ymd');
        $count = $this->db->table('tbl_stock_adjustment')
            ->where('business_id', $businessId)
            ->where('DATE(created_at)', date('Y-m-d'))
            ->countAllResults();

        return 'ADJ-' . $today . '-' . str_pad($count + 1, 4, '0', STR_PAD_LEFT);
    }

    public function createAdjustment($header, $items)
    {
        $this->db->transStart();

        $header['adj_no'] = $this->generateAdjNo($header['business_id']);
        $this->db->table('tbl_stock_adjustment')->insert($header);
        $adjId = $this->db->insertID();

        foreach ($items as $item) {
            $item['adjustment_id'] = $adjId;
            $this->db->table('tbl_stock_adjustment_item')->insert($item);
        }

        $this->db->transComplete();
        return $this->db->transStatus() ? $adjId : false;
    }

    public function postAdjustment($id, $userId)
    {
        $adj = $this->db->table('tbl_stock_adjustment')->where('id', $id)->get()->getRow();
        if (!$adj || $adj->status === 'posted') return false;

        $items = $this->db->table('tbl_stock_adjustment_item')->where('adjustment_id', $id)->get()->getResult();

        $this->db->transStart();
        $stockModel = new StockModel();

        foreach ($items as $it) {
            $moveType = ($it->qty_adjusted >= 0) ? 'STOCK_ADJUSTMENT_IN' : 'STOCK_ADJUSTMENT_OUT';

            $stockModel->upsertBalance($adj->business_id, $adj->store_id, $adj->warehouse_id, $it->location_id, $it->product_id, $it->batch_id ?: 0, $it->qty_adjusted);

            $this->db->table('tbl_stock_movement')->insert([
                'business_id'    => $adj->business_id,
                'store_id'       => $adj->store_id,
                'warehouse_id'   => $adj->warehouse_id,
                'location_id'    => $it->location_id,
                'product_id'     => $it->product_id,
                'batch_id'       => $it->batch_id ?: null,
                'movement_type'  => $moveType,
                'qty'            => $it->qty_adjusted,
                'unit_id'        => $it->unit_id,
                'reference_type' => 'stock_adjustment',
                'reference_id'   => $adj->id,
                'note'           => $adj->reason . ' (' . ($it->reason ?? '') . ')',
                'created_by'     => $userId,
            ]);
        }

        $this->db->table('tbl_stock_adjustment')
            ->where('id', $id)
            ->update([
                'status'    => 'posted',
                'posted_by' => $userId,
                'posted_at' => date('Y-m-d H:i:s'),
            ]);

        $this->db->transComplete();
        return $this->db->transStatus();
    }
}
