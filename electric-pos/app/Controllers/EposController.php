<?php
namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class EposController extends Controller
{
    protected $request;
    protected $helpers = ['url', 'form', 'custom'];
    protected $session;
    protected $db;

    protected $userId;
    protected $userName;
    protected $userEmail;
    protected $roleId;
    protected $roleName;
    protected $businessId;
    protected $storeId;
    protected $storeName;
    protected $global = [];

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $this->session = session();
        $this->db = \Config\Database::connect();

        $this->checkAuth();

        $this->userId     = $this->session->get('userId');
        $this->userName   = $this->session->get('userName') ?? 'Admin';
        $this->userEmail  = $this->session->get('userEmail') ?? 'admin@epos.local';
        $this->roleId     = (int)($this->session->get('roleId') ?? 1);
        $this->roleName   = $this->session->get('roleName') ?? 'Master Admin';
        $this->businessId = (int)($this->session->get('businessId') ?? 1);
        $this->storeId    = (int)($this->session->get('storeId') ?? 1);
        $this->storeName  = $this->session->get('storeName') ?? 'Main Branch - Lahore';

        $this->global = [
            'pageTitle'   => 'EPOS | Multi-Store Retail',
            'userId'      => $this->userId,
            'userName'    => $this->userName,
            'userEmail'   => $this->userEmail,
            'roleId'      => $this->roleId,
            'roleName'    => $this->roleName,
            'businessId'  => $this->businessId,
            'storeId'     => $this->storeId,
            'storeName'   => $this->storeName,
        ];
    }

    protected function checkAuth()
    {
        if (!$this->session->get('isLoggedIn')) {
            // For AJAX / JSON requests
            if ($this->request->isAJAX() || strpos($this->request->getHeaderLine('accept'), 'application/json') !== false) {
                header('Content-Type: application/json', true, 401);
                echo json_encode(['status' => 'error', 'message' => 'Session expired. Please login again.']);
                exit;
            }
            header('Location: ' . base_url('login'));
            exit;
        }
    }

    protected function loadEposView($view, $data = [])
    {
        $data = array_merge($this->global, $data);
        return view('includes/epos_header', $data)
             . view($view, $data)
             . view('includes/epos_footer', $data);
    }

    protected function checkPermission($action)
    {
        // Master Admin has full access
        if ($this->roleId == 1) {
            return true;
        }

        $builder = $this->db->table('tbl_role_permission rp');
        $builder->join('tbl_permission p', 'p.id = rp.permission_id');
        $builder->where('rp.role_id', $this->roleId);
        $builder->where('p.action', $action);
        return $builder->countAllResults() > 0;
    }

    protected function accessDenied()
    {
        if ($this->request->isAJAX()) {
            return $this->response->setStatusCode(403)
                ->setJSON(['status' => 'error', 'message' => 'Permission denied.']);
        }
        return $this->response->setStatusCode(403)
            ->setBody('<h3>403 Access Denied: You do not have permission for this action.</h3><a href="' . base_url('dashboard') . '">Back to Dashboard</a>');
    }

    protected function jsonSuccess($data = [], $message = 'Success')
    {
        return $this->response->setJSON([
            'status'  => 'success',
            'message' => $message,
            'data'    => $data
        ]);
    }

    protected function jsonError($message = 'Error', $code = 400, $errors = [])
    {
        return $this->response->setStatusCode($code)->setJSON([
            'status'  => 'error',
            'message' => $message,
            'errors'  => $errors
        ]);
    }
}
