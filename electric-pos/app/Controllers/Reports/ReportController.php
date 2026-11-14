<?php
namespace App\Controllers\Reports;

use App\Controllers\EposController;
use App\Models\ReportModel;
use App\Models\StockModel;

class ReportController extends EposController
{
    protected $reportModel;
    protected $stockModel;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->reportModel = new ReportModel();
        $this->stockModel  = new StockModel();
    }

    public function index()
    {
        return redirect()->to(base_url('reports/sales'));
    }

    public function sales()
    {
        $this->global['pageTitle'] = 'EPOS | Sales Analytics & Reports';

        $dateFrom = $this->request->getGet('date_from') ?: date('Y-m-d', strtotime('-30 days'));
        $dateTo   = $this->request->getGet('date_to') ?: date('Y-m-d');

        $data = [
            'summary'     => $this->reportModel->getSalesSummary($this->businessId, $this->storeId, $dateFrom, $dateTo),
            'byProduct'   => $this->reportModel->getSalesByProduct($this->businessId, $this->storeId, $dateFrom, $dateTo),
            'byCashier'   => $this->reportModel->getSalesByCashier($this->businessId, $this->storeId, $dateFrom, $dateTo),
            'dateFrom'    => $dateFrom,
            'dateTo'      => $dateTo,
        ];

        return $this->loadEposView('reports/sales_report', $data);
    }

    public function stock()
    {
        $this->global['pageTitle'] = 'EPOS | Stock Valuation & Inventory Report';
        $data = [
            'stock' => $this->stockModel->getStockOverview($this->businessId, $this->storeId),
        ];
        return $this->loadEposView('reports/stock_report', $data);
    }

    public function purchase()
    {
        $this->global['pageTitle'] = 'EPOS | Supplier Purchases & Outstanding Payables';
        $data = [
            'payables' => $this->reportModel->getPurchasePayables($this->businessId),
        ];
        return $this->loadEposView('reports/purchase_report', $data);
    }
}
