<?php
namespace App\Controllers\Inventory;

use App\Controllers\EposController;
use App\Models\PurchaseModel;
use App\Models\SupplierModel;
use App\Models\ProductModel;
use App\Models\StoreModel;

class PurchaseController extends EposController
{
    protected $purchaseModel;
    protected $supplierModel;
    protected $productModel;
    protected $storeModel;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->purchaseModel = new PurchaseModel();
        $this->supplierModel = new SupplierModel();
        $this->productModel  = new ProductModel();
        $this->storeModel    = new StoreModel();
    }

    public function index()
    {
        $this->global['pageTitle'] = 'EPOS | Purchase Orders';
        $data = [
            'purchaseOrders' => $this->purchaseModel->getAllPOs($this->businessId, $this->storeId),
        ];
        return $this->loadEposView('purchase/po_list', $data);
    }

    public function createPo()
    {
        if ($this->request->getMethod() === 'post') {
            $supplierId = (int)$this->request->getPost('supplier_id');
            $rawItems   = $this->request->getPost('items');

            if ($supplierId <= 0 || empty($rawItems) || !is_array($rawItems)) {
                $this->session->setFlashdata('error', 'Supplier and at least one item are required.');
                return redirect()->back()->withInput();
            }

            $items = [];
            $subtotal = 0;
            $taxTotal = 0;

            foreach ($rawItems as $row) {
                if (empty($row['product_id']) || empty($row['qty'])) continue;
                $qty = (float)$row['qty'];
                $cost = (float)$row['unit_cost'];
                $lineTax = (float)($row['tax_amount'] ?? 0);
                $lineTotal = ($qty * $cost) + $lineTax;

                $subtotal += ($qty * $cost);
                $taxTotal += $lineTax;

                $items[] = [
                    'product_id'   => (int)$row['product_id'],
                    'unit_id'      => (int)($row['unit_id'] ?? 1),
                    'ordered_qty'  => $qty,
                    'received_qty' => 0.000,
                    'unit_cost'    => $cost,
                    'tax_amount'   => $lineTax,
                    'line_total'   => $lineTotal,
                ];
            }

            if (empty($items)) {
                $this->session->setFlashdata('error', 'No valid items in purchase order.');
                return redirect()->back()->withInput();
            }

            $header = [
                'business_id'   => $this->businessId,
                'store_id'      => $this->storeId,
                'supplier_id'   => $supplierId,
                'po_date'       => $this->request->getPost('po_date') ?: date('Y-m-d'),
                'expected_date' => $this->request->getPost('expected_date') ?: null,
                'status'        => 'pending',
                'subtotal'      => $subtotal,
                'discount'      => (float)($this->request->getPost('discount') ?: 0),
                'tax_amount'    => $taxTotal,
                'total_amount'  => ($subtotal - (float)($this->request->getPost('discount') ?: 0)) + $taxTotal,
                'note'          => $this->request->getPost('note'),
                'created_by'    => $this->userId,
            ];

            $poId = $this->purchaseModel->createPO($header, $items);
            if ($poId) {
                $this->session->setFlashdata('success', 'Purchase Order created successfully.');
                return redirect()->to(base_url('purchase/po/view/' . $poId));
            } else {
                $this->session->setFlashdata('error', 'Failed to create Purchase Order.');
                return redirect()->back()->withInput();
            }
        }

        $this->global['pageTitle'] = 'EPOS | Create Purchase Order';
        $data = [
            'suppliers' => $this->supplierModel->getAllSuppliers($this->businessId),
            'units'     => $this->productModel->getUnits($this->businessId),
        ];
        return $this->loadEposView('purchase/po_form', $data);
    }

    public function viewPo($id)
    {
        $po = $this->purchaseModel->getPO($id);
        if (!$po) {
            $this->session->setFlashdata('error', 'Purchase Order not found.');
            return redirect()->to(base_url('purchase/po'));
        }

        $this->global['pageTitle'] = 'EPOS | ' . esc($po->po_no);
        $data = ['po' => $po];
        return $this->loadEposView('purchase/po_view', $data);
    }

    public function approvePo($id)
    {
        $this->purchaseModel->approvePO($id, $this->userId);
        $this->session->setFlashdata('success', 'Purchase order approved. Ready for receiving.');
        return redirect()->back();
    }

    // GRN (Goods Received Note) Listing & Form
    public function grnList()
    {
        $this->global['pageTitle'] = 'EPOS | Goods Received Notes (GRN)';
        $data = [
            'grnList' => $this->purchaseModel->getAllGRNs($this->businessId, $this->storeId),
        ];
        return $this->loadEposView('purchase/grn_list', $data);
    }

    public function createGrn()
    {
        $poId = $this->request->getGet('po_id');
        $po = null;
        if ($poId) {
            $po = $this->purchaseModel->getPO($poId);
        }

        if ($this->request->getMethod() === 'post') {
            $supplierId  = (int)$this->request->getPost('supplier_id');
            $warehouseId = (int)$this->request->getPost('warehouse_id');
            $rawItems    = $this->request->getPost('items');

            if ($supplierId <= 0 || $warehouseId <= 0 || empty($rawItems)) {
                $this->session->setFlashdata('error', 'Supplier, Warehouse and items are required.');
                return redirect()->back()->withInput();
            }

            $items = [];
            $subtotal = 0;

            foreach ($rawItems as $row) {
                if (empty($row['product_id']) || empty($row['received_qty'])) continue;
                $qty = (float)$row['received_qty'];
                $cost = (float)$row['unit_cost'];
                $lineTotal = $qty * $cost;
                $subtotal += $lineTotal;

                $items[] = [
                    'poi_id'       => !empty($row['poi_id']) ? (int)$row['poi_id'] : null,
                    'product_id'   => (int)$row['product_id'],
                    'batch_id'     => !empty($row['batch_id']) ? (int)$row['batch_id'] : null,
                    'location_id'  => (int)$row['location_id'],
                    'unit_id'      => (int)($row['unit_id'] ?? 1),
                    'received_qty' => $qty,
                    'unit_cost'    => $cost,
                    'tax_amount'   => 0,
                    'line_total'   => $lineTotal,
                    'expiry_date'  => !empty($row['expiry_date']) ? $row['expiry_date'] : null,
                ];
            }

            $header = [
                'business_id'  => $this->businessId,
                'store_id'     => $this->storeId,
                'warehouse_id' => $warehouseId,
                'po_id'        => !empty($poId) ? $poId : null,
                'supplier_id'  => $supplierId,
                'grn_date'     => $this->request->getPost('grn_date') ?: date('Y-m-d'),
                'status'       => 'draft',
                'subtotal'     => $subtotal,
                'discount'     => 0,
                'tax_amount'   => 0,
                'total_amount' => $subtotal,
                'note'         => $this->request->getPost('note'),
                'created_by'   => $this->userId,
            ];

            $grnId = $this->purchaseModel->createGRN($header, $items);
            if ($grnId) {
                // Check if user clicked "Post Immediately"
                if ($this->request->getPost('action_post')) {
                    $this->purchaseModel->postGRN($grnId, $this->userId);
                    $this->session->setFlashdata('success', 'GRN created and stock posted successfully!');
                } else {
                    $this->session->setFlashdata('success', 'GRN draft created.');
                }
                return redirect()->to(base_url('purchase/grn/view/' . $grnId));
            }
        }

        $this->global['pageTitle'] = 'EPOS | Receive Goods (GRN)';
        $warehouses = $this->storeModel->getWarehouses($this->storeId);
        $firstWh = $warehouses[0]->id ?? 0;
        $locations = $firstWh ? $this->storeModel->getLocations($firstWh) : [];

        $data = [
            'po'         => $po,
            'suppliers'  => $this->supplierModel->getAllSuppliers($this->businessId),
            'warehouses' => $warehouses,
            'locations'  => $locations,
            'units'      => $this->productModel->getUnits($this->businessId),
        ];

        return $this->loadEposView('purchase/grn_form', $data);
    }

    public function viewGrn($id)
    {
        $grn = $this->purchaseModel->getGRN($id);
        if (!$grn) {
            $this->session->setFlashdata('error', 'GRN not found.');
            return redirect()->to(base_url('purchase/grn'));
        }

        $this->global['pageTitle'] = 'EPOS | ' . esc($grn->grn_no);
        $data = ['grn' => $grn];
        return $this->loadEposView('purchase/grn_view', $data);
    }

    public function postGrnAction($id)
    {
        $res = $this->purchaseModel->postGRN($id, $this->userId);
        if ($res) {
            $this->session->setFlashdata('success', 'GRN confirmed and posted. Stock and supplier ledger updated!');
        } else {
            $this->session->setFlashdata('error', 'Failed to post GRN. Stock not updated.');
        }
        return redirect()->back();
    }
}
