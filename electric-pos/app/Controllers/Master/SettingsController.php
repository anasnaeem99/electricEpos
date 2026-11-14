<?php
namespace App\Controllers\Master;

use App\Controllers\EposController;
use App\Models\StoreModel;

class SettingsController extends EposController
{
    protected $storeModel;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->storeModel = new StoreModel();
    }

    public function index()
    {
        $this->global['pageTitle'] = 'EPOS | System & Store Settings';

        $business = $this->db->table('tbl_business')->where('id', $this->businessId)->get()->getRow();
        $store    = $this->db->table('tbl_store')->where('id', $this->storeId)->get()->getRow();
        $paymentMethods = $this->db->table('tbl_payment_method')->where('business_id', $this->businessId)->get()->getResult();
        $taxes = $this->db->table('tbl_tax_rate')->where('business_id', $this->businessId)->get()->getResult();

        $data = [
            'business'       => $business,
            'store'          => $store,
            'paymentMethods' => $paymentMethods,
            'taxes'          => $taxes,
            'stores'         => $this->storeModel->getAllStores($this->businessId),
        ];

        return $this->loadEposView('master/settings', $data);
    }

    public function saveGeneral()
    {
        $data = [
            'name'     => trim($this->request->getPost('name')),
            'currency' => trim($this->request->getPost('currency') ?: 'PKR'),
            'timezone' => $this->request->getPost('timezone') ?: 'Asia/Karachi',
            'phone'    => $this->request->getPost('phone'),
            'email'    => $this->request->getPost('email'),
            'address'  => $this->request->getPost('address'),
        ];

        $this->db->table('tbl_business')->where('id', $this->businessId)->update($data);
        $this->session->setFlashdata('success', 'General business settings updated.');
        return redirect()->back();
    }

    public function saveStore()
    {
        $storeId = (int)$this->request->getPost('store_id');
        $data = [
            'name'           => trim($this->request->getPost('name')),
            'code'           => trim($this->request->getPost('code')),
            'phone'          => $this->request->getPost('phone'),
            'address'        => $this->request->getPost('address'),
            'invoice_header' => $this->request->getPost('invoice_header'),
        ];

        $this->db->table('tbl_store')->where('id', $storeId)->update($data);
        $this->session->setFlashdata('success', 'Store branch updated.');
        return redirect()->back();
    }
}
