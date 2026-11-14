<?php
namespace App\Models;

use CodeIgniter\Model;

class SaleModel extends Model
{
    protected $table = 'tbl_sale';
    protected $primaryKey = 'id';

    public function getSale($id)
    {
        $sale = $this->db->table('tbl_sale s')
            ->select('s.*, c.name as customer_name, c.phone as customer_phone, st.name as store_name, st.address as store_address, st.phone as store_phone, st.invoice_header, u.full_name as cashier_name')
            ->join('tbl_customer c', 'c.id = s.customer_id', 'left')
            ->join('tbl_store st', 'st.id = s.store_id')
            ->join('tbl_user u', 'u.id = s.cashier_id')
            ->where('s.id', $id)
            ->get()->getRow();

        if ($sale) {
            $sale->items = $this->db->table('tbl_sale_item')->where('sale_id', $id)->get()->getResult();
            $sale->payments = $this->db->table('tbl_sale_payment sp')
                ->select('sp.*, pm.name as method_name')
                ->join('tbl_payment_method pm', 'pm.id = sp.payment_method_id')
                ->where('sp.sale_id', $id)
                ->get()->getResult();
        }

        return $sale;
    }

    public function getAllSales($businessId, $storeId = null, $filters = [])
    {
        $builder = $this->db->table('tbl_sale s')
            ->select('s.*, c.name as customer_name, c.phone as customer_phone, u.full_name as cashier_name')
            ->join('tbl_customer c', 'c.id = s.customer_id', 'left')
            ->join('tbl_user u', 'u.id = s.cashier_id', 'left')
            ->where('s.business_id', $businessId);

        if ($storeId) {
            $builder->where('s.store_id', $storeId);
        }

        if (!empty($filters['date_from'])) {
            $builder->where('DATE(s.sale_date) >=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $builder->where('DATE(s.sale_date) <=', $filters['date_to']);
        }

        return $builder->orderBy('s.id', 'DESC')->limit(100)->get()->getResult();
    }

    public function getActiveShift($cashierId, $storeId)
    {
        return $this->db->table('tbl_pos_shift')
            ->where('cashier_id', $cashierId)
            ->where('store_id', $storeId)
            ->where('status', 'open')
            ->orderBy('id', 'DESC')
            ->get()->getRow();
    }
}
