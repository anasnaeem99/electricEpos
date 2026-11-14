<?php
namespace App\Controllers\Loyalty;

use App\Controllers\EposController;
use App\Services\LoyaltyService;
use App\Models\LoyaltyModel;

class LoyaltyController extends EposController
{
    protected $loyaltyService;
    protected $loyaltyModel;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->loyaltyService = new LoyaltyService();
        $this->loyaltyModel   = new LoyaltyModel();
    }

    public function index()
    {
        $this->global['pageTitle'] = 'EPOS | Store Loyalty Program';
        $stats = $this->loyaltyModel->getLoyaltyStats($this->businessId, $this->storeId);
        $recentAccounts = $this->loyaltyModel->getAllAccounts($this->businessId, $this->storeId);

        $data = [
            'stats'    => $stats,
            'accounts' => array_slice($recentAccounts, 0, 10),
        ];

        return $this->loadEposView('loyalty/dashboard', $data);
    }

    public function customers()
    {
        $this->global['pageTitle'] = 'EPOS | Customer Loyalty Accounts';
        $data = [
            'accounts' => $this->loyaltyModel->getAllAccounts($this->businessId, $this->storeId),
        ];
        return $this->loadEposView('loyalty/customers', $data);
    }

    public function customerDetail($id)
    {
        $acc = $this->loyaltyModel->getAccountDetails($id);
        if (!$acc) {
            $this->session->setFlashdata('error', 'Loyalty account not found.');
            return redirect()->to(base_url('loyalty/customers'));
        }

        $this->global['pageTitle'] = 'EPOS | Loyalty: ' . esc($acc->customer_name);
        return $this->loadEposView('loyalty/customer_detail', ['acc' => $acc]);
    }

    public function adjust()
    {
        $accountId = (int)$this->request->getPost('account_id');
        $points    = (float)$this->request->getPost('points');
        $reason    = trim($this->request->getPost('reason') ?? 'Manual Point Correction');

        if (!$accountId || $points == 0) {
            $this->session->setFlashdata('error', 'Valid Account and Points delta required.');
            return redirect()->back();
        }

        $res = $this->loyaltyService->adjustPoints($accountId, $points, $reason, $this->userId);
        if ($res) {
            $this->session->setFlashdata('success', 'Points adjusted successfully. Logged in audit trail.');
        } else {
            $this->session->setFlashdata('error', 'Failed to adjust points.');
        }

        return redirect()->back();
    }

    public function programSettings()
    {
        if ($this->request->getMethod() === 'post') {
            $data = [
                'name'             => trim($this->request->getPost('name')),
                'earn_rate'        => (float)$this->request->getPost('earn_rate'),
                'point_value'      => (float)$this->request->getPost('point_value'),
                'min_spend'        => (float)$this->request->getPost('min_spend'),
                'credit_delay_hrs' => (int)$this->request->getPost('credit_delay_hrs'),
                'expiry_months'    => (int)$this->request->getPost('expiry_months'),
            ];

            $this->db->table('tbl_loyalty_program')->where('id', 1)->update($data);
            $this->session->setFlashdata('success', 'Loyalty program settings updated.');
            return redirect()->back();
        }

        $program = $this->db->table('tbl_loyalty_program')->where('id', 1)->get()->getRow();
        $this->global['pageTitle'] = 'EPOS | Loyalty Program Rules';
        return $this->loadEposView('loyalty/program_settings', ['program' => $program]);
    }

    public function runCreditJob()
    {
        $credited = $this->loyaltyService->creditPendingPoints();
        $this->session->setFlashdata('success', "Loyalty Credit Job Processed: {$credited} lots matured and credited to available balance.");
        return redirect()->back();
    }

    public function runExpiryJob()
    {
        $expired = $this->loyaltyService->expirePoints();
        $this->session->setFlashdata('success', "Loyalty Expiry Job Processed: {$expired} expired lots archived.");
        return redirect()->back();
    }
}
