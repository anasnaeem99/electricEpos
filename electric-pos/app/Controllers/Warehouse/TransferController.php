<?php
namespace App\Controllers\Warehouse;

use App\Controllers\EposController;
use App\Models\TransferModel;
use App\Models\StoreModel;
use App\Models\ProductModel;

class TransferController extends EposController
{
    protected $transferModel;
    protected $storeModel;
    protected $productModel;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->transferModel = new TransferModel();
        $this->storeModel    = new StoreModel();
        $this->productModel  = new ProductModel();
    }

    public function index()
    {
        $this->global['pageTitle'] = 'EPOS | Stock Transfers';
        $data = [
            'transfers' => $this->transferModel->getAllTransfers($this->businessId, $this->storeId),
        ];
        return $this->loadEposView('warehouse/transfer_list', $data);
    }

    public function create()
    {
        if ($this->request->getMethod() === 'post') {
            $fromStore = (int)$this->request->getPost('from_store_id');
            $fromWh    = (int)$this->request->getPost('from_wh_id');
            $toStore   = (int)$this->request->getPost('to_store_id');
            $toWh      = (int)$this->request->getPost('to_wh_id');
            $rawItems  = $this->request->getPost('items');

            if (!$fromStore || !$fromWh || !$toStore || !$toWh || empty($rawItems)) {
                $this->session->setFlashdata('error', 'From/To store & warehouses and items are required.');
                return redirect()->back()->withInput();
            }

            $items = [];
            foreach ($rawItems as $row) {
                if (empty($row['product_id']) || empty($row['qty'])) continue;
                $items[] = [
                    'product_id'    => (int)$row['product_id'],
                    'batch_id'      => !empty($row['batch_id']) ? (int)$row['batch_id'] : null,
                    'unit_id'       => (int)($row['unit_id'] ?? 1),
                    'requested_qty' => (float)$row['qty'],
                ];
            }

            $header = [
                'business_id'   => $this->businessId,
                'from_store_id' => $fromStore,
                'from_wh_id'    => $fromWh,
                'to_store_id'   => $toStore,
                'to_wh_id'      => $toWh,
                'status'        => 'draft',
                'note'          => $this->request->getPost('note'),
                'created_by'    => $this->userId,
            ];

            $trfId = $this->transferModel->createTransfer($header, $items);
            if ($trfId) {
                if ($this->request->getPost('action_dispatch')) {
                    $this->transferModel->dispatchTransfer($trfId, $this->userId);
                    $this->session->setFlashdata('success', 'Transfer created and dispatched!');
                } else {
                    $this->session->setFlashdata('success', 'Transfer order created.');
                }
                return redirect()->to(base_url('warehouse/transfers'));
            }
        }

        $this->global['pageTitle'] = 'EPOS | New Stock Transfer';
        $stores = $this->storeModel->getAllStores($this->businessId);

        $data = [
            'stores'     => $stores,
            'warehouses' => $this->db->table('tbl_warehouse')->where('business_id', $this->businessId)->get()->getResult(),
            'units'      => $this->productModel->getUnits($this->businessId),
        ];

        return $this->loadEposView('warehouse/transfer_form', $data);
    }

    public function dispatch($id)
    {
        $res = $this->transferModel->dispatchTransfer($id, $this->userId);
        if ($res) {
            $this->session->setFlashdata('success', 'Transfer dispatched! Stock moved out of source warehouse.');
        } else {
            $this->session->setFlashdata('error', 'Could not dispatch transfer.');
        }
        return redirect()->back();
    }

    public function receive($id)
    {
        $trf = $this->transferModel->getTransfer($id);
        if (!$trf) {
            $this->session->setFlashdata('error', 'Transfer not found.');
            return redirect()->to(base_url('warehouse/transfers'));
        }

        if ($this->request->getMethod() === 'post') {
            $receivedItems = $this->request->getPost('items');
            $note          = $this->request->getPost('note');

            $res = $this->transferModel->receiveTransfer($id, $receivedItems, $note, $this->userId);
            if ($res) {
                $this->session->setFlashdata('success', 'Transfer received successfully! Stock placed in destination shelf.');
                return redirect()->to(base_url('warehouse/transfers'));
            }
        }

        $this->global['pageTitle'] = 'EPOS | Receive Transfer: ' . esc($trf->transfer_no);
        $locations = $this->storeModel->getLocations($trf->to_wh_id);

        $data = [
            'trf'       => $trf,
            'locations' => $locations,
        ];

        return $this->loadEposView('warehouse/transfer_receive', $data);
    }
}
