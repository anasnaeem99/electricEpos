<?php
namespace App\Controllers\Master;

use App\Controllers\EposController;
use App\Models\UserModel;
use App\Models\StoreModel;

class UserController extends EposController
{
    protected $userModel;
    protected $storeModel;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->userModel  = new UserModel();
        $this->storeModel = new StoreModel();
    }

    public function index()
    {
        $this->global['pageTitle'] = 'EPOS | User Management';
        $data = [
            'users' => $this->userModel->getAllUsers($this->businessId),
        ];
        return $this->loadEposView('master/users_list', $data);
    }

    public function add()
    {
        if ($this->request->getMethod() === 'post') {
            $username = trim($this->request->getPost('username'));
            $password = $this->request->getPost('password');

            $existing = $this->userModel->getUserByUsername($username);
            if ($existing) {
                $this->session->setFlashdata('error', 'Username already taken.');
                return redirect()->back()->withInput();
            }

            $userId = $this->userModel->createUser([
                'business_id' => $this->businessId,
                'role_id'     => (int)$this->request->getPost('role_id'),
                'username'    => $username,
                'full_name'   => trim($this->request->getPost('full_name')),
                'email'       => $this->request->getPost('email'),
                'phone'       => $this->request->getPost('phone'),
                'password'    => $password,
                'is_active'   => 1,
            ]);

            $storeIds = $this->request->getPost('store_ids') ?: [$this->storeId];
            $this->userModel->assignStoreAccess($userId, $storeIds);

            $this->session->setFlashdata('success', 'User created successfully!');
            return redirect()->to(base_url('master/users'));
        }

        $this->global['pageTitle'] = 'EPOS | Add System User';
        $data = [
            'roles'  => $this->db->table('tbl_role')->where('is_active', 1)->get()->getResult(),
            'stores' => $this->storeModel->getAllStores($this->businessId),
        ];
        return $this->loadEposView('master/user_form', $data);
    }

    public function edit($id)
    {
        $user = $this->userModel->getUserById($id);
        if (!$user) {
            $this->session->setFlashdata('error', 'User not found.');
            return redirect()->to(base_url('master/users'));
        }

        if ($this->request->getMethod() === 'post') {
            $data = [
                'role_id'   => (int)$this->request->getPost('role_id'),
                'full_name' => trim($this->request->getPost('full_name')),
                'email'     => $this->request->getPost('email'),
                'phone'     => $this->request->getPost('phone'),
                'is_active' => $this->request->getPost('is_active') ? 1 : 0,
            ];

            if ($this->request->getPost('password')) {
                $data['password'] = $this->request->getPost('password');
            }

            $this->userModel->updateUser($id, $data);

            $storeIds = $this->request->getPost('store_ids');
            if (is_array($storeIds)) {
                $this->userModel->assignStoreAccess($id, $storeIds);
            }

            $this->session->setFlashdata('success', 'User updated successfully!');
            return redirect()->to(base_url('master/users'));
        }

        $this->global['pageTitle'] = 'EPOS | Edit User: ' . esc($user->full_name);
        $userStores = $this->userModel->getUserStores($id);
        $assignedStoreIds = array_column($userStores, 'id');

        $data = [
            'user'             => $user,
            'roles'            => $this->db->table('tbl_role')->where('is_active', 1)->get()->getResult(),
            'stores'           => $this->storeModel->getAllStores($this->businessId),
            'assignedStoreIds' => $assignedStoreIds,
        ];
        return $this->loadEposView('master/user_form', $data);
    }
}
