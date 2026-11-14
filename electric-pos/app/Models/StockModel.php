<?php
namespace App\Models;

use CodeIgniter\Model;

class StockModel extends Model
{
    protected $table = 'tbl_stock_balance';
    protected $primaryKey = 'id';

    public function getStockOverview($businessId, $storeId = null, $warehouseId = null, $lowStockOnly = false)
    {
        $builder = $this->db->table('tbl_stock_balance sb')
            ->select('sb.*, p.name as product_name, p.sku as product_sku, p.purchase_price, p.retail_price, p.min_stock, p.batch_tracking, p.expiry_tracking, s.name as store_name, w.name as warehouse_name, loc.name as location_name, loc.location_type, pb.batch_no, pb.expiry_date, u.abbreviation as unit_abbr')
            ->join('tbl_product p', 'p.id = sb.product_id')
            ->join('tbl_store s', 's.id = sb.store_id')
            ->join('tbl_warehouse w', 'w.id = sb.warehouse_id')
            ->join('tbl_stock_location loc', 'loc.id = sb.location_id')
            ->join('tbl_product_batch pb', 'pb.id = sb.batch_id', 'left')
            ->join('tbl_product_unit u', 'u.id = p.base_unit_id', 'left')
            ->where('sb.business_id', $businessId);

        if ($storeId) {
            $builder->where('sb.store_id', $storeId);
        }
        if ($warehouseId) {
            $builder->where('sb.warehouse_id', $warehouseId);
        }
        if ($lowStockOnly) {
            $builder->where('sb.quantity <=', 'p.min_stock', false);
        }

        return $builder->orderBy('p.name', 'ASC')->get()->getResult();
    }

    public function getMovements($businessId, $storeId = null, $limit = 100)
    {
        $builder = $this->db->table('tbl_stock_movement sm')
            ->select('sm.*, p.name as product_name, p.sku as product_sku, s.name as store_name, w.name as warehouse_name, loc.name as location_name, u.full_name as user_name, un.abbreviation as unit_abbr')
            ->join('tbl_product p', 'p.id = sm.product_id')
            ->join('tbl_store s', 's.id = sm.store_id')
            ->join('tbl_warehouse w', 'w.id = sm.warehouse_id', 'left')
            ->join('tbl_stock_location loc', 'loc.id = sm.location_id', 'left')
            ->join('tbl_user u', 'u.id = sm.created_by', 'left')
            ->join('tbl_product_unit un', 'un.id = sm.unit_id', 'left')
            ->where('sm.business_id', $businessId);

        if ($storeId) {
            $builder->where('sm.store_id', $storeId);
        }

        return $builder->orderBy('sm.id', 'DESC')->limit($limit)->get()->getResult();
    }

    public function upsertBalance($businessId, $storeId, $warehouseId, $locationId, $productId, $batchId, $qtyDelta)
    {
        $row = $this->db->table('tbl_stock_balance')
            ->where('store_id', $storeId)
            ->where('location_id', $locationId)
            ->where('product_id', $productId)
            ->where('batch_id', $batchId)
            ->get()->getRow();

        if ($row) {
            $newQty = $row->quantity + $qtyDelta;
            $this->db->table('tbl_stock_balance')
                ->where('id', $row->id)
                ->update(['quantity' => $newQty, 'updated_at' => date('Y-m-d H:i:s')]);
        } else {
            $this->db->table('tbl_stock_balance')->insert([
                'business_id'  => $businessId,
                'store_id'     => $storeId,
                'warehouse_id' => $warehouseId,
                'location_id'  => $locationId,
                'product_id'   => $productId,
                'batch_id'     => $batchId,
                'quantity'     => $qtyDelta,
                'reserved_qty' => 0.000,
            ]);
        }
    }
}
