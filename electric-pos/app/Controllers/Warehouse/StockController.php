<?php
namespace App\Controllers\Warehouse;

use App\Controllers\EposController;
use App\Models\StockModel;
use App\Models\StoreModel;

class StockController extends EposController
{
    protected $stockModel;
    protected $storeModel;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->stockModel = new StockModel();
        $this->storeModel = new StoreModel();
    }

    public function index()
    {
        $this->global['pageTitle'] = 'EPOS | Stock Balances & Locations';
        $lowOnly = ($this->request->getGet('filter') === 'low');
        $whId = $this->request->getGet('warehouse_id');

        $data = [
            'stock'      => $this->stockModel->getStockOverview($this->businessId, $this->storeId, $whId, $lowOnly),
            'warehouses' => $this->storeModel->getWarehouses($this->storeId),
            'lowOnly'    => $lowOnly,
            'selectedWh' => $whId,
        ];

        return $this->loadEposView('warehouse/stock_overview', $data);
    }

    public function movements()
    {
        $this->global['pageTitle'] = 'EPOS | Stock Movement Ledger (Audit Trail)';
        $data = [
            'movements' => $this->stockModel->getMovements($this->businessId, $this->storeId, 200),
        ];

        return $this->loadEposView('warehouse/stock_movements', $data);
    }
}
