<?php
namespace App\Controllers\Auth;

use CodeIgniter\Controller;
use App\Models\UserModel;

class AuthController extends Controller
{
    protected $session;
    protected $db;
    protected $userModel;

    public function __construct()
    {
        $this->session = session();
        $this->db = \Config\Database::connect();
        $this->userModel = new UserModel();
        helper(['url', 'form']);
    }

    public function loginForm()
    {
        if ($this->session->get('isLoggedIn')) {
            return redirect()->to(base_url('dashboard'));
        }
        return view('epos_login', [
            'error'   => $this->session->getFlashdata('error'),
            'success' => $this->session->getFlashdata('success'),
        ]);
    }

    public function doLogin()
    {
        $username = trim($this->request->getPost('username') ?? '');
        $password = $this->request->getPost('password') ?? '';

        if (empty($username) || empty($password)) {
            $this->session->setFlashdata('error', 'Username and Password are required.');
            return redirect()->to(base_url('login'));
        }

        $user = $this->userModel->getUserByUsername($username);

        // Demo fallback: If no users in database yet, create default admin user
        if (!$user) {
            $totalUsers = $this->db->table('tbl_user')->countAllResults();
            if ($totalUsers === 0 && ($username === 'admin' || $username === 'admin@epos.local') && $password === 'admin123') {
                $newUserId = $this->userModel->createUser([
                    'business_id' => 1,
                    'role_id'     => 1,
                    'username'    => 'admin',
                    'full_name'   => 'System Administrator',
                    'email'       => 'admin@epos.local',
                    'password'    => 'admin123',
                    'is_active'   => 1
                ]);
                $this->userModel->assignStoreAccess($newUserId, [1, 2]);
                $user = $this->userModel->getUserById($newUserId);
            }
        }

        if (!$user || !password_verify($password, $user->password)) {
            $this->session->setFlashdata('error', 'Invalid username or password.');
            return redirect()->to(base_url('login'));
        }

        if (!$user->is_active) {
            $this->session->setFlashdata('error', 'Your account has been deactivated. Contact Administrator.');
            return redirect()->to(base_url('login'));
        }

        // Get store access
        $stores = $this->userModel->getUserStores($user->id);
        $firstStore = $stores[0] ?? (object)['id' => 1, 'name' => 'Main Branch - Lahore'];

        // Set session
        $this->session->set([
            'isLoggedIn'   => true,
            'userId'       => $user->id,
            'userName'     => $user->full_name,
            'userEmail'    => $user->email,
            'username'     => $user->username,
            'roleId'       => (int)$user->role_id,
            'roleName'     => $user->role_name ?? 'Master Admin',
            'businessId'   => (int)$user->business_id,
            'storeId'      => (int)$firstStore->id,
            'storeName'    => $firstStore->name,
            'stores'       => $stores,
            'unlocked'     => 'yes',
        ]);

        // Log login
        $this->db->table('tbl_user_login_log')->insert([
            'user_id'    => $user->id,
            'ip_address' => $this->request->getIPAddress(),
            'user_agent' => substr($this->request->getUserAgent()->getAgentString(), 0, 250),
            'status'     => 'success',
        ]);

        // Update last login
        $this->db->table('tbl_user')->where('id', $user->id)->update(['last_login' => date('Y-m-d H:i:s')]);

        return redirect()->to(base_url('dashboard'));
    }

    public function switchStore($storeId)
    {
        $userId = $this->session->get('userId');
        $stores = $this->userModel->getUserStores($userId);
        
        $hasAccess = false;
        foreach ($stores as $s) {
            if ($s->id == $storeId) {
                $hasAccess = true;
                $this->session->set([
                    'storeId'   => (int)$s->id,
                    'storeName' => $s->name,
                ]);
                break;
            }
        }

        if (!$hasAccess && $this->session->get('roleId') == 1) {
            // Admin can switch to any store
            $st = $this->db->table('tbl_store')->where('id', $storeId)->get()->getRow();
            if ($st) {
                $this->session->set([
                    'storeId'   => (int)$st->id,
                    'storeName' => $st->name,
                ]);
            }
        }

        return redirect()->back();
    }

    public function logout()
    {
        $this->session->destroy();
        return redirect()->to(base_url('login'));
    }
}
