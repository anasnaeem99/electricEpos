<?php
namespace App\Controllers\POS;

use App\Controllers\EposController;
use App\Services\SalesService;
use App\Models\SaleModel;
use App\Models\ProductModel;
use App\Models\CustomerModel;

class PosController extends EposController
{
    protected $salesService;
    protected $saleModel;
    protected $productModel;
    protected $customerModel;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->salesService  = new SalesService();
        $this->saleModel     = new SaleModel();
        $this->productModel  = new ProductModel();
        $this->customerModel = new CustomerModel();
    }

    public function terminal()
    {
        // Check active shift
        $shift = $this->saleModel->getActiveShift($this->userId, $this->storeId);
        if (!$shift) {
            return redirect()->to(base_url('pos/open-shift'));
        }

        $paymentMethods = $this->db->table('tbl_payment_method')
            ->where('business_id', $this->businessId)
            ->where('is_active', 1)
            ->orderBy('sort_order', 'ASC')
            ->get()->getResult();

        $registers = $this->db->table('tbl_pos_register')
            ->where('store_id', $this->storeId)
            ->where('is_active', 1)
            ->get()->getResult();

        $data = [
            'shift'          => $shift,
            'paymentMethods' => $paymentMethods,
            'registers'      => $registers,
            'storeName'      => $this->storeName,
            'cashierName'    => $this->userName,
            'storeId'        => $this->storeId,
            'businessId'     => $this->businessId,
        ];

        // Terminal view is standalone fullscreen (no AdminLTE sidebar)
        return view('pos/terminal', $data);
    }

    public function openShift()
    {
        $existing = $this->saleModel->getActiveShift($this->userId, $this->storeId);
        if ($existing) {
            return redirect()->to(base_url('pos/terminal'));
        }

        if ($this->request->getMethod() === 'post') {
            $openingCash = (float)($this->request->getPost('opening_cash') ?: 0);
            $registerId  = (int)($this->request->getPost('register_id') ?: 1);

            $this->db->table('tbl_pos_shift')->insert([
                'store_id'     => $this->storeId,
                'register_id'  => $registerId,
                'cashier_id'   => $this->userId,
                'opening_cash' => $openingCash,
                'status'       => 'open',
                'shift_start'  => date('Y-m-d H:i:s'),
            ]);

            $this->session->setFlashdata('success', 'Shift opened with Rs. ' . number_format($openingCash, 2));
            return redirect()->to(base_url('pos/terminal'));
        }

        $registers = $this->db->table('tbl_pos_register')
            ->where('store_id', $this->storeId)
            ->where('is_active', 1)
            ->get()->getResult();

        $this->global['pageTitle'] = 'EPOS | Open Cashier Shift';
        return $this->loadEposView('pos/shift_open', ['registers' => $registers]);
    }

    public function closeShift()
    {
        $shift = $this->saleModel->getActiveShift($this->userId, $this->storeId);
        if (!$shift) {
            return redirect()->to(base_url('dashboard'));
        }

        if ($this->request->getMethod() === 'post') {
            $closingCash = (float)$this->request->getPost('closing_cash');

            // Calculate cash sales in this shift
            $cashSales = $this->db->table('tbl_sale s')
                ->join('tbl_sale_payment sp', 'sp.sale_id = s.id')
                ->where('s.shift_id', $shift->id)
                ->where('sp.payment_method_id', 1) // cash
                ->selectSum('sp.amount')
                ->get()->getRow()->amount ?? 0;

            $cashIns = $this->db->table('tbl_cash_transaction')
                ->where('shift_id', $shift->id)->where('type', 'cash_in')
                ->selectSum('amount')->get()->getRow()->amount ?? 0;

            $cashOuts = $this->db->table('tbl_cash_transaction')
                ->where('shift_id', $shift->id)->where('type', 'cash_out')
                ->selectSum('amount')->get()->getRow()->amount ?? 0;

            $expectedCash = $shift->opening_cash + $cashSales + $cashIns - $cashOuts;
            $diff = $closingCash - $expectedCash;

            $this->db->table('tbl_pos_shift')
                ->where('id', $shift->id)
                ->update([
                    'closing_cash'  => $closingCash,
                    'expected_cash' => $expectedCash,
                    'difference'    => $diff,
                    'shift_end'     => date('Y-m-d H:i:s'),
                    'status'        => 'closed',
                ]);

            $this->session->setFlashdata('success', 'Shift closed. Expected: Rs. ' . number_format($expectedCash, 2) . ' | Actual: Rs. ' . number_format($closingCash, 2) . ' | Diff: Rs. ' . number_format($diff, 2));
            return redirect()->to(base_url('dashboard'));
        }

        return redirect()->to(base_url('pos/terminal'));
    }

    public function dayClose()
    {
        $today = date('Y-m-d');

        if ($this->request->getMethod() === 'post') {
            $actualCash = (float)$this->request->getPost('actual_cash');
            $shiftId = (int)($this->request->getPost('shift_id') ?? 1);

            // Compute expected cash for today
            $cashSales = $this->db->table('tbl_sale s')
                ->join('tbl_sale_payment sp', 'sp.sale_id = s.id')
                ->where('s.store_id', $this->storeId)
                ->where('DATE(s.sale_date)', $today)
                ->where('sp.payment_method_id', 1)
                ->selectSum('sp.amount')
                ->get()->getRow()->amount ?? 0;

            $openCash = $this->db->table('tbl_pos_shift')
                ->where('store_id', $this->storeId)
                ->where('DATE(shift_start)', $today)
                ->selectSum('opening_cash')
                ->get()->getRow()->opening_cash ?? 0;

            $expected = $openCash + $cashSales;

            $this->db->table('tbl_day_close')->insert([
                'store_id'      => $this->storeId,
                'register_id'   => 1,
                'shift_id'      => $shiftId,
                'cashier_id'    => $this->userId,
                'close_date'    => $today,
                'opening_cash'  => $openCash,
                'cash_sales'    => $cashSales,
                'cash_in'       => 0,
                'cash_out'      => 0,
                'cash_refunds'  => 0,
                'expected_cash' => $expected,
                'actual_cash'   => $actualCash,
                'status'        => 'closed',
                'closed_by'     => $this->userId,
                'closed_at'     => date('Y-m-d H:i:s'),
            ]);

            $this->session->setFlashdata('success', 'Day close submitted successfully.');
            return redirect()->to(base_url('dashboard'));
        }

        // Summary for today
        $summary = $this->db->table('tbl_sale')
            ->select('COUNT(*) as total_bills, COALESCE(SUM(total_amount), 0) as total_sales, COALESCE(SUM(discount_amount), 0) as total_disc, COALESCE(SUM(tax_amount), 0) as total_tax')
            ->where('store_id', $this->storeId)
            ->where('DATE(sale_date)', $today)
            ->where('sale_status !=', 'cancelled')
            ->get()->getRow();

        $paymentBreakdown = $this->db->table('tbl_sale_payment sp')
            ->select('pm.name as method_name, SUM(sp.amount) as method_total')
            ->join('tbl_payment_method pm', 'pm.id = sp.payment_method_id')
            ->join('tbl_sale s', 's.id = sp.sale_id')
            ->where('s.store_id', $this->storeId)
            ->where('DATE(s.sale_date)', $today)
            ->groupBy('sp.payment_method_id, pm.name')
            ->get()->getResult();

        $activeShift = $this->saleModel->getActiveShift($this->userId, $this->storeId);

        $this->global['pageTitle'] = 'EPOS | Daily Day Close Reconciliation';
        $data = [
            'summary'          => $summary,
            'paymentBreakdown' => $paymentBreakdown,
            'activeShift'      => $activeShift,
            'today'            => $today,
        ];

        return $this->loadEposView('pos/day_close', $data);
    }

    // Process Sale AJAX
    public function sale()
    {
        $raw = $this->request->getJSON(true);
        if (!$raw || empty($raw['items'])) {
            return $this->jsonError('Cart is empty.');
        }

        $shift = $this->saleModel->getActiveShift($this->userId, $this->storeId);

        $saleData = [
            'business_id'      => $this->businessId,
            'store_id'         => $this->storeId,
            'register_id'      => $shift ? $shift->register_id : 1,
            'shift_id'         => $shift ? $shift->id : null,
            'cashier_id'       => $this->userId,
            'customer_id'      => !empty($raw['customer_id']) ? (int)$raw['customer_id'] : null,
            'local_txn_uuid'   => $raw['local_txn_uuid'] ?? ('UUID-' . uniqid() . '-' . rand(100, 999)),
            'subtotal'         => (float)$raw['subtotal'],
            'item_discount'    => (float)($raw['item_discount'] ?? 0),
            'bill_discount'    => (float)($raw['bill_discount'] ?? 0),
            'discount_amount'  => (float)($raw['discount_amount'] ?? 0),
            'tax_amount'       => (float)($raw['tax_amount'] ?? 0),
            'loyalty_redeemed' => (float)($raw['loyalty_redeemed'] ?? 0),
            'total_amount'     => (float)$raw['total_amount'],
            'paid_amount'      => (float)$raw['paid_amount'],
            'change_amount'    => (float)($raw['change_amount'] ?? 0),
            'items'            => $raw['items'],
            'payments'         => $raw['payments'] ?? [],
            'note'             => $raw['note'] ?? null,
        ];

        $res = $this->salesService->commitSale($saleData);

        if ($res['success']) {
            return $this->jsonSuccess($res, 'Sale completed successfully!');
        } else {
            return $this->jsonError('Failed to commit sale. Please try again.');
        }
    }

    public function hold()
    {
        $raw = $this->request->getJSON(true);
        if (!$raw || empty($raw['items'])) {
            return $this->jsonError('No items to hold.');
        }

        $holdRef = $raw['hold_ref'] ?? ('Hold-' . date('Hi'));
        $this->db->table('tbl_held_sale')->insert([
            'store_id'    => $this->storeId,
            'register_id' => 1,
            'cashier_id'  => $this->userId,
            'customer_id' => !empty($raw['customer_id']) ? $raw['customer_id'] : null,
            'hold_ref'    => $holdRef,
            'cart_data'   => json_encode($raw),
            'status'      => 'held',
            'held_at'     => date('Y-m-d H:i:s'),
        ]);

        return $this->jsonSuccess(['hold_id' => $this->db->insertID()], 'Sale held successfully.');
    }

    public function holdsList()
    {
        $holds = $this->db->table('tbl_held_sale')
            ->where('store_id', $this->storeId)
            ->where('status', 'held')
            ->orderBy('id', 'DESC')
            ->get()->getResult();

        return $this->response->setJSON($holds);
    }

    public function resume($id)
    {
        $hold = $this->db->table('tbl_held_sale')->where('id', $id)->get()->getRow();
        if (!$hold) {
            return $this->jsonError('Held cart not found.');
        }

        // Mark resumed
        $this->db->table('tbl_held_sale')->where('id', $id)->update([
            'status'     => 'resumed',
            'resumed_at' => date('Y-m-d H:i:s')
        ]);

        return $this->response->setJSON(json_decode($hold->cart_data));
    }

    public function searchCustomer()
    {
        $q = $this->request->getGet('q');
        $res = $this->customerModel->searchCustomer($q, $this->businessId, $this->storeId);
        return $this->response->setJSON($res);
    }

    public function quickCreateCustomer()
    {
        $name = trim($this->request->getPost('name'));
        $phone = trim($this->request->getPost('phone'));

        if (empty($name) || empty($phone)) {
            return $this->jsonError('Name and Phone are required.');
        }

        $existing = $this->customerModel->where('business_id', $this->businessId)->where('phone', $phone)->first();
        if ($existing) {
            return $this->jsonSuccess($existing, 'Existing customer found.');
        }

        $newId = $this->customerModel->insert([
            'business_id' => $this->businessId,
            'name'        => $name,
            'phone'       => $phone,
            'is_member'   => 1,
            'is_active'   => 1,
        ]);

        return $this->jsonSuccess(['id' => $newId, 'name' => $name, 'phone' => $phone, 'loyalty_points' => 0], 'Customer created!');
    }

    public function receipt($id)
    {
        $sale = $this->saleModel->getSale($id);
        if (!$sale) {
            return 'Invoice not found';
        }
        return view('pos/receipt', ['sale' => $sale]);
    }

    public function salesList()
    {
        $this->global['pageTitle'] = 'EPOS | Invoices & Receipts';
        $filters = [
            'date_from' => $this->request->getGet('date_from'),
            'date_to'   => $this->request->getGet('date_to'),
        ];
        $data = [
            'sales'   => $this->saleModel->getAllSales($this->businessId, $this->storeId, $filters),
            'filters' => $filters,
        ];
        return $this->loadEposView('pos/sales_list', $data);
    }

    public function processReturn()
    {
        $raw = $this->request->getJSON(true);
        $saleId = (int)$raw['sale_id'];
        $items  = $raw['items'];
        $note   = $raw['note'] ?? 'POS Cash Refund';

        $res = $this->salesService->processSaleReturn($saleId, $items, $note, $this->userId);
        if ($res) {
            return $this->jsonSuccess($res, 'Return processed and refund recorded.');
        } else {
            return $this->jsonError('Failed to process return.');
        }
    }
}
