<?php
namespace App\Models;

use CodeIgniter\Model;

class ReportModel extends Model
{
    public function getSalesSummary($businessId, $storeId = null, $dateFrom = null, $dateTo = null)
    {
        $builder = $this->db->table('tbl_sale')
            ->select('COUNT(*) as total_invoices, COALESCE(SUM(subtotal), 0) as total_gross, COALESCE(SUM(discount_amount), 0) as total_discount, COALESCE(SUM(tax_amount), 0) as total_tax, COALESCE(SUM(total_amount), 0) as total_net')
            ->where('business_id', $businessId)
            ->where('sale_status !=', 'cancelled');

        if ($storeId) $builder->where('store_id', $storeId);
        if ($dateFrom) $builder->where('DATE(sale_date) >=', $dateFrom);
        if ($dateTo) $builder->where('DATE(sale_date) <=', $dateTo);

        return $builder->get()->getRow();
    }

    public function getSalesByProduct($businessId, $storeId = null, $dateFrom = null, $dateTo = null, $limit = 50)
    {
        $builder = $this->db->table('tbl_sale_item si')
            ->select('si.product_id, si.product_name, si.product_sku, si.unit_name, SUM(si.qty) as qty_sold, SUM(si.line_total) as total_revenue, AVG(si.unit_price) as avg_price')
            ->join('tbl_sale s', 's.id = si.sale_id')
            ->where('s.business_id', $businessId)
            ->where('s.sale_status !=', 'cancelled');

        if ($storeId) $builder->where('s.store_id', $storeId);
        if ($dateFrom) $builder->where('DATE(s.sale_date) >=', $dateFrom);
        if ($dateTo) $builder->where('DATE(s.sale_date) <=', $dateTo);

        return $builder->groupBy('si.product_id, si.product_name, si.product_sku, si.unit_name')
            ->orderBy('total_revenue', 'DESC')
            ->limit($limit)
            ->get()->getResult();
    }

    public function getSalesByCashier($businessId, $storeId = null, $dateFrom = null, $dateTo = null)
    {
        $builder = $this->db->table('tbl_sale s')
            ->select('u.full_name as cashier_name, COUNT(*) as invoices_billed, SUM(s.total_amount) as total_sales')
            ->join('tbl_user u', 'u.id = s.cashier_id')
            ->where('s.business_id', $businessId)
            ->where('s.sale_status !=', 'cancelled');

        if ($storeId) $builder->where('s.store_id', $storeId);
        if ($dateFrom) $builder->where('DATE(s.sale_date) >=', $dateFrom);
        if ($dateTo) $builder->where('DATE(s.sale_date) <=', $dateTo);

        return $builder->groupBy('s.cashier_id, u.full_name')
            ->orderBy('total_sales', 'DESC')
            ->get()->getResult();
    }

    public function getPurchasePayables($businessId)
    {
        return $this->db->table('tbl_supplier s')
            ->select('s.*, (s.opening_balance + COALESCE(SUM(sl.credit), 0) - COALESCE(SUM(sl.debit), 0)) as current_balance')
            ->join('tbl_supplier_ledger sl', 'sl.supplier_id = s.id', 'left')
            ->where('s.business_id', $businessId)
            ->groupBy('s.id')
            ->having('current_balance >', 0)
            ->orderBy('current_balance', 'DESC')
            ->get()->getResult();
    }
}
