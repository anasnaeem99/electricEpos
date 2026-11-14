<?php
namespace App\Models;

use CodeIgniter\Model;

class LoyaltyModel extends Model
{
    protected $table = 'tbl_loyalty_account';
    protected $primaryKey = 'id';

    public function getAllAccounts($businessId, $storeId = null)
    {
        $builder = $this->db->table('tbl_loyalty_account la')
            ->select('la.*, c.name as customer_name, c.phone as customer_phone, c.email as customer_email, s.name as store_name')
            ->join('tbl_customer c', 'c.id = la.customer_id')
            ->join('tbl_store s', 's.id = la.store_id')
            ->where('c.business_id', $businessId);

        if ($storeId) {
            $builder->where('la.store_id', $storeId);
        }

        return $builder->orderBy('la.available_points', 'DESC')->get()->getResult();
    }

    public function getAccountDetails($accountId)
    {
        $acc = $this->db->table('tbl_loyalty_account la')
            ->select('la.*, c.name as customer_name, c.phone as customer_phone, s.name as store_name')
            ->join('tbl_customer c', 'c.id = la.customer_id')
            ->join('tbl_store s', 's.id = la.store_id')
            ->where('la.id', $accountId)
            ->get()->getRow();

        if ($acc) {
            $acc->lots = $this->db->table('tbl_loyalty_points_lot')
                ->where('account_id', $accountId)
                ->orderBy('id', 'DESC')
                ->get()->getResult();

            $acc->transactions = $this->db->table('tbl_loyalty_transaction')
                ->where('account_id', $accountId)
                ->orderBy('id', 'DESC')
                ->limit(50)
                ->get()->getResult();
        }

        return $acc;
    }

    public function getLoyaltyStats($businessId, $storeId = null)
    {
        $builder = $this->db->table('tbl_loyalty_account la')
            ->select('COUNT(*) as total_members, COALESCE(SUM(la.available_points), 0) as total_available, COALESCE(SUM(la.pending_points), 0) as total_pending, COALESCE(SUM(la.lifetime_earned), 0) as total_earned, COALESCE(SUM(la.lifetime_redeemed), 0) as total_redeemed')
            ->join('tbl_customer c', 'c.id = la.customer_id')
            ->where('c.business_id', $businessId);

        if ($storeId) {
            $builder->where('la.store_id', $storeId);
        }

        return $builder->get()->getRow();
    }
}
