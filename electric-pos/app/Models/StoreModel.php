<?php
namespace App\Models;
use CodeIgniter\Model;

class StoreModel extends Model
{
    protected $table = 'tbl_store';
    protected $primaryKey = 'id';

    public function getAllStores($businessId)
    {
        return $this->where('business_id', $businessId)->where('is_active', 1)->findAll();
    }

    public function getStoreById($id)
    {
        return $this->find($id);
    }

    public function getWarehouses($storeId)
    {
        return $this->db->table('tbl_warehouse')
            ->where('store_id', $storeId)->where('is_active', 1)
            ->orderBy('name')->get()->getResult();
    }

    public function getLocations($warehouseId)
    {
        return $this->db->table('tbl_stock_location')
            ->where('warehouse_id', $warehouseId)->where('is_active', 1)
            ->orderBy('name')->get()->getResult();
    }

    public function getStoreWithWarehouses($storeId)
    {
        $store = $this->find($storeId);
        if ($store) {
            $store->warehouses = $this->getWarehouses($storeId);
            foreach ($store->warehouses as $wh) {
                $wh->locations = $this->getLocations($wh->id);
            }
        }
        return $store;
    }
}
