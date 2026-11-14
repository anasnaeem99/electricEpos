<?php
namespace App\Models;

use CodeIgniter\Model;

class LoginModel extends Model
{
    protected $table = 'tbls_user';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'roleId', 'email', 'userName', 'password', 'profilepic', 'startDate', 'endDate', 'resumeDate', 'countryId',
        'wallpaper', 'status', 'userUniqueNo', 'pin', 'countryTimeZone', 'userVerify', 'groupEmail', 'isDeleted'
    ];
    
    // Constructor
    public function __construct()
    {
        parent::__construct();
        $this->db->query("SET time_zone='+05:00'");
    }

    // Login function
    public function loginMe($email, $password)
    {
        $builder = $this->db->table('tbls_user as BaseTbl');
        $builder->select('BaseTbl.id, BaseTbl.roleId, BaseTbl.email, BaseTbl.userName, BaseTbl.password, BaseTbl.profilepic, 
                          BaseTbl.startDate, BaseTbl.endDate, BaseTbl.resumeDate, Country.id as countryId, Country.countryName, 
                          Country.countryFlag, BaseTbl.wallpaper, BaseTbl.status, BaseTbl.userUniqueNo, Role.id as roleId, 
                          Role.department, Role.designation, BaseTbl.pin, Country.countryTimeZone, BaseTbl.userVerify, 
                          BaseTbl.groupEmail');
        $builder->join('tbls_role as Role', 'Role.id = BaseTbl.roleId', 'left');
        $builder->join('tbls_country as Country', 'Country.id = BaseTbl.countryId', 'left');
        $builder->where('BaseTbl.email', $email);
        $builder->where('BaseTbl.isDeleted', 0);

        $query = $builder->get();
        $user = $query->getResult();

        if (!empty($user)) {
            // Use PHP's built-in password verification method
            if (password_verify($password, $user[0]->password)) {
                return $user;
            } else {
                return [];
            }
        } else {
            return [];
        }
    }

    // Login function
    public function loginMe1($email, $password)
    {
        $builder = $this->db->table('tbls_user as BaseTbl');
        $builder->select('BaseTbl.id, BaseTbl.roleId, BaseTbl.email, BaseTbl.userName, BaseTbl.password, BaseTbl.profilepic, 
                          BaseTbl.startDate, BaseTbl.endDate, BaseTbl.resumeDate, Country.id as countryId, Country.countryName, 
                          Country.countryFlag, BaseTbl.wallpaper, BaseTbl.status, BaseTbl.userUniqueNo, Role.id as roleId, 
                          Role.department, Role.designation, BaseTbl.pin, Country.countryTimeZone, BaseTbl.userVerify, 
                          BaseTbl.groupEmail');
        $builder->join('tbls_role as Role', 'Role.id = BaseTbl.roleId', 'left');
        $builder->join('tbls_country as Country', 'Country.id = BaseTbl.countryId', 'left');
        $builder->where('BaseTbl.email', $email);
        $builder->where('BaseTbl.isDeleted', 0);

        $query = $builder->get();
        $user = $query->getResult();

        if (!empty($user)) {
            // Use PHP's built-in password verification method
            if (password_verify($password, $user[0]->password)) {
                return $user;
            } else {
                return [];
            }
        } else {
            return [];
        }
    }

    // Check email existence
    public function checkEmailExists($email)
    {
        $builder = $this->db->table('tbls_user as BaseTbl');
        $builder->select('*');
        $builder->where('BaseTbl.isDeleted', 0);
        $builder->where('BaseTbl.email', $email);

        $query = $builder->get();
        return $query->getResult();
    }

    // Reset password request
    public function resetPasswordUser($data)
    {
        $builder = $this->db->table('tbl_reset_password');
        return $builder->insert($data);
    }

    // Check activation details
    public function checkActivationDetails($email, $activation_id)
    {
        $builder = $this->db->table('tbl_reset_password');
        $builder->select('id');
        $builder->where('email', $email);
        $builder->where('activation_id', $activation_id);

        $query = $builder->get();
        return $query->getNumRows();
    }

    // Create password for user
    public function createPasswordUser($email, $password)
    {
        $builder = $this->db->table('tbls_user');
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $builder->where('email', $email);
        $builder->where('isDeleted', 0);
        $builder->update(['password' => $hashedPassword]);

        // Delete reset request
        $resetBuilder = $this->db->table('tbl_reset_password');
        $resetBuilder->where('email', $email);
        $resetBuilder->delete();
    }

    // Update online status
    public function onlineStatusUpdate($id, $data)
    {
        $builder = $this->db->table('tbls_user');
        $builder->where('id', $id);
        return $builder->update($data);
    }

    // Update seenBy field
    public function seenByUpdate($id, $data, $table)
    {
        $builder = $this->db->table($table);
        $builder->set('seenBy', 'CONCAT(seenBy, \',\', :data:)', false);
        $builder->where('id', $id);
        return $builder->update(['data' => $data]);
    }

    // Update inUse field
    public function inUseUpdate($id, $data, $table)
    {
        $builder = $this->db->table($table);
        $builder->where('id', $id);
        return $builder->update($data);
    }

    // Update alertSeenBy field
    public function alertSeenByUpdate($id, $data, $table)
    {
        $builder = $this->db->table($table);
        $builder->set('alertSeenBy', 'CONCAT(alertSeenBy, \',\', :data:)', false);
        $builder->where('id', $id);
        return $builder->update(['data' => $data]);
    }
    
    // Insert the AuditLog data
    public function auditLogSave($data)
    {
        // Use table method to select the table
        $builder = $this->db->table('tbls_auditlog');

        // Insert data into the audit log
        $builder->insert($data);

        // Return the last insert id
        return $this->db->insertID();
    }

    // Record Custom Query
    function recordAjaxCustomQuery($customQuery)
    {   
        //print_r($customQuery); exit();
        $this->db->query($customQuery);
        $error = $this->db->error();

        if ($error['code'] != 0) { // Check for errors
            log_message('error', 'Query failed: ' . $error['message']);
            return false; // Return false on failure
        }

        $theQuery = $this->db->getLastQuery();
        $builder = $this->db->table('tbls_auditlog');
        $builder->insert([
            'userId' => $this->userId,
            'dateTime' => date($this->dateTimeFullFormat),
            'ipAddress' => $_SERVER['REMOTE_ADDR'],
            'query' => $theQuery
        ]);

        return true; // Return true on success
    }


    // Record Delete Operation
    public function recordAjaxDelete($idColumn, $id, $table)
    {
        $builder = $this->db->table($table);
        $builder->where($idColumn, $id);
        $builder->delete();

        // Log the query in audit log
        $theQuery = $this->db->getLastQuery();
        $builder = $this->db->table($this->table);
        $builder->insert([
            'userId' => session()->get('userId'),
            'dateTime' => date('Y-m-d H:i:s'),
            'ipAddress' => $_SERVER['REMOTE_ADDR'],
            'query' => $theQuery,
            'queryData' => serialize([$idColumn => $id])
        ]);
    }

    // Record Save Operation
    public function recordAjaxSave($data, $table)
    {
        // Insert data into the main table
        $builder = $this->db->table($table);
        $builder->insert($data);
        $insertId = $this->db->insertID();

        // Log the query in the audit log
        $theQuery = $this->db->getLastQuery();
        
        // Ensure you have an audit table
        $auditTable = 'tbls_auditlog'; // Replace with your audit table name
        $auditBuilder = $this->db->table($auditTable);
        $auditBuilder->insert([
            'userId' => session()->get('userId'),
            'dateTime' => date('Y-m-d H:i:s'),
            'ipAddress' => $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0', // Ensure a fallback for IP
            'query' => (string) $theQuery, // Cast to string
            'queryData' => json_encode($data) // Use JSON instead of serialize
        ]);

        return $insertId;
    }

    // Record Update Operation
    function recordAjaxUpdate($idColumn, $id, $data, $table)
    {
        // Use the table method of CodeIgniter 4 query builder
        $builder = $this->db->table($table);

        // Perform the update operation
        $builder->where($idColumn, $id);
        $builder->update($data);

        // Get the number of affected rows (updated rows)
        $update_id = $this->db->affectedRows();

        // Get the last executed query for auditing purposes
        $theQuery = $this->db->getLastQuery();

        // Insert audit log into the 'tbls_auditlog' table
        $auditData = [
            'userId' => $this->userId,  // Ensure that $this->userId is available in the controller or model
            'dateTime' => date($this->dateTimeFullFormat),
            'ipAddress' => $_SERVER['REMOTE_ADDR'], // CodeIgniter helper for getting IP address
            'query' => $theQuery,
            'queryData' => serialize($data)
        ];
        $this->db->table('tbls_auditlog')->insert($auditData);

        return $update_id;
    }

    function recordAjaxExcelUpdate($idColumn, $id, $data, $table)
    {
        // Use the table method of CodeIgniter 4 query builder
        $builder = $this->db->table($table);

        // Support multiple where conditions (array) as well as single condition
        if (is_array($idColumn) && is_array($id)) {
            foreach ($idColumn as $index => $column) {
                $builder->where($column, $id[$index]);
            }
        } else {
            $builder->where($idColumn, $id);
        }

        // Perform the update operation
        $builder->update($data);
        // Get the number of affected rows (updated rows)
        $update_id = $this->db->affectedRows();
        // Get the last executed query for auditing purposes
        $theQuery = $this->db->getLastQuery();
        // Insert audit log into the 'tbls_auditlog' table
        $auditData = [
            'userId' => $this->userId,
            'dateTime' => date($this->dateTimeFullFormat),
            'ipAddress' => $_SERVER['REMOTE_ADDR'],
            'query' => $theQuery,
            'queryData' => serialize($data)
        ];
        $this->db->table('tbls_auditlog')->insert($auditData);
        return $update_id;
    }

    /**
     * Bulk update many rows in one/few queries (CI4 updateBatch).
     * Much faster than calling recordAjaxUpdate in a loop (e.g. gmpexecution detail tables).
     *
     * @param string|array $idColumn Constraint column name, or list of constraint columns
     * @param array        $rows     Rows to update; each row MUST include the constraint column(s)
     * @param string       $table    Target table
     * @return int Number of rows submitted for update (0 on failure/empty)
     */
    function recordAjaxBulkUpdate($idColumn, $rows, $table)
    {
        if (empty($rows) || !is_array($rows)) {
            return 0;
        }

        // Normalize to list of associative rows
        $rows = array_values($rows);
        $constraint = is_array($idColumn) ? $idColumn : $idColumn;

        $this->db->transStart();

        $builder = $this->db->table($table);
        // Chunk size 100 keeps packet size reasonable for large GMP scoring grids
        $builder->updateBatch($rows, $constraint, 100);

        $this->db->table('tbls_auditlog')->insert([
            'userId'    => session()->get('userId'),
            'dateTime'  => date('Y-m-d H:i:s'),
            'ipAddress' => $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0',
            'query'     => 'BULK UPDATE ' . $table . ' (' . count($rows) . ' rows) ON ' . (is_array($constraint) ? implode(',', $constraint) : $constraint),
            'queryData' => json_encode(['count' => count($rows), 'constraint' => $constraint]),
        ]);

        $this->db->transComplete();

        return $this->db->transStatus() === false ? 0 : count($rows);
    }

    /**
     * Bulk insert many rows in one/few queries (CI4 insertBatch).
     *
     * @param array  $rows  Rows to insert
     * @param string $table Target table
     * @return int Number of rows submitted for insert (0 on failure/empty)
     */
    function recordAjaxBulkSave($rows, $table)
    {
        if (empty($rows) || !is_array($rows)) {
            return 0;
        }

        $rows = array_values($rows);

        $this->db->transStart();

        $builder = $this->db->table($table);
        $builder->insertBatch($rows, null, 100);

        $this->db->table('tbls_auditlog')->insert([
            'userId'    => session()->get('userId'),
            'dateTime'  => date('Y-m-d H:i:s'),
            'ipAddress' => $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0',
            'query'     => 'BULK INSERT ' . $table . ' (' . count($rows) . ' rows)',
            'queryData' => json_encode(['count' => count($rows)]),
        ]);

        $this->db->transComplete();

        return $this->db->transStatus() === false ? 0 : count($rows);
    }

    // Get System Information
    public function systemInfoGet()
    {
        $builder = $this->db->table('tbls_system as BaseTbl');
        $builder->where('BaseTbl.isDeleted', 0);
        $builder->where('BaseTbl.status', 'Active');
        $query = $builder->get();
        
        return $query->getResult();
    }

    // Get Page Title
    public function pageTitleGet($data)
    {
        $builder = $this->db->table('tbls_page as BaseTbl');
        $builder->select('BaseTbl.id, BaseTbl.friendlyName, BaseTbl.url, BaseTbl.icon');
        $builder->where('BaseTbl.isDeleted', 0);
        $builder->where('BaseTbl.status', 'Active');
        $builder->where('BaseTbl.url', $data);
        $query = $builder->get();
        
        return $query->getResult();
    }

    // Get Role Page Header
    public function rolePageHeaderGet($roleId)
    {
        $builder = $this->db->table('tbls_pagedetail as BaseTbl');
        $builder->select('BaseTbl.roleId, Page.id, Page.url, Page.defaultAction, Page.friendlyName, 
                          Page.pageName, Page.icon, Page.parentId, Page.head, Page.myHead, 
                          (SELECT COUNT(tbls_page.id) 
                           FROM tbls_page 
                           LEFT JOIN tbls_pagedetail ON tbls_pagedetail.masterId = tbls_page.id 
                           WHERE tbls_page.parentId = Page.id 
                           AND tbls_pagedetail.roleId = '.$this->db->escape($roleId).'
                           AND tbls_page.isDeleted = "0" 
                           AND tbls_page.status = "Active" 
                           AND tbls_page.visible = "1" 
                           AND tbls_pagedetail.recordLookup = "1" 
                           AND tbls_page.sorting > Page.sorting) as children');
        
        $builder->join('tbls_page as Page', 'Page.id = BaseTbl.masterId', 'left');
        $builder->where('Page.isDeleted', 0);
        $builder->where('Page.status', 'Active');
        $builder->where('BaseTbl.roleId', $roleId);
        $builder->where('Page.visible', 1);
        $builder->where('BaseTbl.recordLookup', 1);
        $builder->orderBy('Page.sorting', 'asc');

        // Execute the query
        $query = $builder->get();
        
        // Return the result
        return $query->getResult();
    }

    // Get Role Pages
    public function rolePageGet($roleId)
    {
        $builder = $this->db->table('tbls_pagedetail as BaseTbl');
        $builder->select('BaseTbl.*, Page.id, Page.url, Page.friendlyName, Page.pageName, Page.icon');
        $builder->join('tbls_page as Page', 'Page.id = BaseTbl.masterId', 'left');
        $builder->where('Page.isDeleted', 0);
        $builder->where('Page.status', 'Active');
        $builder->where('BaseTbl.roleId', $roleId);
        $builder->orderBy('Page.sorting', 'asc');
        $query = $builder->get();
        
        return $query->getResult();
    }

    // Get All Pages
    public function pageGet()
    {
        $builder = $this->db->table('tbls_page as BaseTbl');
        $builder->where('BaseTbl.isDeleted', 0);
        $builder->where('BaseTbl.status', 'Active');
        $builder->where('BaseTbl.visibleinlist', 1);
        $builder->orderBy('BaseTbl.friendlyName');
        $query = $builder->get();
        
        return $query->getResult();
    }

    // Get Total Record Count
    public function totalRecordCountGet($table)
    {
        $builder = $this->db->table($table);
        $builder->select('count(*) as total');
        $query = $builder->get();
        
        return $query->getRow();
    }

    // Get All Roles
    public function roleGet()
    {
        $builder = $this->db->table('tbls_role as BaseTbl');
        $builder->where('BaseTbl.isDeleted', 0);
        $builder->where('BaseTbl.status', 'Active');
        $builder->whereNotIn('BaseTbl.id', [1,2,3]);
        $query = $builder->get();
        
        return $query->getResult();
    }

    // Get Users by Role
    public function userGet($roleId)
    {
        $builder = $this->db->table('tbls_user as BaseTbl');
        $builder->where('BaseTbl.isDeleted', 0);
        $builder->where('BaseTbl.status', 'Active');
        $builder->where('BaseTbl.roleId', $roleId);
        $query = $builder->get();
        
        return $query->getResult();
    }

    // Get All Countries
    public function countryGet()
    {
        $builder = $this->db->table('tbls_country as BaseTbl');
        $builder->where('BaseTbl.isDeleted', 0);
        $builder->where('BaseTbl.status', 'Active');
        $query = $builder->get();
        
        return $query->getResult();
    }

    // Get All Countries
    public function productTypeGet()
    {
        $builder = $this->db->table('tbl_producttype as BaseTbl');
        $builder->where('BaseTbl.isDeleted', 0);
        $builder->where('BaseTbl.status', 'Active');
        $query = $builder->get();
        
        return $query->getResult();
    }

    // Get All States
    public function stateGet()
    {
        $builder = $this->db->table('tbls_state as BaseTbl');
        $builder->where('BaseTbl.isDeleted', 0);
        $builder->where('BaseTbl.status', 'Active');
        $query = $builder->get();
        
        return $query->getResult();
    }

        // Get city data
    public function cityGet()
    {
        $builder = $this->db->table('tbls_city as BaseTbl');
        $builder->select('BaseTbl.*');
        $builder->where('BaseTbl.isDeleted', 0);
        $builder->where('BaseTbl.status', 'Active');
        $builder->orderBy('BaseTbl.cityname', 'asc');
        $query = $builder->get();

        return $query->getResult();
    }

    // Get alerts
    public function alertGet()
    {
        $builder = $this->db->table('tbls_alert as BaseTbl');
        $builder->select('BaseTbl.*');
        $builder->where('BaseTbl.isDeleted', 0);
        $builder->where('BaseTbl.status', 'Active');
        //$builder->like('BaseTbl.recepients', '0');
        $query = $builder->get();

        return $query->getResult();
    }

    // Register a new user
    public function registerMe($data)
    {   
        $dateTimeFullFormat = 'd-M-y H:i:s';
        $this->db->table('tbls_user')->insert($data);
        $insertId = $this->db->insertID();
     
        // Insert into audit log
        $query = $this->db->getLastQuery();
        $this->db->table('tbls_auditlog')->insert([
            'dateTime' => date($dateTimeFullFormat),
            'ipAddress' => $_SERVER['REMOTE_ADDR'],
            'query' => $query,
            'queryData' => serialize($data)
        ]);
     
        return $insertId;
    }

    // Fetch user data with optional search text
    public function user($table, $searchText = null)
    {
        $builder = $this->db->table($table . ' as BaseTbl');
        $builder->select('BaseTbl.*, Country.countryName, City.cityName, Role.department, Role.designation');

        if (!empty($searchText)) {
            $builder->groupStart();
            $builder->like('BaseTbl.userName', $searchText);
            $builder->orLike('BaseTbl.email', $searchText);
            $builder->orLike('BaseTbl.phone', $searchText);
            $builder->orLike('Country.countryName', $searchText);
            $builder->orLike('Role.department', $searchText);
            $builder->orLike('Role.designation', $searchText);
            $builder->orLike('BaseTbl.store', $searchText);
            $builder->orLike('BaseTbl.zone', $searchText);
            $builder->orLike('BaseTbl.section', $searchText);
            $builder->orLike('BaseTbl.regulatoryDepartment', $searchText);
            $builder->orLike('BaseTbl.status', $searchText);
            $builder->groupEnd();
        }

        $builder->join('tbls_country as Country', 'Country.id = BaseTbl.countryId', 'left');
        $builder->join('tbls_city as City', 'City.id = BaseTbl.cityId', 'left');
        $builder->join('tbls_role as Role', 'Role.id = BaseTbl.roleId', 'left');
        $builder->where('BaseTbl.isDeleted', 0);
        $builder->where('BaseTbl.id !=', 1);
        $builder->orderBy('BaseTbl.id', 'desc');
        $query = $builder->get();

        return $query->getResult();
    }

    // Edit user data
    public function userEdit($id, $table)
    {
        $builder = $this->db->table($table . ' as BaseTbl');
        $builder->select('BaseTbl.*');
        $builder->where('BaseTbl.isDeleted', 0);
        $builder->where('BaseTbl.id', $id);
        $query = $builder->get();

        return $query->getResult();
    }

    // Fetch permissions data
    public function permission($table, $searchText = null)
    {
        // Load the database service
        //$db = \Config\Database::connect();
        $builder = $this->db->table($table . ' as BaseTbl');
        $builder->select('BaseTbl.*');

        if (!empty($searchText)) {
            $builder->groupStart();
            $builder->like('BaseTbl.department', $searchText);
            $builder->orLike('BaseTbl.designation', $searchText);
            $builder->orLike('BaseTbl.status', $searchText);
            $builder->groupEnd();
        }

        $builder->where('BaseTbl.isDeleted', 0);
        $builder->where('BaseTbl.status', 'Active');
        $builder->whereNotIn('BaseTbl.id', [1, 2, 3, 4]);
        $builder->orderBy('BaseTbl.id', 'desc');
        $query = $builder->get();

        return $query->getResult();
    }

    // Edit permission data
    public function permissionEdit($id, $table)
    {   
        // Load the database service
        //$db = \Config\Database::connect();
        $builder = $this->db->table($table . ' as BaseTbl');
        $builder->select('BaseTbl.*');
        $builder->where('BaseTbl.isDeleted', 0);
        $builder->where('BaseTbl.status', 'Active');
        $builder->where('BaseTbl.id', $id);
        $query = $builder->get();

        return $query->getResult();
    }

    // Edit detailed permission data
    public function permissionDetailEdit($id, $table)
    {   
        $builder = $this->db->table($table . ' as BaseTbl');
        $builder->select('BaseTbl.*, Page.friendlyName');
        $builder->join('tbls_page as Page', 'Page.id = BaseTbl.masterId', 'left');
        $builder->where('BaseTbl.roleId', $id);
        $builder->orderBy('Page.friendlyName', 'asc');
        $query = $builder->get();

        return $query->getResult();
    }

    // Audit log function
    public function auditlog($table, $searchText = null)
    {
        $builder = $this->db->table($table . ' as BaseTbl');
        $builder->select('BaseTbl.*, User.userName');

        if (!empty($searchText)) {
            $builder->groupStart();
            $builder->like('BaseTbl.userName', $searchText);
            $builder->orLike('BaseTbl.dateTime', $searchText);
            $builder->orLike('BaseTbl.query', $searchText);
            $builder->groupEnd();
        }

        $builder->join('tbls_user as User', 'User.id = BaseTbl.userId', 'left');
        $builder->where('BaseTbl.isArchived', 0);
        $builder->limit(15);
        $builder->orderBy('BaseTbl.id', 'desc');
        $query = $builder->get();

        return $query->getResult();
    }

    // Edit audit log data
    public function auditlogEdit($id, $table)
    {
        $builder = $this->db->table($table . ' as BaseTbl');
        $builder->select('BaseTbl.*');
        $builder->where('BaseTbl.id', $id);
        $query = $builder->get();

        return $query->getResult();
    }

    // Fetch alert data
    public function alert($table, $searchText = null)
    {
        $builder = $this->db->table($table . ' as BaseTbl');
        $builder->select('BaseTbl.*');

        if (!empty($searchText)) {
            $builder->groupStart();
            $builder->like('BaseTbl.type', $searchText);
            $builder->orLike('BaseTbl.dateTime', $searchText);
            $builder->orLike('BaseTbl.alertName', $searchText);
            $builder->orLike('BaseTbl.duration', $searchText);
            $builder->orLike('BaseTbl.description', $searchText);
            $builder->orLike('BaseTbl.status', $searchText);
            $builder->groupEnd();
        }

        $builder->where('BaseTbl.isDeleted', 0);
        $builder->orderBy('BaseTbl.id', 'desc');
        $query = $builder->get();

        return $query->getResult();
    }

    // Edit alert data
    public function alertEdit($id, $table)
    {
        $builder = $this->db->table($table . ' as BaseTbl');
        $builder->select('BaseTbl.*');
        $builder->where('BaseTbl.isDeleted', 0);
        $builder->where('BaseTbl.id', $id);
        $query = $builder->get();

        return $query->getResult();
    }

    // Alerts data
    public function alerts($table, $searchText = null)
    {
        $builder = $this->db->table($table . ' as BaseTbl');
        $builder->select('BaseTbl.*');

        if (!empty($searchText)) {
            $builder->groupStart();
            $builder->like('BaseTbl.type', $searchText);
            $builder->orLike('BaseTbl.dateTime', $searchText);
            $builder->orLike('BaseTbl.alertName', $searchText);
            $builder->orLike('BaseTbl.duration', $searchText);
            $builder->orLike('BaseTbl.description', $searchText);
            $builder->orLike('BaseTbl.status', $searchText);
            $builder->groupEnd();
        }

        $builder->where('BaseTbl.isDeleted', 0);
        $builder->where('BaseTbl.status', 'Active');
        $builder->orderBy('BaseTbl.id', 'desc');
        $query = $builder->get();

        return $query->getResult();
    }

    // Helpdesk function (generalized for any table)
    public function helpdesk(string $table, string $searchText = null): array
    {
        $builder = $this->db->table("{$table} as BaseTbl");
        $builder->select('BaseTbl.*, Country.countryName as country, User.userName, User.email');
        
        if (!empty($searchText)) {
            $builder->groupStart();
            $builder->like('BaseTbl.dateTime', $searchText);
            $builder->orLike('BaseTbl.id', $searchText);
            $builder->orLike('BaseTbl.description', $searchText);
            $builder->orLike('BaseTbl.issueStatus', $searchText);
            $builder->orLike('BaseTbl.clientStatus', $searchText);
            $builder->groupEnd();
        }

        $builder->join('tbls_user as User', 'User.id = BaseTbl.userId', 'left');
        $builder->join('tbls_country as Country', 'Country.id = User.countryId', 'left');
        $builder->where('BaseTbl.isDeleted', 0);

        if ($this->roleId !== '1') {
            $builder->where('BaseTbl.createdby', $this->userId);
        }

        $builder->orderBy('BaseTbl.id', 'desc');
        $query = $builder->get();

        return $query->getResultArray();
    }

    // Edit Helpdesk function
    public function helpdeskEdit($id, $table)
    {
        $db = \Config\Database::connect(); // Connect to the database
        $builder = $db->table($table);     // Initialize query builder for the table

        $builder->select('*');
        $builder->where('isDeleted', 0);
        $builder->where('id', $id);

        $query = $builder->get();          // Execute the query
        return $query->getResult();        // Return the result as an array of objects
    }

    // User Guide function
    public function userguide(string $table, string $searchText = null): array
    {
        $builder = $this->db->table("{$table} as BaseTbl");
        $builder->select('BaseTbl.*');
        
        if (!empty($searchText)) {
            $builder->groupStart();
            $builder->like('BaseTbl.createddate', $searchText);
            $builder->orLike('BaseTbl.version', $searchText);
            $builder->orLike('BaseTbl.name', $searchText);
            $builder->groupEnd();
        }

        $builder->where('BaseTbl.isDeleted', 0);
        $builder->where('BaseTbl.status', 'Active');
        $builder->where('BaseTbl.roleId', $this->roleId);

        $builder->orderBy('BaseTbl.id', 'desc');
        $query = $builder->get();

        return $query->getResultArray();
    }

    // Get Users function
    public function users(): array
    {
        $builder = $this->db->table('tbls_user as BaseTbl');
        $builder->select('BaseTbl.*');
        $builder->where('BaseTbl.isDeleted', 0);
        $builder->where('BaseTbl.id !=', 1);  // Exclude admin (assuming admin id is 1)
        $query = $builder->get();
        
        return $query->getResultArray();
    }

    public function panelPoolUser($userId)
    {   
        $this->db->table('tbl_panelpool')->where('id', $userId)->delete();
        
        if ($this->db->affectedRows() > 0) {
            return true; // Successfully deleted
        } else {
            return false; // Deletion failed (or record not found)
        }
    }

    public function userDeleted($userId)
    {   
        $this->db->table('tbls_user')->where('id', $userId)->delete();
        
        if ($this->db->affectedRows() > 0) {
            return true; // Successfully deleted
        } else {
            return false; // Deletion failed (or record not found)
        }
    }


    // Get Inspections Under 3 Months function
    public function inspectionUnder3Months(int $id): array
    {
        $builder = $this->db->table('tbl_gdpinspection as BaseTbl');
        $builder->select('BaseTbl.*');
        $builder->where('BaseTbl.isDeleted', 0);
        $builder->where('BaseTbl.createdby', $id);
        $builder->where('STR_TO_DATE(BaseTbl.inspectionFromDate,"%d-%M-%y %H:%i") >= DATE_ADD(curdate(), INTERVAL -3 MONTH)', null, false);
        $query = $builder->get();
        
        return $query->getResultArray();
    }

    function reportTypeGet()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('tbls_reporttype as BaseTbl');
        
        $builder->select('BaseTbl.*');
        $builder->where('BaseTbl.isDeleted', 0);
        $builder->where('BaseTbl.status', 'Active');
        $builder->where('BaseTbl.visibleinlist', 1);
        // Uncomment the next line if you need to filter by department
        // $builder->where('BaseTbl.department', $this->department);
        
        $query = $builder->get();

        return $query->getResultArray();
    }


    // Report function
    function report(string $reportType, int $id = null, array $parameters = [], string $type = 'Main')
    {
        $db = \Config\Database::connect();

        if ($type === 'Main') {
            if ($reportType === 'All Users') {
                $builder = $db->table('tbls_users as BaseTbl');
                $builder->select('BaseTbl.*');
                $builder->where('BaseTbl.isDeleted', 0);

                if ($id) {
                    $builder->where('BaseTbl.id', $id);
                }

                if (!empty($parameters['fromDate'])) {
                    $fromDate = date('Y-m-d H:i:s', strtotime($parameters['fromDate']));
                    $builder->where('BaseTbl.dateTime >=', $fromDate);
                }

                if (!empty($parameters['toDate'])) {
                    $toDate = date('Y-m-d H:i:s', strtotime($parameters['toDate']));
                    $builder->where('BaseTbl.dateTime <=', $toDate);
                }

                $query = $builder->get();
                return $query->getResultArray();
            }

            if ($reportType === 'Monthly Summary Report') {
                $builder = $db->table('tbl_registration as BaseTbl');
                $builder->select('BaseTbl.*, Country.countryName, 
                                  (SELECT COUNT(id) FROM tbl_registration WHERE status = "In Process") as totalAtRegulatory, 
                                  (SELECT COUNT(id) FROM tbl_registration WHERE status = "At Agent") as totalAtAgent, 
                                  (SELECT COUNT(id) FROM tbl_registration WHERE status = "Applied") as totalApplied, 
                                  (SELECT COUNT(id) FROM tbl_registration WHERE status = "Approved") as totalRegistered');
                $builder->join('tbls_country as Country', 'Country.id = BaseTbl.countryId', 'left');
                $builder->where('BaseTbl.isDeleted', 0);
                $builder->where('BaseTbl.countryId !=', 167);

                if (!empty($parameters['fromDate'])) {
                    $builder->where('BaseTbl.bdRequestDate >=', $parameters['fromDate']);
                }

                if (!empty($parameters['toDate'])) {
                    $builder->where('BaseTbl.bdRequestDate <=', $parameters['toDate']);
                }

                if (!empty($parameters['manufacturerId'])) {
                    $builder->where('BaseTbl.manufacturerId', $parameters['manufacturerId']);
                }

                $builder->groupBy('Country.countryName');
                $builder->orderBy('Country.countryName', 'asc');
                $query = $builder->get();

                return $query->getResultArray();
            }
        }

        if ($type === 'Detail') {
            if ($reportType === 'Registration Certificate') {
                $builder = $db->table('tbl_registration as BaseTbl');
                $builder->select('BaseTbl.*');
                $builder->where('BaseTbl.isDeleted', 0);
                $builder->where('BaseTbl.id', $id);

                $query = $builder->get();
                return $query->getResultArray();
            }
        }

        return [];
    }

}
