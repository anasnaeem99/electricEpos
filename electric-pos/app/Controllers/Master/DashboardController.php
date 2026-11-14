<?php
namespace App\Controllers\Master;

use App\Controllers\EposController;

class DashboardController extends EposController
{
    public function index()
    {
        $today = date('Y-m-d');
        $db = $this->db;

        // Today's Sales
        $todaySales = $db->table('tbl_sale')
            ->select('COUNT(*) as count, COALESCE(SUM(total_amount), 0) as total')
            ->where('store_id', $this->storeId)
            ->where('DATE(sale_date)', $today)
            ->where('sale_status !=', 'cancelled')
            ->get()->getRow();

        // Total Stock Value
        $stockVal = $db->table('tbl_stock_balance sb')
            ->select('COALESCE(SUM(sb.quantity * p.purchase_price), 0) as val, COALESCE(SUM(sb.quantity), 0) as total_qty')
            ->join('tbl_product p', 'p.id = sb.product_id')
            ->where('sb.store_id', $this->storeId)
            ->get()->getRow();

        // Low Stock Count
        $lowStockCount = $db->table('tbl_stock_balance sb')
            ->join('tbl_product p', 'p.id = sb.product_id')
            ->where('sb.store_id', $this->storeId)
            ->where('sb.quantity <=', 'p.min_stock', false)
            ->where('p.is_active', 1)
            ->countAllResults();

        // Pending Transfers
        $pendingTransfers = $db->table('tbl_stock_transfer')
            ->where('to_store_id', $this->storeId)
            ->whereIn('status', ['authorized', 'dispatched'])
            ->countAllResults();

        // Recent 5 Sales
        $recentSales = $db->table('tbl_sale s')
            ->select('s.*, c.name as customer_name, u.full_name as cashier_name')
            ->join('tbl_customer c', 'c.id = s.customer_id', 'left')
            ->join('tbl_user u', 'u.id = s.cashier_id', 'left')
            ->where('s.store_id', $this->storeId)
            ->orderBy('s.id', 'DESC')
            ->limit(7)
            ->get()->getResult();

        // Top 5 Selling Products
        $topProducts = $db->table('tbl_sale_item si')
            ->select('si.product_name, SUM(si.qty) as total_qty, SUM(si.line_total) as total_revenue')
            ->join('tbl_sale s', 's.id = si.sale_id')
            ->where('s.store_id', $this->storeId)
            ->groupBy('si.product_id, si.product_name')
            ->orderBy('total_qty', 'DESC')
            ->limit(5)
            ->get()->getResult();

        // Daily trend (last 7 days)
        $sevenDaysAgo = date('Y-m-d', strtotime('-6 days'));
        $dailyTrends = $db->table('tbl_sale')
            ->select('DATE(sale_date) as sdate, COALESCE(SUM(total_amount), 0) as total')
            ->where('store_id', $this->storeId)
            ->where('DATE(sale_date) >=', $sevenDaysAgo)
            ->where('sale_status !=', 'cancelled')
            ->groupBy('DATE(sale_date)')
            ->orderBy('sdate', 'ASC')
            ->get()->getResult();

        $this->global['pageTitle'] = 'EPOS | Dashboard';

        $data = [
            'todaySales'       => $todaySales->total ?? 0,
            'todayInvoices'    => $todaySales->count ?? 0,
            'stockValue'       => $stockVal->val ?? 0,
            'totalStockQty'    => $stockVal->total_qty ?? 0,
            'lowStockCount'    => $lowStockCount,
            'pendingTransfers' => $pendingTransfers,
            'recentSales'      => $recentSales,
            'topProducts'      => $topProducts,
            'dailyTrends'      => $dailyTrends,
        ];

        return $this->loadEposView('epos_dashboard', $data);
    }
}
