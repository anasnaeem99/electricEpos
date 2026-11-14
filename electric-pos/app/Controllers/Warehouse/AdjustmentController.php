<?php
namespace App\Controllers\Warehouse;

use App\Controllers\EposController;
use App\Models\AdjustmentModel;
use App\Models\StoreModel;
use App\Models\ProductModel;

class AdjustmentController extends EposController
{
    protected $adjustmentModel;
    protected $storeModel;
    protected $productModel;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->adjustmentModel = new AdjustmentModel();
        $this->storeModel      = new StoreModel();
        $this->productModel    = new ProductModel();
    }

    public function index()
    {
        $this->global['pageTitle'] = 'EPOS | Stock Adjustments';
        $adjustments = $this->db->table('tbl_stock_adjustment a')
            ->select('a.*, s.name as store_name, w.name as warehouse_name, u.full_name as creator_name')
            ->join('tbl_store s', 's.id = a.store_id')
            ->join('tbl_warehouse w', 'w.id = a.warehouse_id')
            ->join('tbl_user u', 'u.id = a.created_by', 'left')
            ->where('a.business_id', $this->businessId)
            ->where('a.store_id', $this->storeId)
            ->orderBy('a.id', 'DESC')
            ->get()->getResult();

        return $this->loadEposView('warehouse/adjustment_list', ['adjustments' => $adjustments]);
    }

    public function create()
    {
        if ($this->request->getMethod() === 'post') {
            $warehouseId = (int)$this->request->getPost('warehouse_id');
            $reason      = trim($this->request->getPost('reason') ?? 'Inventory Audit Count');
            $rawItems    = $this->request->getPost('items');

            if (!$warehouseId || empty($rawItems)) {
                $this->session->setFlashdata('error', 'Warehouse and adjusted items are required.');
                return redirect()->back()->withInput();
            }

            $items = [];
            foreach ($rawItems as $row) {
                if (empty($row['product_id']) || !isset($row['qty_adjusted'])) continue;
                $adjQty = (float)$row['qty_adjusted'];
                if ($adjQty == 0) continue;

                $items[] = [
                    'product_id'   => (int)$row['product_id'],
                    'batch_id'     => !empty($row['batch_id']) ? (int)$row['batch_id'] : null,
                    'location_id'  => (int)$row['location_id'],
                    'unit_id'      => (int)($row['unit_id'] ?? 1),
                    'qty_before'   => (float)($row['qty_before'] ?? 0),
                    'qty_adjusted' => $adjQty,
                    'reason'       => $row['item_reason'] ?? $reason,
                ];
            }

            if (empty($items)) {
                $this->session->setFlashdata('error', 'No non-zero adjustment lines entered.');
                return redirect()->back()->withInput();
            }

            $header = [
                'business_id'  => $this->businessId,
                'store_id'     => $this->storeId,
                'warehouse_id' => $warehouseId,
                'reason'       => $reason,
                'status'       => 'draft',
                'created_by'   => $this->userId,
            ];

            $adjId = $this->adjustmentModel->createAdjustment($header, $items);
            if ($adjId) {
                if ($this->request->getPost('action_post')) {
                    $this->adjustmentModel->postAdjustment($adjId, $this->userId);
                    $this->session->setFlashdata('success', 'Stock adjustment posted immediately!');
                } else {
                    $this->session->setFlashdata('success', 'Stock adjustment draft created.');
                }
                return redirect()->to(base_url('warehouse/adjustments'));
            }
        }

        $this->global['pageTitle'] = 'EPOS | New Stock Adjustment';
        $warehouses = $this->storeModel->getWarehouses($this->storeId);
        $firstWh = $warehouses[0]->id ?? 0;
        $locations = $firstWh ? $this->storeModel->getLocations($firstWh) : [];

        $data = [
            'warehouses' => $warehouses,
            'locations'  => $locations,
            'units'      => $this->productModel->getUnits($this->businessId),
        ];

        return $this->loadEposView('warehouse/adjustment_form', $data);
    }

    public function postAction($id)
    {
        $res = $this->adjustmentModel->postAdjustment($id, $this->userId);
        if ($res) {
            $this->session->setFlashdata('success', 'Adjustment posted! Stock balances updated.');
        } else {
            $this->session->setFlashdata('error', 'Could not post adjustment.');
        }
        return redirect()->back();
    }
}
