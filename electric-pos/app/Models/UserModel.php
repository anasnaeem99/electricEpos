<?php
namespace App\Models;
use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'tbl_user';
    protected $primaryKey = 'id';
    protected $allowedFields = ['business_id','role_id','username','full_name','email','phone','password','profile_pic','is_active'];

    public function getUserById($id)
    {
        return $this->db->table('tbl_user u')
            ->select('u.*, r.name as role_name, r.slug as role_slug')
            ->join('tbl_role r', 'r.id = u.role_id', 'left')
            ->where('u.id', $id)->where('u.is_active', 1)->get()->getRow();
    }

    public function getUserByUsername($username)
    {
        return $this->db->table('tbl_user u')
            ->select('u.*, r.name as role_name, r.slug as role_slug')
            ->join('tbl_role r', 'r.id = u.role_id', 'left')
            ->where('u.username', $username)->where('u.is_active', 1)->get()->getRow();
    }

    public function getAllUsers($businessId)
    {
        return $this->db->table('tbl_user u')
            ->select('u.id, u.username, u.full_name, u.email, u.phone, u.is_active, u.last_login, r.name as role_name')
            ->join('tbl_role r', 'r.id = u.role_id', 'left')
            ->where('u.business_id', $businessId)
            ->orderBy('u.full_name')->get()->getResult();
    }

    public function createUser($data)
    {
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        $this->insert($data);
        return $this->insertID();
    }

    public function updateUser($id, $data)
    {
        if (!empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        } else {
            unset($data['password']);
        }
        return $this->update($id, $data);
    }

    public function assignStoreAccess($userId, array $storeIds)
    {
        $this->db->table('tbl_user_store_access')->where('user_id', $userId)->delete();
        foreach ($storeIds as $sid) {
            $this->db->table('tbl_user_store_access')->insert(['user_id' => $userId, 'store_id' => $sid]);
        }
    }

    public function getUserStores($userId)
    {
        return $this->db->table('tbl_user_store_access usa')
            ->select('s.id, s.name, s.code, s.city')
            ->join('tbl_store s', 's.id = usa.store_id')
            ->where('usa.user_id', $userId)->get()->getResult();
    }
}
