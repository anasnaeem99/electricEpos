<?php
namespace App\Models;

use CodeIgniter\Model;

class CustomerModel extends Model
{
    protected $table = 'tbl_customer';
    protected $primaryKey = 'id';
    protected $allowedFields = ['business_id', 'name', 'phone', 'email', 'is_member', 'is_active'];

    public function searchCustomer($phoneOrName, $businessId, $storeId = null)
    {
        $q = trim($phoneOrName);
        $builder = $this->db->table('tbl_customer c')
            ->select('c.*, COALESCE(la.available_points, 0) as loyalty_points, COALESCE(la.pending_points, 0) as pending_points')
            ->join('tbl_loyalty_account la', 'la.customer_id = c.id' . ($storeId ? ' AND la.store_id = ' . (int)$storeId : ''), 'left')
            ->where('c.business_id', $businessId)
            ->where('c.is_active', 1)
            ->groupStart()
                ->like('c.phone', $q)
                ->orLike('c.name', $q)
            ->groupEnd()
            ->limit(10);

        return $builder->get()->getResult();
    }

    public function getCustomerWithLoyalty($id, $storeId)
    {
        return $this->db->table('tbl_customer c')
            ->select('c.*, COALESCE(la.available_points, 0) as loyalty_points, COALESCE(la.pending_points, 0) as pending_points, COALESCE(la.lifetime_earned, 0) as lifetime_earned, COALESCE(la.lifetime_redeemed, 0) as lifetime_redeemed')
            ->join('tbl_loyalty_account la', 'la.customer_id = c.id AND la.store_id = ' . (int)$storeId, 'left')
            ->where('c.id', $id)
            ->get()->getRow();
    }
}
