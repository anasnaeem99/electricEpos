<?php
namespace App\Controllers\Inventory;

use App\Controllers\EposController;
use App\Models\SupplierModel;

class SupplierController extends EposController
{
    protected $supplierModel;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->supplierModel = new SupplierModel();
    }

    public function index()
    {
        $this->global['pageTitle'] = 'EPOS | Suppliers';
        $data = [
            'suppliers' => $this->supplierModel->getAllSuppliers($this->businessId),
        ];
        return $this->loadEposView('purchase/suppliers_list', $data);
    }

    public function add()
    {
        if ($this->request->getMethod() === 'post') {
            $data = [
                'business_id'     => $this->businessId,
                'name'            => trim($this->request->getPost('name')),
                'code'            => trim($this->request->getPost('code') ?: 'SUP-'.strtoupper(substr(uniqid(), -5))),
                'contact_person'  => $this->request->getPost('contact_person'),
                'phone'           => $this->request->getPost('phone'),
                'email'           => $this->request->getPost('email'),
                'ntn'             => $this->request->getPost('ntn'),
                'payment_terms'   => $this->request->getPost('payment_terms'),
                'opening_balance' => (float)($this->request->getPost('opening_balance') ?: 0),
                'is_active'       => 1
            ];

            $this->supplierModel->insert($data);
            $this->session->setFlashdata('success', 'Supplier created successfully!');
            return redirect()->to(base_url('purchase/suppliers'));
        }

        $this->global['pageTitle'] = 'EPOS | Add Supplier';
        return $this->loadEposView('purchase/supplier_form');
    }

    public function edit($id)
    {
        $supplier = $this->supplierModel->find($id);
        if (!$supplier) {
            $this->session->setFlashdata('error', 'Supplier not found.');
            return redirect()->to(base_url('purchase/suppliers'));
        }

        if ($this->request->getMethod() === 'post') {
            $data = [
                'name'           => trim($this->request->getPost('name')),
                'code'           => trim($this->request->getPost('code')),
                'contact_person' => $this->request->getPost('contact_person'),
                'phone'          => $this->request->getPost('phone'),
                'email'          => $this->request->getPost('email'),
                'ntn'            => $this->request->getPost('ntn'),
                'payment_terms'  => $this->request->getPost('payment_terms'),
            ];

            $this->supplierModel->update($id, $data);
            $this->session->setFlashdata('success', 'Supplier updated successfully!');
            return redirect()->to(base_url('purchase/suppliers'));
        }

        $this->global['pageTitle'] = 'EPOS | Edit Supplier';
        return $this->loadEposView('purchase/supplier_form', ['supplier' => $supplier]);
    }

    public function view($id)
    {
        $supplier = $this->supplierModel->getSupplierById($id);
        if (!$supplier) {
            $this->session->setFlashdata('error', 'Supplier not found.');
            return redirect()->to(base_url('purchase/suppliers'));
        }

        $this->global['pageTitle'] = 'EPOS | Supplier Ledger: ' . esc($supplier->name);
        $data = [
            'supplier' => $supplier,
            'ledger'   => $this->supplierModel->getLedger($id),
            'payments' => $this->db->table('tbl_supplier_payment')->where('supplier_id', $id)->orderBy('id', 'DESC')->get()->getResult(),
        ];
        return $this->loadEposView('purchase/supplier_view', $data);
    }

    public function addPayment()
    {
        $supplierId = (int)$this->request->getPost('supplier_id');
        $amount = (float)$this->request->getPost('amount');

        if ($supplierId <= 0 || $amount <= 0) {
            return $this->jsonError('Valid Supplier and Payment Amount are required.');
        }

        $this->supplierModel->recordPayment([
            'business_id'  => $this->businessId,
            'supplier_id'  => $supplierId,
            'amount'       => $amount,
            'payment_date' => $this->request->getPost('payment_date') ?: date('Y-m-d'),
            'method'       => $this->request->getPost('method') ?: 'cash',
            'reference'    => $this->request->getPost('reference'),
            'note'         => $this->request->getPost('note'),
            'created_by'   => $this->userId,
        ]);

        $this->session->setFlashdata('success', 'Payment recorded and ledger updated.');
        return redirect()->back();
    }
}
