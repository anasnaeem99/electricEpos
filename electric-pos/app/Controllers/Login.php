<?php
namespace App\Controllers;

use App\Models\LoginModel; // Ensure the LoginModel exists
use App\Models\MyModel;
use CodeIgniter\Controller;
use App\Helpers\custom_helper;

// This controller is used to help log in and log out the system securely.
class Login extends Controller
{
    // public function __construct() is used to initialize the controller, setting up necessary configurations or loading models, libraries, and helpers before any other methods are called.
    public function __construct()
    {   
        $this->loginModel = new LoginModel(); // Initialize the LoginModel
        $this->myModel = new MyModel(); // Initialize the MyModel
        $this->session = session(); // Initialize the Session
        if (!$this->session->has('myLanguage')) {
            $this->session->set('myLanguage', 'english');
        }
        $this->db = \Config\Database::connect(); // Initialize the Database file
        $this->db->query("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))");
        helper('custom');
        $isLoggedIn = $this->session->get('isLoggedIn');

        if(isset($isLoggedIn) || $isLoggedIn == TRUE)
        {  
            $email = $this->session->get('userEmail');
            $password = $this->session->get('plainPassword');
            
            $result = $this->loginModel->loginMe1($email, $password);
            
            if(count($result) > 0)
            {
                foreach ($result as $res)
                {
                    $this->userId = $res->id;
                    $this->userEmail = $res->email;
                    $this->userName = $res->userName;
                    $this->profilepic = $res->profilepic;
                    $this->startDate = $res->startDate;
                    $this->endDate = $res->endDate;
                    $this->resumeDate = $res->resumeDate;
                    $this->countryId = $res->countryId;
                    $this->countryName = $res->countryName;
                    $this->countryFlag = $res->countryFlag;
                    $this->wallpaper = $res->wallpaper;
                    $this->status = $res->status;
                    $this->userUniqueNo = $res->userUniqueNo;
                    $this->companyUniqueNo = 'RBI';
                    $this->roleId = $res->roleId;
                    $this->department = $res->department;
                    $this->designation = $res->designation;
                    $this->userVerify = $res->userVerify;
                    $this->groupEmail = $res->groupEmail;
                    $this->pin = $res->pin;
                    $this->companyName = 'RBI';
                    $this->companyNick = 'RBI';
                    $this->companyAddress = 'Risk Based Inspection';
                    $this->companyProject = 'RBI';
                    $this->companyTheme = 'navbar-dark';
                    $this->dateFormat = 'd-M-y';
                    $this->dateTimeFormat = 'd-M-y H:i';
                    $this->dateTimeFullFormat = 'd-M-y H:i:s';
                }
            }
        }
    }
    // The end contructer function..
    
    // Index use for login in system
    public function index()
    {
       return $this->isLoggedIn();
    }
    
    // isLoggedIn use check login security
    public function isLoggedIn()
    {
        $isLoggedIn = $this->session->get('isLoggedIn');
        $unlocked = $this->session->get('unlocked');
        
        if(!isset($isLoggedIn) || $isLoggedIn != TRUE)
        {   
            $data['loginModel'] = $this->loginModel;
            $data['myModel'] = $this->myModel;
            $data['session'] = $this->session;
            return view('login', $data);
        }
        else
        {
            if($unlocked == 'yes'){
                return redirect()->to('dashboard');
            }
            if($unlocked == 'no'){
                return $this->lockscreen();
            }
        }
    }
    ///// ..... The End  isLoggedIn() Function ..... /////
    
    // isLoggedIn1 use check login security
    public function isLoggedIn1()
    {
        $email = $this->session->get('userEmail');
        $password = $this->session->get('plainPassword');
        
        $result = $this->loginModel->loginMe1($email, $password);
        
        if(count($result) > 0)
        {
            foreach ($result as $res)
            {
                $this->userId = $res->id;
                $this->userEmail = $res->email;
                $this->userName = $res->userName;
                $this->profilepic = $res->profilepic;
                $this->startDate = $res->startDate;
                $this->endDate = $res->endDate;
                $this->resumeDate = $res->resumeDate;
                $this->countryId = $res->countryId;
                $this->countryName = $res->countryName;
                $this->countryFlag = $res->countryFlag;
                $this->wallpaper = $res->wallpaper;
                $this->status = $res->status;
                $this->userUniqueNo = $res->userUniqueNo;
                $this->companyUniqueNo = 'RBI';
                $this->roleId = $res->roleId;
                $this->department = $res->department;
                $this->designation = $res->designation;
                $this->userVerify = $res->userVerify;
                $this->groupEmail = $res->groupEmail;
                $this->pin = $res->pin;
                $this->companyName = 'RBI';
                $this->companyNick = 'RBI';
                $this->companyAddress = 'Risk Based Inspection';
                $this->companyProject = 'RBI';
                $this->companyTheme = 'navbar-dark';
                $this->dateFormat = 'd-M-y';
                $this->dateTimeFormat = 'd-M-y H:i';
                $this->dateTimeFullFormat = 'd-M-y H:i:s';
            }
        }

        $isLoggedIn = $this->session->get('isLoggedIn');
        $unlocked = $this->session->get('unlocked');
        
        if(!isset($isLoggedIn) || $isLoggedIn != TRUE)
        {
            return redirect()->to('login');
        }
        if($unlocked == 'no' && $this->status == 'Active')
        {
            return redirect()->to('loginMe');
        }
        if($this->status == 'Inactive')
        {
            return redirect()->to('accessDenied');
        }
    }
    ///// ..... The End  isLoggedIn1() Function ..... /////
    
    // The lockMe feature is used to lock the system.
    public function lockMe()
    {   
        if(session()->has('userId')){
        $unlocked = $this->session->get('unlocked');
        $data['session'] = $this->session;
        $data['wallpaper'] = $this->wallpaper; 
        $data['userName'] = $this->userName;
        $data['companyProject'] = $this->companyProject;
        $data['companyName'] = $this->companyName;
        $data['profilepic'] = $this->profilepic;
        $data['companyAddress'] = $this->companyAddress;
        
        if($unlocked == 'yes')
        {
            $this->session->set(array('unlocked' => 'no'));
            $this->loginModel->auditLogSave(['userId' => $this->session->get('userId'), 'lockTime' => date($this->dateTimeFullFormat), 'ipAddress'=>$_SERVER['REMOTE_ADDR']]);
        }
        
            return view('lockscreen', $data);
        }else{
        $this->session->destroy();
        return redirect()->to(base_url());
        }
    }
    ///// ..... The End  lockMe() Function ..... /////
    
    // The unlockMe feature is used to log in to the system.
    public function unlockMe()
    {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $result = '';
        if(verifyHashedPassword($password, $this->session->get('password'))){
            $result = TRUE;
        }
        if($result == TRUE && $email == $this->session->get('userEmail')){
        $this->session->set(array('unlocked' => 'yes', 'welcomeMessageSound' => TRUE));
        $this->loginModel->auditLogSave(['userId' => $this->session->get('userId'), 'unlockTime' => date($this->dateTimeFullFormat), 'ipAddress'=>$_SERVER['REMOTE_ADDR']]);

        return redirect()->to('dashboard');
        }else{
        $this->session->destroy();
        return redirect()->to('login');
        }
    }
    ///// ..... The End  unlockMe() Function ..... /////
    
    // The loginMe feature is used to check the system's authentication.
    public function loginMe()
    {
        $isLoggedIn = $this->session->get('isLoggedIn');
        if(!(!isset($isLoggedIn) || $isLoggedIn != TRUE))
        {
            return $this->index();
        }
        else
        {
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');

            $this->session->set(['myEmail'=>$email, 'myPassword'=>$password]);

            $result = $this->loginModel->checkEmailExists($email);
            if(count($result) === 0){
                $this->session->setFlashdata('error', 'Account associated with this email does not appear to be registered here.');
                //redirect();
                //redirect('login');
                //echo "<script>alert('Account associated with this email does not appear to be registered here.');</script>";
                $data['loginModel'] = $this->loginModel;
                $data['myModel'] = $this->myModel;
                $data['session'] = $this->session;
                return view('login', $data);
            }
            if(count($result) > 0 && $result[0]->status == 'Inactive'){
                $this->session->setFlashdata('error', 'Your account is temporarily Inactive. Please Contact Admin for assistance.');
                $data['loginModel'] = $this->loginModel;
                $data['myModel'] = $this->myModel;
                $data['session'] = $this->session;
                return view('login', $data);
            }
            
            $result = $this->loginModel->loginMe($email, $password);
            
            if(count($result) > 0)
            {
                foreach ($result as $res)
                {
                    $sessionArray = array('userId'=>$res->id, 'userEmail'=>$res->email, 'userName'=>$res->userName, 'password'=>$res->password, 'plainPassword'=>$password, 'isLoggedIn' => TRUE, 'welcomeMessageSound'=> TRUE, 'unlocked'=> 'yes');
                    
                    if(!empty($res->endDate)){
                        if($res->endDate <= date('Y-m-d') && $res->roleId <> 1){
                            $this->session->setFlashdata('error', 'Your account is expired. Please Contac Super Admin for assistance.');
                            $data['loginModel'] = $this->loginModel;
                            $data['myModel'] = $this->myModel;
                            $data['session'] = $this->session;
                            return view('login', $data);
                        }
                    }
                                    
                    $this->session->set($sessionArray);
                    $this->loginModel->onlineStatusUpdate($this->session->get('userId'), array('isOnline' => 1));
                    $this->loginModel->auditLogSave(['userId' => $this->session->get('userId'), 'loginTime' => date('d-M-y H:i:s'), 'ipAddress'=>$_SERVER['REMOTE_ADDR']]);

                    sleep(1);
                    //if($res->companyEmail == 'dra.gov.pk' && $res->id <> 1 && $res->roleId <> 35){
                    
                    if($res->userVerify == 0){
                        return redirect()->to('profile/edit');
                    }
                    else{
                        return redirect()->to('dashboard');
                    }
                }
            }
            else
            {
                $this->session->setFlashdata('error', 'Email or password mismatch');
                $data['loginModel'] = $this->loginModel;
                $data['myModel'] = $this->myModel;
                $data['session'] = $this->session;
                return view('login', $data);
            }
        }
    }
    ///// ..... The End  loginMe() Function ..... /////
    
    // The logout() function is used to log out of the system.
    public function logout()
    {
        $isLoggedIn = $this->session->get('isLoggedIn');
        
        if(!isset($isLoggedIn) || $isLoggedIn != TRUE)
        {
            return redirect()->to(base_url());
        }
        
        $unlocked = $this->session->get('unlocked');
        
        if($unlocked == 'no')
        {
            return redirect();
        }
        if($unlocked == 'yes')
        {
            $this->loginModel->onlineStatusUpdate($this->session->get('userId'), array('isOnline' => 0));
            $this->loginModel->auditLogSave(['userId' => $this->session->get('userId'), 'logoutTime' => date($this->dateTimeFullFormat), 'ipAddress'=>$_SERVER['REMOTE_ADDR']]);
            $this->session->destroy();
            sleep(1);
            return redirect()->to(base_url());
        }
    }
    ///// ..... The End Logout() Function ..... /////
    
    // The forgotPassword() function is used when someone forgets their password for any reason. This function helps them change their password, and a link to this function is available on the login page.
    public function forgotPassword()
    {
        $data['session'] = $this->session;
        return view('forgotPassword', $data); // Load the forgot password view
    }
    ///// ..... The End forgotPassword() Function ..... /////
    
    // The register() function is used for anyone registering in the system. This function helps save user data in the database.
    public function register()
    {
        $loginModel = new LoginModel();
        $data['session'] = $this->session;
        $data['country'] = $loginModel->countryGet();
        $data['productType'] = $loginModel->productTypeGet();
        // $data['city'] = $loginModel->cityGet(); // Uncomment if needed
        return view('register', $data); // Load the register view
    }
    ///// ..... The End register() Function ..... /////
    
    // The registerCaptcha() function is used to enhance CAPTCHA security in our system.
    public function registerCaptcha()
    {
        return view('phptextClass');
    }
    ///// ..... The End registerCaptcha() Function ..... /////

    // registerOutputPreview() matches registration form values against tbl_assessmentconfiguration
    // and returns GMP systems where elementStatus would be 1 (for tab5 Output Preview).
    public function registerOutputPreview()
    {
        $data = $this->request->getPost();
        if (!$data) {
            return $this->response->setJSON(['success' => false, 'systems' => [], 'message' => 'No form data received.']);
        }

        $formValues = (object) [
            'productTypeId'          => $data['productTypeId'] ?? '',
            'medicineBy'             => $data['medicineBy'] ?? '',
            'computerizedSystem'     => $data['computerizedSystem'] ?? '',
            'sterileAsepticProcess'  => $data['sterileAsepticProcess'] ?? '',
            'ionisingRadiation'      => $data['ionisingRadiation'] ?? '',
            'rtrtParametricRelease'  => $data['rtrtParametricRelease'] ?? '',
        ];

        $system = $this->myModel->myGet('tbl_system', 'BaseTbl.*', 'BaseTbl.status = "Active" AND BaseTbl.category = "GMP"', 'BaseTbl.id', 'asc');
        $assessmentConfig = $this->myModel->myGet('tbl_assessmentconfiguration', 'BaseTbl.*', '1=1', 'BaseTbl.id', 'asc');

        $placeholderValues = ['Select Product Scope Model', 'Select', 'Select...'];

        $toArray = function ($str) use ($placeholderValues) {
            if (empty($str)) return [];
            $raw = trim($str, ', ');
            if ($raw === '') return [];

            foreach ($placeholderValues as $ph) {
                if (strcasecmp(trim($raw), trim($ph)) === 0) {
                    return [];
                }
            }

            $parts = array_map('trim', explode(',', $raw));
            $parts = array_filter($parts, function ($p) use ($placeholderValues) {
                if ($p === '') return false;
                foreach ($placeholderValues as $ph) {
                    if (strcasecmp($p, $ph) === 0) return false;
                }
                return true;
            });

            return array_values($parts);
        };

        $formTypes      = $toArray($formValues->productTypeId);
        $formMedicineBy = $toArray($formValues->medicineBy);

        $matchField = function ($configArr, $formArr) {
            $configEmpty = empty($configArr);
            $formEmpty   = empty($formArr);

            if ($configEmpty && $formEmpty) {
                return true;
            }
            if ($configEmpty xor $formEmpty) {
                return false;
            }
            foreach ($formArr as $val) {
                if (in_array($val, $configArr)) {
                    return true;
                }
            }
            return false;
        };

        $matchMedicineBy = function ($configArr, $formArr) {
            if (empty($configArr)) {
                return true;
            }
            if (empty($formArr)) {
                return false;
            }
            foreach ($formArr as $val) {
                if (in_array($val, $configArr)) {
                    return true;
                }
            }
            return false;
        };

        $matchedConfigs = [];
        if (!empty($assessmentConfig)) {
            foreach ($assessmentConfig as $config) {
                $configTypes      = $toArray($config->productTypeId);
                $configScope      = $toArray($config->productScopeModel);
                $configMedicineBy = $toArray($config->medicineBy);

                $typeMatch       = $matchField($configTypes, $formTypes);
                $medicineByMatch = $matchMedicineBy($configMedicineBy, $formMedicineBy);

                if ($typeMatch && $medicineByMatch) {
                    $matchedConfigs[] = $config;
                }
            }
        }

        $baselineSystemIds = [];
        $conditionalSystemStatus = [];
        $fourFields = ['computerizedSystem', 'sterileAsepticProcess', 'ionisingRadiation', 'rtrtParametricRelease'];

        foreach ($matchedConfigs as $config) {
            foreach ($toArray($config->picsParentBaselineId) as $sysId) {
                $baselineSystemIds[$sysId] = true;
            }

            $jsonSets = [
                ['data' => $config->jsonDataDetailCrossCutting,    'key' => 'crosscuttingannexesId'],
                ['data' => $config->jsonDataDetailProductSpecific, 'key' => 'productSpecificId'],
            ];

            foreach ($jsonSets as $set) {
                if (empty($set['data'])) continue;
                $rows = json_decode($set['data'], true);
                if (empty($rows)) continue;

                foreach ($rows as $row) {
                    if (empty($row[$set['key']])) continue;
                    $sysId = $row[$set['key']];

                    $hasYesCondition  = false;
                    $conditionMatched = false;

                    foreach ($fourFields as $field) {
                        $jsonVal = isset($row[$field]) ? trim($row[$field]) : '';
                        if ($jsonVal === 'Yes') {
                            $hasYesCondition = true;
                            $formVal = isset($formValues->$field) ? trim($formValues->$field) : '';
                            if ($formVal === 'Yes') {
                                $conditionMatched = true;
                            }
                        }
                    }

                    if (!$hasYesCondition) {
                        $status = 1;
                    } else {
                        $status = $conditionMatched ? 1 : 0;
                    }

                    if (!isset($conditionalSystemStatus[$sysId]) || $status == 1) {
                        $conditionalSystemStatus[$sysId] = $status;
                    }
                }
            }
        }

        $applicableSystems = [];
        if (!empty($system)) {
            foreach ($system as $record0) {
                $elementStatus = 0;

                if (isset($baselineSystemIds[$record0->id])) {
                    $elementStatus = 1;
                } elseif (isset($conditionalSystemStatus[$record0->id])) {
                    $elementStatus = $conditionalSystemStatus[$record0->id];
                }

                if ($elementStatus == 1) {
                    $applicableSystems[] = [
                        'id'   => $record0->id,
                        'name' => $record0->name,
                    ];
                }
            }
        }

        return $this->response->setJSON([
            'success'  => true,
            'systems'  => $applicableSystems,
            'message'  => empty($applicableSystems) ? 'No applicable systems found for the selected scope.' : '',
        ]);
    }
    ///// ..... The End registerOutputPreview() Function ..... /////
    
    // The registerMe() function is used to check authentication during registration in our system, such as whether the same email already exists in the database.
    function registerMe()
    {       
        $data = $this->request->getPost();
        if(!$data){
            return redirect()->to('register');
        }

        $this->dateTimeFormat = 'd-M-y H:i';
        $data['createddate'] = date($this->dateTimeFormat);

        // Check if email already exists
        $builder = $this->db->table('tbls_user');
        $builder->select('id, email');
        $builder->where('email', $data['email']);
        $query = $builder->get();
        $count = $query->getNumRows();
        if($count > 0){
            $this->session->set(["msg"=>"<span style='color:red; font-size: x-large;'>Email already exists!</span>"]);
            return redirect()->to('register');
        }

        

        if($_SESSION['captcha_code'] <> $data['captcha_code']){
            $this->session->set(["msg"=>"<span style='color:red; font-size: x-large;'>The Validation code does not match!</span>"]);
            return redirect()->to('register');
        }
        else{
            $this->session->set(["msg"=>"<span style='color:green'>The Validation code has been matched.</span>"]);
        }

        if($data['module'] == 'GMP'){
            $result1 = $this->loginModel->recordAjaxSave(['companyName' => $data['companyName'], 'productTypeId' => $data['productTypeId'], 'siteAddress' => $data['siteAddress'], 'longitude' => $data['longitude'], 'latitude' => $data['latitude'], 'countryId' => $data['countryId'], 'responsibility' => $data['responsibility'], 'contactPersonName' => $data['contactPersonName'], 'contactPersonDesignation' => $data['contactPersonDesignation'], 'contactPersonPhone' => $data['countryCode'].$data['contactPersonPhone'], 'email' => $data['email'], 'alternateContactPersonName' => $data['alternateContactPersonName'], 'alternateContactPersonDesignation' => $data['alternateContactPersonDesignation'], 'alternateContactPersonPhone' => $data['countryCode'].$data['alternateContactPersonPhone'], 'alternateEmail' => $data['alternateEmail'], 'productScopeModel' => $data['productScopeModel'], 'medicineBy' => $data['medicineBy'], 'computerizedSystem' => $data['computerizedSystem'], 'sterileAsepticProcess' => $data['sterileAsepticProcess'], 'ionisingRadiation' => $data['ionisingRadiation'], 'rtrtParametricRelease' => $data['rtrtParametricRelease'], 'status' => 'Active', 'createddate' => $data['createddate']], 'tbl_company');

            $result = $this->loginModel->registerMe(['roleId' => 709, 'countryId' => $data['countryId'], 'category' => $data['medicineBy'], 'wallpaper' => 'wp1.jpg', 'userName' => $data['companyName'], 'address' => $data['siteAddress'], 'phone' => $data['countryCode'].$data['contactPersonPhone'], 'email' => $data['email'], 'purpose' => 'Pharma Account', 'status' => 'Pending', 'profilepic' => 'avatar5.png', 'createddate' => $data['createddate']]);
        }

        if(isset($data['email'])){
            $mailData['from'] = 'RBI';
            $mailData['subject'] = 'RBI | Account Registration';
            $mailData['title'] = 'Greetings, '.$data['companyName'].'!';
            $mailData['message'] = "Your account registration request has been submitted. An authorized officer will check your credentials for the activation of your account. We will verify you with an email soon. In case of any query please write us at: support@rbi.pqmplustools.com";
            $mailData['email'] = $data['email'];
            $sendStatus = mailSend($mailData);
            if($sendStatus == true){
                $result = 1;
            }
            
            $countryName = '';
            $countryData = $this->myModel->myGet('tbls_country', 'BaseTbl.*', '', 'BaseTbl.id', 'asc');
            foreach($countryData as $detail){
                if($detail->id == $data['countryId']){
                   $countryName = $detail->countryName;
                }
            }
            // send email with HTML table 
            $mailData['from'] = 'RBI';
            $mailData['subject'] = 'RBI | Account Registration';
            $mailData['title'] = 'Greetings, Admin!';
            $mailData['message'] = "
                                <html>
                                <head>
                                    <style>
                                        table {
                                            width: 100%;
                                            border-collapse: collapse;
                                        }
                                        table, th, td {
                                            border: 1px solid black;
                                        }
                                        th, td {
                                            padding: 8px;
                                            text-align: left;
                                        }
                                    </style>
                                </head>
                                <body>
                                    <p>New registration request has been submitted with the following details:</p>
                                    <table>
                                        <tr>
                                            <th>Field</th>
                                            <th>Value</th>
                                        </tr>
                                        <tr>
                                            <td>User Name</td>
                                            <td>{$data['companyName']}</td>
                                        </tr>
                                        <tr>
                                            <td>Country</td>
                                            <td>{$countryName}</td>
                                        </tr>
                                        <tr>
                                            <td>Module</td>
                                            <td>{$data['module']}</td>
                                        </tr>
                                        <tr>
                                            <td>Company Category</td>
                                            <td>{$data['medicineBy']}</td>
                                        </tr>
                                        <tr>
                                            <td colspan='2'><a href='" . base_url() . "' target='_blank'>Click here to login</a></td>
                                        </tr>
                                    </table>
                                </body>
                                </html>
                            ";
            $mailData['email'] = 'muhammad.asghar@gmail.com';
            $sendStatus = mailSend($mailData);
            if($sendStatus == true){
                $result = 1;
            }
        }
        if(!isset($data['email'])){
            $result = 0;
        }

        if($result > 0){
            // Flash message success...
            $this->session->setFlashdata('success', 'Account registeration request submitted. We will verify you with an email soon.');
        }
        else{
            $this->session->setFlashdata('error', 'Something went wrong.');
        }
        return redirect()->to('register');
    }
    ///// ..... The End registerMe() Function ..... /////
    

    // The link to this function is available at the bottom of the login page. It is used to send a contact us email to the admin. 
    //This function uses the parameter ($action = null), which allows it to submit the mail.
    public function contactus($action = null)
    {
        if ($action == 'submit') {
            $data = $this->request->getPost();
            $this->dateTimeFullFormat = 'd-M-y H:i:s';
            $this->dateTimeFormat = 'd-M-y H:i';
            $data['createdby'] = 0;
            $data['createddate'] = date($this->dateTimeFormat);

            if (isset($data['email'])) {

                if ($data['module'] == 'GMP') {
                    $user = $this->myModel->myGet('tbls_user', 'BaseTbl.*', 'BaseTbl.roleId = 705', 'BaseTbl.id', 'asc');
                    
                $mailData = [
                    'from' => 'RBI',
                    'subject' => 'RBI | Contact Us',
                    'title' => 'Greetings, Admin!',
                    'message' => '
                        <html>
                        <body>
                            <h3>Contact Us Message</h3>
                            <p><strong>Module:</strong> ' . $data['module'] . '</p>
                            <p><strong>Name:</strong> ' . $data['name'] . '</p>
                            <p><strong>Email:</strong> ' . $data['email'] . '</p>
                            <p><strong>Message:</strong></p>
                            <p>' . nl2br($data['message']) . '</p>
                        </body>
                        </html>',
                    'email' => 'muhammad.asghar@gmail.com'
                ];
                
                // mailSend() function use to send mail successfully and this function location is : app/Helpers/customer_helper.php
                $sendStatus = mailSend($mailData);
                    if ($sendStatus == true) {
                        $result = 1;
                    }
                }

                if (!isset($data['email'])) {
                    $result = 0;
                }

                if (isset($data['email'])) {
                    $mailData = [
                        'from' => 'RBI',
                        'subject' => 'RBI | Acknowledgement',
                        'title' => 'Greetings, ' . $data['name'] . '!',
                        'message' => 'Your feedback has been submitted successfully.',
                        'email' => $data['email']
                    ];
                    $sendStatus = mailSend($mailData);
                    if ($sendStatus == true) {
                        $result = 1;
                        echo '<script>
                                alert("Your feedback has been submitted successfully.");
                                window.location.href = "' . base_url() . '";
                              </script>';
                    }
                }
                return redirect()->to('/');
            } else {
                return $this->pageNotFound(); // Handle the case where action is not 'submit'
            }
        } else {
            return $this->pageNotFound(); // Handle the case where action is not 'submit'
        }
    }
    ///// ..... The End contactus($action = null) Function ..... /////


    // documentation($action = NULL) function use load the documentation view.
    // And in this function ($action = NULL) parameter use to submit the data.
    public function documentation($action = NULL)
    {
        if($action == 'submit'){
            $data = $this->request->getPost();
            //unset($data['captcha_code']);
            $this->userId = 0;
            $this->dateTimeFullFormat = 'd-M-y H:i:s';
            $this->dateTimeFormat = 'd-M-y H:i';
            $data['createdby'] = 0;
            $data['createddate'] = date($this->dateTimeFormat);
            
            // $fileName = 'attachment';
            // if(@$_FILES[$fileName]['tmp_name']){
            //     $data[$fileName] = $this->fileMove('DocumentationAttachment', $fileName, $this->companyName, 'docs');
            // }
            
            foreach ($data as $key => $row){
                if(@$_FILES[$key]['tmp_name']){
                    $data[$key] = $this->fileMove($_FILES[$key]['name'], $key, $this->companyUniqueNo, 'docs');
                }
            }
            
            $result = $this->loginModel->recordAjaxUpdate('id', 1, ['saManual' => $data['saManual'], 'manual' => $data['manual'], 'saUAT' => $data['saUAT'], 'gmpUAT' => $data['gmpUAT'], 'gsdpUAT' => $data['gsdpUAT'], 'readMe' => $data['readMe'], 'createdby' => $data['createdby'], 'createddate' => $data['createddate']], 'tbls_documentation');
            
            return redirect('dashboard');
        }

        else{
            return $this->pageNotFound();
        }
    }
    ///// ..... The End documentation($action = NULL) Function ..... /////
    
    // The resetPassword() function is used to help generate the forgot password token key...
    public function resetPassword()
    {
        $data = $this->request->getPost();
        if (!$data) {
            $this->accessDenied();
            return;
        }

        if ($this->session->get('captcha_code') !== $data['captcha_code']) {
            $this->session->set("msg", "<span style='color:red'>The Validation code does not match!</span>");
            return redirect()->to('forgotPassword');
        } else {
            $this->session->set("msg", "<span style='color:green'>The Validation code has been matched.</span>");
        }

        $db = \Config\Database::connect();
        $builder = $db->table('tbls_user as BaseTbl');
        $builder->select('BaseTbl.id, BaseTbl.email, BaseTbl.userName')
                ->where('BaseTbl.email', $data['email'])
                ->where('BaseTbl.status', "Active");
        $query = $builder->get();

        $row = $query->getRow();
        if ($row) {
            $id = $row->id;
            $email = $row->email;
            $userName = $row->userName;
            
            //$this->generateRandomString help to make a token key...
            $token = $this->generateRandomString(64);

            $mailData['from'] = 'RBI';
            $mailData['subject'] = 'RBI | Account Reset Password Confirmation';
            $mailData['title'] = 'Greetings, ' . $userName . '!';
            $mailData['message'] = "Are you trying to Reset your RBI Account Password?\r\nPlease click the following link to confirm Reset Password or copy the URL and paste it in the browser.\r\n <a href='" . base_url() . "generatePassword/" . $token . "'>" . base_url() . "generatePassword/" . $token . "</a>";
            $mailData['email'] = $email;
            $sendStatus = mailSend($mailData);
            if ($sendStatus) {
                $this->session->set('email_token', $token);
                $this->session->set('email_email', $email);
                $result = 1;
            }
        }

        if (!isset($email)) {
            $result = 0;
        }

        if ($result > 0) {
            $this->session->setFlashdata('success', 'Reset Password Confirmation Email has been sent. Please check your Email.');
        } else {
            $this->session->setFlashdata('error', 'Something went wrong.');
        }

        return redirect()->to('forgotPassword');
    }
    ///// ..... The End  resetPassword() Function ..... /////
    
    //The generatePassword($token) function helps provide the final password via email. In this function, $token is a parameter. After receiving the token, this function generates the final password.
    public function generatePassword($token)
    {
        $data = $this->request->getPost();
        if ($this->session->get('email_token') !== $token) {
            return redirect()->to('login');
        }

        $db = \Config\Database::connect();
        $builder = $db->table('tbls_user as BaseTbl');
        $builder->select('BaseTbl.id, BaseTbl.email, BaseTbl.userName')
                ->where('BaseTbl.email', $this->session->get('email_email'))
                ->where('BaseTbl.status', "Active");
        $query = $builder->get();

        $row = $query->getRow();
        if ($row) {
            $id = $row->id;
            $email = $row->email;
            $userName = $row->userName;

            $this->userId = $id;
            $this->dateFormat = 'd-M-y';
            $this->dateTimeFormat = 'd-M-y H:i';
            $this->dateTimeFullFormat = 'd-M-y H:i:s';
            $data['updatedby'] = $id;
            $data['updateddate'] = date($this->dateTimeFormat);

            $password = $this->generateRandomString(5);
            $hashedPassword = getHashedPassword($password);
            $result = $this->loginModel->recordAjaxUpdate('id', $id, ['password' => $hashedPassword, 'updatedby' => $data['updatedby'], 'updateddate' => $data['updateddate']], 'tbls_user');

            $mailData['from'] = 'RBI';
            $mailData['subject'] = 'RBI | Account Reset Password Information';
            $mailData['title'] = 'Greetings, ' . $userName . '!';
            $mailData['message'] = "Please use the following new generated password to Sign In to your RBI Account.\r\nPassword: " . $password . "\r\nYou can change it anytime!";
            $mailData['email'] = $email;
            $sendStatus = mailSend($mailData);
            if ($sendStatus) {
                $result = 1;
            }
        }

        if (!isset($email)) {
            $result = 0;
        }

        $this->session->remove('email_token');
        $this->session->remove('email_email');

        if ($result > 0) {
            $this->session->setFlashdata('success', 'Reset Password Information Email has been sent. Please check your Email.');
        } else {
            $this->session->setFlashdata('error', 'Something went wrong.');
        }

        return redirect()->to('login');
    }
    ///// ..... The End  generatePassword($token) Function ..... /////

    // This function is the heart of the system because it loads all the modules in our system.
    //explain the parameters
    // $viewName = "" use to get module name 
    //$headerInfo = NULL, use to get the header file 
    //$pageInfo = NULL use to get some important information related to the user during login
    //$footerInfo = NULL use to get footer file 
    protected function loadViews($viewName = "", $headerInfo = NULL, $pageInfo = NULL, $footerInfo = NULL)
    {   
        // Ensure the arguments are arrays
        $headerInfo = $headerInfo ?? [];
        $pageInfo = $pageInfo ?? [];
        $footerInfo = $footerInfo ?? [];

        $headerInfo['companyName'] = $this->companyName;
        $headerInfo['session'] = $this->session;
        $headerInfo['userName'] = $this->userName;
        $headerInfo['companyTheme'] = $this->companyTheme;
        $headerInfo['loginModel'] = $this->loginModel;
        $headerInfo['roleId'] = $this->roleId;
        $headerInfo['countryName'] = $this->countryName;
        $headerInfo['countryId'] = $this->countryId;
        $headerInfo['countryFlag'] = $this->countryFlag;
        $headerInfo['profilepic'] = $this->profilepic;
        $headerInfo['department'] = $this->department;
        $headerInfo['designation'] = $this->designation;
        $headerInfo['wallpaper'] = $this->wallpaper;
        $headerInfo['companyProject'] = $this->companyProject;
        $headerInfo['userId'] = $this->userId;
        $headerInfo['groupEmail'] = $this->groupEmail;
        $footerInfo['companyAddress'] = $this->companyAddress;
        echo view('includes/header', $headerInfo);
        echo view($viewName, $pageInfo);
        echo view('includes/footer', $footerInfo);
    }
    ///// ..... The End  loadViews() Function ..... /////
    
    // loadViewsSuper function load the Super Administrater views only...
    protected function loadViewsSuper($viewName = "", $headerInfo = NULL, $pageInfo = NULL, $footerInfo = NULL)
    {
        echo view('includes/headerSuper', $headerInfo);
        echo view($viewName, $pageInfo);
        echo view('includes/footer', $footerInfo);
    }
    ///// ..... The End  loadViewsSuper() Function ..... /////
    
    // The pageNotFound() function is used when the page routes are not correctly defined. This function displays a 'Page Not Found' message.
    public function pageNotFound()
    {
        $this->global['pageTitle'] =  'RBI | 404 - Page Not Found';
        
        return $this->loadViews("errors/404", $this->global, NULL, NULL);
    }
    ///// ..... The End  pageNotFound() Function ..... /////
    
    // The accessDenied() function is used to handle system rights when a user does not have the necessary permissions. This function works by showing an 'Access Denied' image in the module.
    public function accessDenied()
    {
        $this->global ['pageTitle'] = $this->companyNick.' | Access Denied';

        return $this->loadViews("errors/access", $this->global, NULL, NULL);
    }
    ///// ..... The End  accessDenied() Function ..... /////
    
    //The accessDeniedMain() function is used to handle system rights when a user does not have the necessary permissions. This function works by showing an 'Access Denied' image in the module.
    public function accessDeniedMain()
    {
        if($this->status == 'Inactive')
        {
            $this->global ['pageTitle'] = $this->companyNick.' | Access Denied';

            return $this->loadViews('errors/accessMain', $this->global);
            $this->loginModel->onlineStatusUpdate($this->session->get('userId'), array('isOnline' => 0));
            $this->loginModel->auditLogSave(['userId' => $this->session->get('userId'), 'logoutTime' => date($this->dateTimeFullFormat), 'ipAddress'=>$_SERVER['REMOTE_ADDR']]);
            $this->session->destroy();
        }
        else
        {
            return redirect()->to('login');
        }
    }
    ///// ..... The End  accessDeniedMain() Function ..... /////
    

    // lockscreen() function is used to lock the system 
    public function lockscreen()
    {
        $this->global['pageTitle'] = $this->companyNick . ' | Lockscreen';
        $data['wallpaper'] = $this->wallpaper;
        $data['companyProject'] = $this->companyProject;
        $data['userName'] = $this->userName;
        $data['profilepic'] = $this->profilepic;
        $data['companyName'] = $this->companyName;
        $data['session'] = $this->session;
        $data['companyAddress'] = $this->companyAddress;

        // Merge $this->global and $data into a single array
        $viewData = array_merge($this->global, $data);

        return view('lockscreen', $viewData);
    }
    ///// ..... The End  lockscreen() Function ..... /////


    //################## AJAX FUNCTIONS ##################
    public function pageAjaxGet()
    {
        $functionName = __FUNCTION__;

        echo $this->loginModel->$functionName('', '');
    }

    public function alertAjaxGet()
    {
        echo $this->loginModel->alertAjaxGet();
    }

    public function reportTypeDetailAjaxGet()
    {
        if($this->request->getPost()('reportType'))
        {
            $reportType = $this->request->getPost()('reportType');
            echo $this->loginModel->reportTypeDetailAjaxGet($reportType);
        }
    }
    
    // Whenever there is an update in the software, this function helps set the system to 'Maintenance Mode' on the landing page. After 'Maintenance Mode' is activated, no user can access the system.
    // After updating the system, set it back to 'Active Mode' to resume normal operation.
    public function systemUpdate()
    {
        if($this->request->getPost('system') == 'Active')
        {
            $this->loginModel->recordAjaxUpdate('id', '1', ['status' => 'Active', 'updatedby' => $this->userId, 'updateddate' => date($this->dateTimeFormat)], 'tbls_system');
            $this->loginModel->recordAjaxUpdate('id', '2', ['status' => 'Inactive', 'updatedby' => $this->userId, 'updateddate' => date($this->dateTimeFormat)], 'tbls_system');
        }
        if($this->request->getPost('system') == 'Maintenance')
        {
            $this->loginModel->recordAjaxUpdate('id', '1', ['status' => 'Inactive', 'updatedby' => $this->userId, 'updateddate' => date($this->dateTimeFormat)], 'tbls_system');
            $this->loginModel->recordAjaxUpdate('id', '2', ['status' => 'Active', 'updatedby' => $this->userId, 'updateddate' => date($this->dateTimeFormat)], 'tbls_system');
        }
    }
    ///// ..... The End  systemUpdate() Function ..... /////

    //################## AJAX FUNCTIONS ##################

    //################## MAIN FUNCTIONS ##################
    
    // The generateRandomString($length) function provides the token characters during the forgot password process.
    public function generateRandomString($length)
    {
        $characters = '123456789abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    ///// ..... The End  generateRandomString() Function ..... /////
    

    // the fileMove function used for save the attachment files in the db.
    // explain parameters...
    //$lable : used to get attach file size 
    //$fileName : used to get attach file name
    //$path: used to which path attach file store
    //$folder : used to get which folder save the file.
    public function fileMove($label, $fileName, $path, $folder)
    {
        $file = $label.'_'.random_int(0,999999);
        $path = 'uploads/company/'.$path.'/'.$folder.'/';
        $imgtmpname = $_FILES[$fileName]['tmp_name'];
        $name = $_FILES[$fileName]['name'];
        $extention = explode('.', $name);
        $ext = end($extention);
        $fullpath = $path.$file.'.'.$ext;
        $filepath = $file.'.'.$ext;
        $allowedFileTypes = array('image/jpeg', 'image/png', 'application/pdf', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        if($_FILES[$fileName]['size'] <= 10000000){
            //if(in_array($_FILES[$fileName]['type'], $allowedFileTypes)){
                move_uploaded_file($imgtmpname,$fullpath);
                if($ext <> ''){
                    return $filepath;
                }
            //}
        }
    }
    ///// ..... The End  fileMove() Function ..... /////


    // This function help to save the multiple file save in the db/
    public function fileMoveMultiple($label, $fileName, $index, $path, $folder)
    {
        $file = $label.'_'.random_int(0,999999);
        $path = 'uploads/company/'.$path.'/'.$folder.'/';
        $imgtmpname = $_FILES[$fileName]['tmp_name'][$index];
        $name = $_FILES[$fileName]['name'][$index];
        $extention = explode('.', $name);
        $ext = end($extention);
        $fullpath = $path.$file.'.'.$ext;
        $filepath = $file.'.'.$ext;
        $allowedFileTypes = array('image/jpeg', 'image/png', 'application/pdf');
        if($_FILES[$fileName]['size'][$index] <= 5000000){
            if(in_array($_FILES[$fileName]['type'][$index], $allowedFileTypes)){
                move_uploaded_file($imgtmpname,$fullpath);
                if($ext <> ''){
                    return $filepath;
                }
            }
        }
    }
    ///// ..... The End  fileMoveMultiple() Function ..... /////


    // The dataDetailTableSave() function is used to save multiple table rows in the database.
    //explain the parameters..
    //$masterId: used to get the main table id.
    //$datadetail : used to get multiple data of rows get.
    //$tableDetail : used to get table name during save the data in db.
    // Uses recordAjaxBulkSave / recordAjaxBulkUpdate so large grids (e.g. gmpexecution) stay fast.
    public function dataDetailTableSave($masterId, $datadetail, $tableDetail)
    {
        $idColumn = 'id';
        $keys = array_keys($datadetail);
        if (empty($keys) || empty($datadetail[$keys[0]])) {
            return 0;
        }

        $rows = [];
        for ($i = 0, $len = count($datadetail[$keys[0]]); $i < $len; $i++) {
            $rows[$i] = [];
            foreach ($keys as $key) {
                $rows[$i][$key] = isset($datadetail[$key][$i]) ? $datadetail[$key][$i] : 'n/a';
            }
        }

        // One lookup instead of SELECT-per-row
        $ids = [];
        foreach ($rows as $row) {
            $id = trim((string) ($row[$idColumn] ?? ''));
            if ($id !== '' && strtolower($id) !== 'n/a') {
                $ids[] = $id;
            }
        }
        $ids = array_values(array_unique($ids));

        $existingIds = [];
        if (!empty($ids)) {
            $existing = $this->db->table($tableDetail)
                ->select($idColumn)
                ->where('masterId', $masterId)
                ->whereIn($idColumn, $ids)
                ->get()
                ->getResultArray();
            foreach ($existing as $ex) {
                $existingIds[(string) $ex[$idColumn]] = true;
            }
        }

        $toInsert = [];
        $toUpdate = [];
        $now = date($this->dateTimeFormat);

        foreach ($rows as $row) {
            $column_data = $row;
            $column_keys = array_keys($column_data);
            $id = trim((string) ($column_data[$idColumn] ?? ''));

            if (!isset($existingIds[$id])) {
                $datainsert = [];
                foreach ($column_keys as $key) {
                    $datainsert[$key] = $column_data[$key];
                    if (isset($_FILES[$key]['tmp_name'])) {
                        $datainsert[$key] = $this->fileMove('Attachment', $key, $this->companyName, 'docs');
                    }
                }
                $datainsert['createdby'] = $this->userId;
                $datainsert['createddate'] = $now;
                $datainsert['updateddate'] = $now;
                unset($datainsert['id']);
                $datainsert['masterId'] = $masterId;
                $toInsert[] = $datainsert;
            } else {
                $dataupdate = [];
                foreach ($column_keys as $key) {
                    $dataupdate[$key] = $column_data[$key];
                    if (isset($_FILES[$key]['tmp_name'])) {
                        $dataupdate[$key] = $this->fileMove('Attachment', $key, $this->companyName, 'docs');
                    }
                }
                $dataupdate['updatedby'] = $this->userId;
                $dataupdate['updateddate'] = $now;
                // Keep id in row — required as updateBatch constraint
                $toUpdate[] = $dataupdate;
            }
        }

        $resultdetail = 0;
        if (!empty($toInsert)) {
            $resultdetail = $this->loginModel->recordAjaxBulkSave($toInsert, $tableDetail);
        }
        if (!empty($toUpdate)) {
            $resultdetail = $this->loginModel->recordAjaxBulkUpdate($idColumn, $toUpdate, $tableDetail);
        }

        return $resultdetail;
    }
    ///// ..... The End  dataDetailTableSave() Function ..... /////
    
    // The structure() function used for load the structure module only.....
    public function structure()
    {
        if (!session()->has('userId')) { // Check if the session key exists
            return redirect()->to('/login'); // Redirect to login if session does not exist
        }

        $isLoggedIn = $this->session->get('isLoggedIn'); if(!isset($isLoggedIn) || $isLoggedIn != TRUE){redirect('login');} $found=false; $rolePage = $this->loginModel->rolePageGet($this->roleId); if(!empty($rolePage)){foreach ($rolePage as $res){$pageName = $res->url; $recordLookup = $res->recordLookup; $recordAdd = $res->recordAdd; $recordEdit = $res->recordEdit; $recordView = $res->recordView; $recordDelete = $res->recordDelete; $recordSubmit = $res->recordSubmit; if(__FUNCTION__ == $pageName){ $found=true; break;}}} if($found == false){$this->accessDenied(); return;}

        $functionName = __FUNCTION__;
        $functionNameEdit = __FUNCTION__.'Edit';

        $data['pageTitle'] = $this->loginModel->pageTitleGet(__FUNCTION__);
        $this->global['pageTitle'] = $this->companyProject.' | '.$data['pageTitle'][0]->friendlyName;

        $loginModel = new LoginModel();
        $data['result'] = $loginModel->systemInfoGet();

        return $this->loadViews(__FUNCTION__, $this->global, $data, NULL);
    }
    ///// ..... The End  structure() Function ..... /////
    

    // The backupDB() function used for backup the system data-base...
    public function backupDB()
    {  
        if (!session()->has('userId')) { // Check if the session key exists
            return redirect()->to('/login'); // Redirect to login if session does not exist
        }
        $isLoggedIn = $this->session->get('isLoggedIn'); if(!isset($isLoggedIn) || $isLoggedIn != TRUE){redirect('login');} $found=false; $rolePage = $this->loginModel->rolePageGet($this->roleId); if(!empty($rolePage)){foreach ($rolePage as $res){$pageName = $res->url; $recordLookup = $res->recordLookup; $recordAdd = $res->recordAdd; $recordEdit = $res->recordEdit; $recordView = $res->recordView; $recordDelete = $res->recordDelete; $recordSubmit = $res->recordSubmit; if(__FUNCTION__ == $pageName){ $found=true; break;}}} if($found == false){$this->accessDenied(); return;}

        $functionName = __FUNCTION__;
        $functionNameEdit = __FUNCTION__.'Edit';

        $data['pageTitle'] = $this->loginModel->pageTitleGet(__FUNCTION__);
        $this->global['pageTitle'] = $this->companyProject.' | '.$data['pageTitle'][0]->friendlyName;

        if (!$this->request->isAJAX()) {
        return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request.'])->setStatusCode(400);
        }

        $isLoggedIn = $this->session->get('isLoggedIn');
        if (!$isLoggedIn) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not logged in.'])->setStatusCode(401);
        }

        $db = \Config\Database::connect();
        helper('filesystem');

        // Define backup folder and ensure it exists
        $backupPath = 'backup/';
        if (!is_dir($backupPath)) {
            mkdir($backupPath, 0777, true);
        }

        // Generate filename
        $fileName = 'RBI_' . date('d-M-y_H-i-s') . '.sql';
        $filePath = $backupPath . $fileName;

        // Attempt to open file for writing
        if (!$handle = fopen($filePath, 'w')) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unable to create backup file.'])->setStatusCode(500);
        }

        // Fetch and write all table data
        foreach ($db->listTables() as $table) {
            $createTable = $db->query("SHOW CREATE TABLE `$table`")->getRowArray()['Create Table'];
            fwrite($handle, "\n\n$createTable;\n\n");

            // Fetch all rows in batch processing
            $query = $db->query("SELECT * FROM `$table`");
            foreach ($query->getResultArray() as $row) {
                $values = implode(", ", array_map([$db, 'escape'], $row));
                fwrite($handle, "INSERT INTO `$table` VALUES ($values);\n");
            }
            fwrite($handle, "\n\n");
        }

        fclose($handle);

        return $this->response->setJSON(['status' => 'success', 'file' => $fileName])->setStatusCode(200);
    }


    ///// ..... The End  backupDB() Function ..... /////
    
    // The backupSoftware() function used for the backup complete system//
    public function backupSoftware()
    {
        if (!session()->has('userId')) { // Check if the session key exists
            return redirect()->to('/login'); // Redirect to login if session does not exist
        }
        $isLoggedIn = $this->session->get('isLoggedIn'); if(!isset($isLoggedIn) || $isLoggedIn != TRUE){redirect('login');} $found=false; $rolePage = $this->loginModel->rolePageGet($this->roleId); if(!empty($rolePage)){foreach ($rolePage as $res){$pageName = $res->url; $recordLookup = $res->recordLookup; $recordAdd = $res->recordAdd; $recordEdit = $res->recordEdit; $recordView = $res->recordView; $recordDelete = $res->recordDelete; $recordSubmit = $res->recordSubmit; if(__FUNCTION__ == $pageName){ $found=true; break;}}} if($found == false){$this->accessDenied(); return;}

        $functionName = __FUNCTION__;
        $functionNameEdit = __FUNCTION__.'Edit';

        $data['pageTitle'] = $this->loginModel->pageTitleGet(__FUNCTION__);
        $this->global['pageTitle'] = $this->companyProject.' | '.$data['pageTitle'][0]->friendlyName;

        // Define backup directory
        $backupPath = 'backup/';
        if (!is_dir($backupPath) && !mkdir($backupPath, 0777, true) && !is_dir($backupPath)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to create backup directory.'])->setStatusCode(500);
        }

        // Define backup filename
        $fileName = 'RBI-Software-Backup_' . date('Y-m-d_H-i-s') . '.zip';
        $filePath = $backupPath . $fileName;

        // Get the root application path
        $appPath = realpath(APPPATH); // Ensures correct absolute path

        // Initialize ZipArchive
        $zip = new \ZipArchive();
        if ($zip->open($filePath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to create ZIP file.'])->setStatusCode(500);
        }

        // Use generator to reduce memory usage when adding files
        foreach ($this->getFilesIterator($appPath) as $file) {
            $filePathInZip = str_replace($appPath . DIRECTORY_SEPARATOR, '', $file);
            $zip->addFile($file, $filePathInZip);
        }

        $zip->close();

        return $this->response->setJSON(['status' => 'success', 'file' => $fileName])->setStatusCode(200);
    }
    ///// ..... The End  backupSoftware() Function ..... /////

    /**
     * Generator function to get files in a directory recursively
     * This improves memory efficiency over using RecursiveIteratorIterator
     */
    private function getFilesIterator(string $directory): \Generator
    {
        $directoryIterator = new \RecursiveDirectoryIterator($directory, \RecursiveDirectoryIterator::SKIP_DOTS);
        $iterator = new \RecursiveIteratorIterator($directoryIterator, \RecursiveIteratorIterator::LEAVES_ONLY);

        foreach ($iterator as $file) {
            if ($file->isFile()) {
                yield $file->getPathname();
            }
        }
    }

    
    // The permission($action = NULL, $id = NULL) function is used to manage permissions for the overall system modules.
    // $action : used update the permissions, such as submit.
    // $id : which row id update.
    public function permission($action = NULL, $id = NULL)
    {
        if (!session()->has('userId')) { // Check if the session key exists
            return redirect()->to('/login'); // Redirect to login if session does not exist
        }

        $isLoggedIn = $this->session->get('isLoggedIn'); if(!isset($isLoggedIn) || $isLoggedIn != TRUE){redirect('login');} $found=false; $rolePage = $this->loginModel->rolePageGet($this->roleId); if(!empty($rolePage)){foreach ($rolePage as $res){$pageName = $res->url; $recordLookup = $res->recordLookup; $recordAdd = $res->recordAdd; $recordEdit = $res->recordEdit; $recordView = $res->recordView; $recordDelete = $res->recordDelete; $recordSubmit = $res->recordSubmit; if(__FUNCTION__ == $pageName){ $found=true; break;}}} if($found == false){$this->accessDenied(); return;}

        $functionName = __FUNCTION__;
        $functionNameEdit = __FUNCTION__.'Edit';

        $data['pageTitle'] = $this->loginModel->pageTitleGet(__FUNCTION__);
        $this->global['pageTitle'] = $this->companyProject.' | '.$data['pageTitle'][0]->friendlyName;

        $myAction = '';
        if($action == 'submit'){
            if(explode('/', $_SERVER['HTTP_REFERER'])[4] == __FUNCTION__){
                $myAction = explode('/', $_SERVER['HTTP_REFERER'])[5];
            }
            if(explode('/', $_SERVER['HTTP_REFERER'])[3] == __FUNCTION__){
                $myAction = explode('/', $_SERVER['HTTP_REFERER'])[4];
            }
            if($myAction == 'add'){
                $myAction = 'save';
            }
            if($myAction == 'edit'){
                $myAction = 'update';
            }
        }

        $table = 'tbls_role';
        $searchText = $this->request->getPost('searchText');
        $data['searchText'] = $searchText;
        $data['records'] = $this->loginModel->$functionName($table, $searchText);
        $data['recordsEdit'] = $this->loginModel->$functionNameEdit($id, $table);
        $data['recordsDetailPage'] = $this->loginModel->permissionDetailEdit($id, 'tbls_pagedetail');
        $data['role'] = $this->loginModel->roleGet();
        $data['page'] = $this->loginModel->pageGet();

        if($action == 'lookup' && $recordLookup == 1){
            $this->loadViews(__FUNCTION__, $this->global, $data, NULL);
        }

        else if($action == 'add' && $recordAdd == 1){
            $this->accessDenied();
            return;
        }

        else if($action == 'edit' && $recordEdit == 1){
            if(!$id){
                $this->accessDenied();
                return;
            }
            
            $this->loadViews(__FUNCTION__, $this->global, $data, NULL);
        }

        else if($action == 'view' && $recordView == 1){
            if(!$id){
                $this->accessDenied();
                return;
            }
            
            $this->loadViews(__FUNCTION__, $this->global, $data, NULL);
        }

        else if($action == 'delete' && $recordDelete == 1){
            $this->accessDenied();
            return;
        }

        else if($myAction == 'save' && $recordSubmit == 1){
            $this->accessDenied();
            return;
        }

        else if($myAction == 'update' && $recordSubmit == 1){
            $data = $this->request->getPost();
            if(!$data){
                $this->accessDenied();
                return;
            }
            $data['updatedby'] = $this->userId;
            $data['updateddate'] = date($this->dateTimeFormat);
            $result = 0;
            $resultdetail = 0;

            $tableName = 'tabledetailpage-';
            $datadetail['id'] = $data[$tableName.'id_detail'];
            $datadetail['masterId'] = $data[$tableName.'masterId_detail'];
            $datadetail['recordLookup'] = $data[$tableName.'recordLookup_detail'];
            $datadetail['recordAdd'] = $data[$tableName.'recordAdd_detail'];
            $datadetail['recordEdit'] = $data[$tableName.'recordEdit_detail'];
            $datadetail['recordView'] = $data[$tableName.'recordView_detail'];
            $datadetail['recordDelete'] = $data[$tableName.'recordDelete_detail'];
            $datadetail['recordSubmit'] = $data[$tableName.'recordSubmit_detail'];
            $idColumn = 'id';
            $table = 'tbls_pagedetail';
            $keys = array_keys($datadetail);
            $rows = [];
            for($i = 0, $len = count($datadetail[$keys[0]]); $i < $len; $i++){
                $rows[$i] = [];
                foreach($keys as $key){
                    $rows[$i][$key] = isset($datadetail[$key][$i]) ? $datadetail[$key][$i] : 'n/a';
                }
            }
            for($row = 0, $cnt = count($rows); $row < $cnt; $row++){
                $column_data = $rows[$row];
                $column_keys = array_keys($column_data);
                $id = $column_data[$idColumn];
                $id = trim($id);
                $queryCheck = $this->db->table($table)->where($idColumn, $id)->where('roleId', substr($_SERVER['HTTP_REFERER'], strrpos($_SERVER['HTTP_REFERER'], '/') + 1))->where('isDeleted', 0)->get();
                $count = $queryCheck->getNumRows();
                if($count === 0){  
                    foreach($column_keys as $key){
                        $datainsert[$key] = $column_data[$key];
                    }
                    $datainsert['createdby'] = $this->userId;
                    $datainsert['createddate'] = date($this->dateTimeFormat);
                    unset($datainsert['id']);
                    $datainsert['roleId'] = substr($_SERVER['HTTP_REFERER'], strrpos($_SERVER['HTTP_REFERER'], '/') + 1);
                    $resultdetail = $this->loginModel->recordAjaxSave($datainsert, $table);
                }
                if($count > 0){
                    foreach($column_keys as $key){
                        $dataupdate[$key] = $column_data[$key];
                    }
                    $dataupdate['updatedby'] = $this->userId;
                    $dataupdate['updateddate'] = date($this->dateTimeFormat);
                    unset($dataupdate['id']);
                    $resultdetail = $this->loginModel->recordAjaxUpdate($idColumn, $id, $dataupdate, $table);
                }
            }
            unset($datadetail);
            unset($rows);
            unset($column_data);
            unset($column_keys);
            unset($datainsert);
            unset($dataupdate);

            if($result > 0 || $resultdetail > 0){
                $this->session->setFlashdata('success', 'Record updated successfully.');
            }
            else{
                $this->session->setFlashdata('error', 'Something went wrong.');
            }
            
            return redirect()->to(__FUNCTION__.'/lookup');
        }

        else{
            $this->accessDenied();
            return;
        }
    }
    ///// ..... The End  permission() Function ..... /////
    
    // The helpdesk($action = NULL, $id = NULL) function used for load the helpdesk view only...
    // $action : help add, edit, submit, delete.
    // $id : which row id update.
    public function helpdesk($action = NULL, $id = NULL)
    {
        if (!session()->has('userId')) { // Check if the session key exists
            return redirect()->to('/login'); // Redirect to login if session does not exist
        }
        $isLoggedIn = $this->session->get('isLoggedIn'); if(!isset($isLoggedIn) || $isLoggedIn != TRUE){redirect('login');} $found=false; $rolePage = $this->loginModel->rolePageGet($this->roleId); if(!empty($rolePage)){foreach ($rolePage as $res){$pageName = $res->url; $recordLookup = $res->recordLookup; $recordAdd = $res->recordAdd; $recordEdit = $res->recordEdit; $recordView = $res->recordView; $recordDelete = $res->recordDelete; $recordSubmit = $res->recordSubmit; if(__FUNCTION__ == $pageName){ $found=true; break;}}} if($found == false){$this->accessDenied(); return;}

        $functionName = __FUNCTION__;
        $functionNameEdit = __FUNCTION__.'Edit';

        $data['pageTitle'] = $this->loginModel->pageTitleGet(__FUNCTION__);
        $this->global['pageTitle'] = $this->companyProject.' | '.$data['pageTitle'][0]->friendlyName;

        $myAction = '';
        if($action == 'submit'){
            if(explode('/', $_SERVER['HTTP_REFERER'])[4] == __FUNCTION__){
                $myAction = explode('/', $_SERVER['HTTP_REFERER'])[5];
            }
            if(explode('/', $_SERVER['HTTP_REFERER'])[3] == __FUNCTION__){
                $myAction = explode('/', $_SERVER['HTTP_REFERER'])[4];
            }
            if($myAction == 'add'){
                $myAction = 'save';
            }
            if($myAction == 'edit'){
                $myAction = 'update';
            }
        }

        $table = 'tbls_'.__FUNCTION__;
        $searchText = $this->request->getPost('searchText');
        $data['searchText'] = $searchText;
        $data['records'] = $this->loginModel->$functionName($table, $searchText);
        $data['recordsEdit'] = $this->loginModel->$functionNameEdit($id, $table);

        if($action == 'lookup' && $recordLookup == 1){
            $this->loadViews(__FUNCTION__, $this->global, $data, NULL);
        }

        else if($action == 'add' && $recordAdd == 1){
            $this->loadViews(__FUNCTION__, $this->global, $data, NULL);
        }

        else if($action == 'edit' && $recordEdit == 1){
            if(!$id){
                $this->accessDenied();
                return;
            }
            
            $this->loadViews(__FUNCTION__, $this->global, $data, NULL);
        }

        else if($action == 'view' && $recordView == 1){
            if(!$id){
                $this->accessDenied();
                return;
            }
            
            $this->loadViews(__FUNCTION__, $this->global, $data, NULL);
        }

        else if($action == 'delete' && $recordDelete == 1){
            $data = array('isDeleted'=>1, 'updateddate'=>date($this->dateTimeFormat), 'updatedby'=>$this->userId);

            if($this->roleId == 1){
                $result = $this->loginModel->recordAjaxUpdate('id', $id, $data, $table);
            }

            if($result > 0){
                $this->session->setFlashdata('success', 'Record deleted successfully.');
            }
            else{
                $this->session->setFlashdata('error', 'Something went wrong.');
            }

            return redirect()->to(__FUNCTION__.'/lookup');
        }

        else if($myAction == 'save' && $recordSubmit == 1){
            $data = $this->request->getPost();
            if(!$data){
                $this->accessDenied();
                return;
            }
            $data['createdby'] = $this->userId;
            $data['createddate'] = date($this->dateTimeFormat);
            $result = 0;
            $resultdetail = 0;

            $fileName = 'attachment';
            if(@$_FILES[$fileName]['tmp_name']){
                $data[$fileName] = $this->fileMove('HelpDeskAttachment', $fileName, $this->companyName, 'helpdesk');
            }

            $result = $this->loginModel->recordAjaxSave(['dateTime' => $data['createddate'], 'userId' => $this->userId, 'moduleName' => $data['moduleName'], 'pageName' => $data['pageName'], 'fieldName' => $data['fieldName'], 'description' => $data['description'], 'stepsToReproduceError' => $data['stepsToReproduceError'], 'currentBehaviour' => $data['currentBehaviour'], 'idealScenario' => $data['idealScenario'], 'errorCode' => $data['errorCode'], 'attachment' => $data['attachment'], 'issueStatus' => 'Logged', 'status' => 'Active', 'createdby' => $data['createdby'], 'createddate' => $data['createddate']], $table);
            
                $currentUser = $this->myModel->myGet('tbls_user', 'BaseTbl.*', 'BaseTbl.roleId = 1', 'BaseTbl.id', 'asc');
                $mailData['from'] = 'RBI';
                $mailData['subject'] = 'RBI | Help Desk';
                $mailData['title'] = 'Greetings, Admin!';
                $mailData['message'] = "New Issue Logged.";
                //$mailData['email'] = $currentUser[0]->email;
                $mailData['email'] = $currentUser[0]->email;
                $sendStatus = mailSend($mailData);
                if($sendStatus == true){
                    $result = 1;
                }

            // ------------------- Send Alert Start -------------------
            $result = $this->loginModel->recordAjaxSave(['type' => 'User', 'alertName' => 'Greetings Admin, New Help Desk Question!', 'description' => 'Please check help desk module.', 'dateTime' => $data['createddate'], 'duration' => 'now', 'recepients' => '0, 1', 'status' => 'Active', 'createdby' => $data['createdby'], 'createddate' => $data['createddate']], 'tbls_alert');
            // ------------------- Send Alert End -------------------

            if($result > 0){
                $this->session->setFlashdata('success', 'New record saved successfully.');
            }
            else{
                $this->session->setFlashdata('error', 'Something went wrong.');
            }

            return redirect()->to(__FUNCTION__.'/lookup');
        }

        else if($myAction == 'update' && $recordSubmit == 1){
            $data = $this->request->getPost();
            if(!$data){
                $this->accessDenied();
                return;
            }
            $data['updatedby'] = $this->userId;
            $data['updateddate'] = date($this->dateTimeFormat);
            $result = 0;
            $resultdetail = 0;
            
            $fileName = 'attachment';
            if(@$_FILES[$fileName]['tmp_name']){
                $data[$fileName] = $this->fileMove('HelpDeskAttachment', $fileName, $this->companyName, 'helpdesk');
            }

            if($this->roleId == 1){
                $data['inUseBy'] = 0;
                $data['inUseTime'] = '';
                $result = $this->loginModel->recordAjaxUpdate('id', substr($_SERVER['HTTP_REFERER'], strrpos($_SERVER['HTTP_REFERER'], '/') + 1), ['issueStatus' => $data['issueStatus'], 'updatedby' => $data['updatedby'], 'remarks' => $data['remarks'], 'updateddate' => $data['updateddate'], 'inUseBy' => $data['inUseBy'], 'inUseTime' => $data['inUseTime']], $table);
                
                $currentHelpDeskUserId = $this->myModel->myGet('tbls_helpdesk', 'BaseTbl.*', 'BaseTbl.id = "' .substr($_SERVER['HTTP_REFERER'], strrpos($_SERVER['HTTP_REFERER'], '/') + 1). '"', 'BaseTbl.id', 'asc');
                $currentUser = $this->myModel->myGet('tbls_user', 'BaseTbl.*', 'BaseTbl.id = "' .$currentHelpDeskUserId[0]->userId. '"', 'BaseTbl.id', 'asc');
                    $mailData['from'] = 'RBI';
                    $mailData['subject'] = 'RBI | Help Desk';
                    $mailData['title'] = 'Greetings!';
                    $mailData['message'] = "You Help Desk ticket has been updated. Please check in RBI tool (Help Desk Module).";
                    $mailData['email'] = $currentUser[0]->email;
                    $sendStatus = mailSend($mailData);
                    if($sendStatus == true){
                        $result = 1;
                    }
            }
            if($this->roleId <> 1){
                $query = $this->db->table('tbls_helpdesk as BaseTbl')
                      ->select('BaseTbl.id, BaseTbl.issueStatus')
                      ->where('BaseTbl.id', substr($_SERVER['HTTP_REFERER'], strrpos($_SERVER['HTTP_REFERER'], '/') + 1))
                      ->get();
    
    $count = $query->getNumRows(); // Correct way to get row count in CI4
                if($count === 0){
                    $this->session->setFlashdata('error', 'No record found.');
                }
                if($count > 0){
                    foreach ($query->getResult() as $row){
                        $id = $row->id;
                        $issueStatus = $row->issueStatus;
                    }
                    if($issueStatus <> 'Fixed'){
                        //$data['clientStatus'] = '';
                    }
                }

                $data['inUseBy'] = 0;
                $data['inUseTime'] = '';
                $result = $this->loginModel->recordAjaxUpdate('id', substr($_SERVER['HTTP_REFERER'], strrpos($_SERVER['HTTP_REFERER'], '/') + 1), ['clientStatus' => $data['clientStatus'], 'moduleName' => $data['moduleName'], 'pageName' => $data['pageName'], 'fieldName' => $data['fieldName'], 'description' => $data['description'], 'stepsToReproduceError' => $data['stepsToReproduceError'], 'currentBehaviour' => $data['currentBehaviour'], 'idealScenario' => $data['idealScenario'], 'errorCode' => $data['errorCode'], 'attachment' => $data['attachment'], 'updatedby' => $data['updatedby'], 'updateddate' => $data['updateddate'], 'inUseBy' => $data['inUseBy'], 'inUseTime' => $data['inUseTime']], $table);
            }
            if($this->roleId == 707 || $this->roleId == 708){
                if($data['clientStatus'] == 'Fixed'){
                $currentHelpDeskUserId = $this->myModel->myGet('tbls_helpdesk', 'BaseTbl.*', 'BaseTbl.id = "' .substr($_SERVER['HTTP_REFERER'], strrpos($_SERVER['HTTP_REFERER'], '/') + 1). '"', 'BaseTbl.id', 'asc');
                $currentUser = $this->myModel->myGet('tbls_user', 'BaseTbl.*', 'BaseTbl.id = "' .$currentHelpDeskUserId[0]->userId. '"', 'BaseTbl.id', 'asc');
                    $mailData['from'] = 'RBI';
                    $mailData['subject'] = 'RBI | Help Desk';
                    $mailData['title'] = 'Greetings, Admin!';
                    $mailData['message'] = "An Issue Resolved.";
                    $mailData['email'] = $currentUser[0]->email;
                    $sendStatus = mailSend($mailData);
                    if($sendStatus == true){
                        $result = 1;
                    }
                }
            }
            //$this->session->remove('recordId');

            if($result > 0){
                $this->session->setFlashdata('success', 'Record updated successfully.');
            }
            else{
                $this->session->setFlashdata('error', 'Something went wrong.');
            }
            
            return redirect()->to(__FUNCTION__.'/lookup');
        }

        else{
            $this->accessDenied();
            return;
        }
    }
    ///// ..... The End  helpdesk() Function ..... /////
    

    // The setting() function used for load the setting module..
    public function setting($action = NULL)
    {
        if (!session()->has('userId')) { // Check if the session key exists
            return redirect()->to('/login'); // Redirect to login if session does not exist
        }
        $isLoggedIn = $this->session->get('isLoggedIn'); if(!isset($isLoggedIn) || $isLoggedIn != TRUE){redirect('login');} $found=false; $rolePage = $this->loginModel->rolePageGet($this->roleId); if(!empty($rolePage)){foreach ($rolePage as $res){$pageName = $res->url; $recordLookup = $res->recordLookup; $recordAdd = $res->recordAdd; $recordEdit = $res->recordEdit; $recordView = $res->recordView; $recordDelete = $res->recordDelete; $recordSubmit = $res->recordSubmit; if(__FUNCTION__ == $pageName){ $found=true; break;}}} if($found == false){$this->accessDenied(); return;}

        $functionName = __FUNCTION__;
        $functionNameEdit = __FUNCTION__.'Edit';

        $data['pageTitle'] = $this->loginModel->pageTitleGet(__FUNCTION__);
        $this->global['pageTitle'] = $this->companyProject.' | '.$data['pageTitle'][0]->friendlyName;

        $myAction = '';
        if($action == 'submit'){
            if(explode('/', $_SERVER['HTTP_REFERER'])[4] == __FUNCTION__){
                $myAction = explode('/', $_SERVER['HTTP_REFERER'])[5];
            }
            if(explode('/', $_SERVER['HTTP_REFERER'])[3] == __FUNCTION__){
                $myAction = explode('/', $_SERVER['HTTP_REFERER'])[4];
            }
            if($myAction == 'add'){
                $myAction = 'save';
            }
            if($myAction == 'edit'){
                $myAction = 'update';
            }
        }

        if($action == 'lookup' && $recordLookup == 1){
            $this->accessDenied();
            return;
        }

        else if($action == 'add' && $recordAdd == 1){
            $this->accessDenied();
            return;
        }

        else if($action == 'edit' && $recordEdit == 1){
            $this->loadViews(__FUNCTION__, $this->global, $data, NULL);
        }

        else if($action == 'view' && $recordView == 1){
            $this->accessDenied();
            return;
        }

        else if($action == 'delete' && $recordDelete == 1){
            $this->accessDenied();
            return;
        }

        else if($myAction == 'save' && $recordSubmit == 1){
            $this->accessDenied();
            return;
        }

        else if($myAction == 'update' && $recordSubmit == 1){
            $data = $this->request->getPost();
            if(!$data){
                $this->accessDenied();
                return;
            }
            // foreach ($data as $key => $value)
            // {
            //     if($key == 'remarks'){
            //         continue;
            //     }
            //     $this->form_validation->set_rules($key, ucwords(implode(' ', preg_split('/(?=[A-Z])/', $key))), ['required']);
            //     if($key == 'Something'){
            //         // Custom Rule
            //     }
            // }
            // if($this->form_validation->run() == FALSE){
            //     $this->session->setFlashdata('error', validation_errors());
            // }
            $data['updatedby'] = $this->userId;
            $data['updateddate'] = date($this->dateTimeFormat);
            $result = 0;
            $resultdetail = 0;

            $result = $this->loginModel->recordAjaxUpdate('id', $this->userId, ['updatedby' => $data['updatedby'], 'updateddate' => $data['updateddate'], 'wallpaper' => $data['wallpaper']], 'tbls_user');

            if($result > 0){
                $this->session->setFlashdata('success', 'Settings updated successfully.');
            }
            else{
                $this->session->setFlashdata('error', 'Something went wrong.');
            }
            
            return redirect()->to(__FUNCTION__.'/edit');
        }

        else{
            $this->accessDenied();
            return;
        }
    }
    ///// ..... The End  setting() Function ..... /////
    
    // The profile() function used for load the profile module..
    public function profile($action = NULL)
    {  
        if (!session()->has('userId')) { // Check if the session key exists
            return redirect()->to('/login'); // Redirect to login if session does not exist
        }

        $isLoggedIn = $this->session->get('isLoggedIn'); if(!isset($isLoggedIn) || $isLoggedIn != TRUE){redirect('login');} $found=false; $rolePage = $this->loginModel->rolePageGet($this->roleId); if(!empty($rolePage)){foreach ($rolePage as $res){$pageName = $res->url; $recordLookup = $res->recordLookup; $recordAdd = $res->recordAdd; $recordEdit = $res->recordEdit; $recordView = $res->recordView; $recordDelete = $res->recordDelete; $recordSubmit = $res->recordSubmit; if(__FUNCTION__ == $pageName){ $found=true; break;}}} if($found == false){$this->accessDenied(); return;}

        $functionName = __FUNCTION__;
        $functionNameEdit = __FUNCTION__.'Edit';

        $data['pageTitle'] = $this->loginModel->pageTitleGet(__FUNCTION__);
        $this->global['pageTitle'] = $this->companyProject.' | '.$data['pageTitle'][0]->friendlyName;

        $myAction = '';
        if($action == 'submit'){
            if(explode('/', $_SERVER['HTTP_REFERER'])[4] == __FUNCTION__){
                $myAction = explode('/', $_SERVER['HTTP_REFERER'])[5];
            }
            if(explode('/', $_SERVER['HTTP_REFERER'])[3] == __FUNCTION__){
                $myAction = explode('/', $_SERVER['HTTP_REFERER'])[4];
            }
            if($myAction == 'add'){
                $myAction = 'save';
            }
            if($myAction == 'edit'){
                $myAction = 'update';
            }
        }

        $table = 'tbls_user';
        $data['records'] = $this->loginModel->userEdit($this->userId, $table);
        $data['country'] = $this->loginModel->countryGet();

        if($action == 'lookup' && $recordLookup == 1){
            $this->accessDenied();
            return;
        }

        else if($action == 'add' && $recordAdd == 1){
            $this->accessDenied();
            return;
        }

        else if($action == 'edit' && $recordEdit == 1){
            $this->loadViews(__FUNCTION__, $this->global, $data, NULL);
        }

        else if($action == 'view' && $recordView == 1){
            $this->accessDenied();
            return;
        }

        else if($action == 'delete' && $recordDelete == 1){
            $this->accessDenied();
            return;
        }

        else if($myAction == 'save' && $recordSubmit == 1){
            $this->accessDenied();
            return;
        }

        else if($myAction == 'update' && $recordSubmit == 1){
            $data = $this->request->getPost();
            if(!$data){
                $this->accessDenied();
                return;
            }
            $data['updatedby'] = $this->userId;
            $data['updateddate'] = date($this->dateTimeFormat);
            $result = 0;
            $resultdetail = 0;

            $passwordTyped = false;
            if($data['password'] == $data['cpassword']){

                $fileName = 'profilepic';
                if(@$_FILES[$fileName]['tmp_name']){
                    $data[$fileName] = $this->fileMove('User', $fileName, $this->companyName, 'profilepic');
                }
                
                if($data['email'] <> $this->userEmail){
                    $passwordTyped = true;
                }

                if($data['password']){
                    if($data['oldpassword'] == $_SESSION['plainPassword']){
                        $data['password'] = getHashedPassword($data['password']);
                        unset($data['cpassword']);
                        $passwordTyped = true;
                        $result = $this->loginModel->recordAjaxUpdate('id', $this->userId, ['updatedby' => $data['updatedby'], 'updateddate' => $data['updateddate'], 'userName' => $data['userName'], 'password' => $data['password'], 'profilepic' => $data['profilepic'], 'pin' => $data['pin'], 'email' => $data['email'], 'userVerify' => 1], 'tbls_user');
                    }
                    else{
                        $result = 0;
                    }
                }
                else{
                    unset($data['password']);
                    unset($data['cpassword']);
                    $result = $this->loginModel->recordAjaxUpdate('id', $this->userId, ['updatedby' => $data['updatedby'], 'updateddate' => $data['updateddate'], 'userName' => $data['userName'], 'profilepic' => $data['profilepic'], 'pin' => $data['pin'], 'email' => $data['email'], 'userVerify' => 1], 'tbls_user');
                }
            }
            else{
                $result = 0;
            }

            if($result > 0){
                $this->session->setFlashdata('success', 'Profile updated successfully.');
            }
            else{
                $this->session->setFlashdata('error', 'Something went wrong.');
            }
            
            if($passwordTyped == true){
                $this->loginModel->onlineStatusUpdate($this->session->get('userId'), array('isOnline' => 0));
                $this->loginModel->auditLogSave(['userId' => $this->session->get('userId'), 'logoutTime' => date($this->dateTimeFullFormat), 'ipAddress'=>$_SERVER['REMOTE_ADDR']]);
                $this->session->destroy();
                sleep(1);
                return redirect()->to('login');
            }
            else{
                return redirect()->to(__FUNCTION__.'/edit');
            }
        }

        else{
            $this->accessDenied();
            return;
        }
    }
    ///// ..... The End  profile() Function ..... /////
    
    // The user($action = NULL, $id = NULL) function used for load the user module only...
    // $action : help add, edit, submit, delete.
    // $id : which row id update.
    public function user($action = NULL, $id = NULL)
    {   
        if (!session()->has('userId')) { // Check if the session key exists
            return redirect()->to('/login'); // Redirect to login if session does not exist
        }

        $isLoggedIn = $this->session->get('isLoggedIn'); if(!isset($isLoggedIn) || $isLoggedIn != TRUE){redirect('login');} $found=false; $rolePage = $this->loginModel->rolePageGet($this->roleId); if(!empty($rolePage)){foreach ($rolePage as $res){$pageName = $res->url; $recordLookup = $res->recordLookup; $recordAdd = $res->recordAdd; $recordEdit = $res->recordEdit; $recordView = $res->recordView; $recordDelete = $res->recordDelete; $recordSubmit = $res->recordSubmit; if(__FUNCTION__ == $pageName){ $found=true; break;}}} if($found == false){$this->accessDenied(); return;}

        $functionName = __FUNCTION__;
        $functionNameEdit = __FUNCTION__.'Edit';

        $data['pageTitle'] = $this->loginModel->pageTitleGet(__FUNCTION__);
        $this->global['pageTitle'] = $this->companyProject.' | '.$data['pageTitle'][0]->friendlyName;

        $myAction = '';
        if($action == 'submit'){
            if(explode('/', $_SERVER['HTTP_REFERER'])[4] == __FUNCTION__){
                $myAction = explode('/', $_SERVER['HTTP_REFERER'])[5];
            }
            if(explode('/', $_SERVER['HTTP_REFERER'])[3] == __FUNCTION__){
                $myAction = explode('/', $_SERVER['HTTP_REFERER'])[4];
            }
            if($myAction == 'add'){
                $myAction = 'save';
            }
            if($myAction == 'edit'){
                $myAction = 'update';
            }
        }

        $table = 'tbls_'.__FUNCTION__;
        $searchText = $this->request->getPost('searchText');
        $data['searchText'] = $searchText;
        $data['records'] = $this->loginModel->$functionName($table, $searchText);
        $data['recordsEdit'] = $this->loginModel->$functionNameEdit($id, $table);
        $data['country'] = $this->loginModel->countryGet();
        $data['role'] = $this->loginModel->roleGet();
        $data['state'] = $this->loginModel->stateGet();
        $data['city'] = $this->loginModel->cityGet();

        if($action == 'lookup' && $recordLookup == 1){
            $this->loadViews(__FUNCTION__, $this->global, $data, NULL);
        }

        else if($action == 'add' && $recordAdd == 1){
            $this->loadViews(__FUNCTION__, $this->global, $data, NULL);
        }

        else if($action == 'edit' && $recordEdit == 1){
            if(!$id){
                $this->accessDenied();
                return;
            }
            
            $this->loadViews(__FUNCTION__, $this->global, $data, NULL);
        }

        else if($action == 'view' && $recordView == 1){
            if(!$id){
                $this->accessDenied();
                return;
            }
            
            $this->loadViews(__FUNCTION__, $this->global, $data, NULL);
        }

        else if($action == 'delete' && $recordDelete == 1){
            $data = array('isDeleted'=>1, 'updateddate'=>date($this->dateTimeFormat), 'updatedby'=>$this->userId);

            if($id <> 1){
                $result = $this->loginModel->recordAjaxUpdate('id', $id, $data, $table);
            }

            if($result > 0){
                $this->session->setFlashdata('success', 'Record deleted successfully.');
            }
            else{
                $this->session->setFlashdata('error', 'Something went wrong.');
            }

            return redirect()->to(__FUNCTION__.'/lookup');
        }

        else if($myAction == 'save' && $recordSubmit == 1){
            $data = $this->request->getPost();
            if(!$data){
                $this->accessDenied();
                return;
            }
            $data['createdby'] = $this->userId;
            $data['createddate'] = date($this->dateTimeFormat);
            $result = 0;
            $resultdetail = 0;
            //$data['email'] = $data['email'].'@'.$this->companyEmail;
            $data['wallpaper'] = 'wp1.jpg';
            if($data['password'] == $data['cpassword']){
                if($data['password']){
                    $data['password'] = getHashedPassword($data['password']);
                    unset($data['cpassword']);
                }
                else{
                    unset($data['password']);
                    unset($data['cpassword']);
                }

                $fileName = 'profilepic';
                $data[$fileName] = 'avatar5.png';
                if(@$_FILES[$fileName]['tmp_name']){
                    $data[$fileName] = $this->fileMove('User', $fileName, $this->companyName, 'profilepic');
                }

                if($this->roleId == 705 || $this->roleId == 706){
                    $result = $this->loginModel->recordAjaxSave(['email' => $data['email'], 'password' => $data['password'], 'userName' => $data['userName'], 'roleId' => $this->roleId, 'countryId' => $this->countryId, 'profilepic' => $data['profilepic'], 'startDate' => $data['startDate'], 'endDate' => $data['endDate'], 'resumeDate' => $data['resumeDate'], 'phone' => $data['phone'], 'address' => $data['address'], 'groupEmail' => $this->groupEmail, 'remarks' => $data['remarks'], 'status' => $data['status'], 'wallpaper' => 'wp1.jpg', 'createdby' => $data['createdby'], 'createddate' => $data['createddate']], $table);
                }
                if($this->roleId <> 705 && $this->roleId <> 706){
                    $password = $this->generateRandomString(5);
                    $hashedPassword = getHashedPassword($password);
                    $result = $this->loginModel->recordAjaxSave(['email' => $data['email'], 'password' => empty($hashedPassword) ? $data['password'] : '', 'userName' => $data['userName'], 'roleId' => $data['roleId'], 'countryId' => $data['countryId'], 'profilepic' => $data['profilepic'], 'startDate' => $data['startDate'], 'endDate' => $data['endDate'], 'resumeDate' => $data['resumeDate'], 'phone' => $data['phone'], 'address' => $data['address'], 'remarks' => $data['remarks'], 'status' => $data['status'], 'wallpaper' => 'wp1.jpg', 'createdby' => $data['createdby'], 'createddate' => $data['createddate']], $table);
                }
            }
            else{
                $result = 0;
            }

            if($result > 0){
                $this->session->setFlashdata('success', 'New record saved successfully.');
            }
            else{
                $this->session->setFlashdata('error', 'Something went wrong.');
            }

            return redirect()->to(__FUNCTION__.'/lookup');
        }

        else if($myAction == 'update' && $recordSubmit == 1){
            $data = $this->request->getPost();
            if(!$data){
                $this->accessDenied();
                return;
            }
            $data['updatedby'] = $this->userId;
            $data['updateddate'] = date($this->dateTimeFormat);
            $result = 0;
            $resultdetail = 0;

            //$data['email'] = $data['email'].'@'.$this->companyEmail;
            if(isset($data['roleId']) && ($data['roleId'] == 705 || $data['roleId'] == 706 || $data['roleId'] == 709)){
                if($data['status'] == 'Active'){
                    $currentUser = $this->myModel->myGet('tbls_user', 'BaseTbl.*', 'BaseTbl.email = "' .$data['email']. '"', 'BaseTbl.id', 'asc');
                    if($currentUser[0]->status == 'Pending' || $currentUser[0]->status == NULL){
                    $password = $this->generateRandomString(5);
                    $hashedPassword = getHashedPassword($password);
                    unset($data['cpassword']);
                    $passwordTyped = true;
                    $result = $this->loginModel->recordAjaxUpdate('id', substr($_SERVER['HTTP_REFERER'], strrpos($_SERVER['HTTP_REFERER'], '/') + 1), ['email' => $data['email'], 'password' => $hashedPassword, 'userName' => $data['userName'], 'roleId' => $data['roleId'], 'countryId' => $data['countryId'], 'profilepic' => $data['profilepic'], 'startDate' => $data['startDate'], 'endDate' => $data['endDate'], 'resumeDate' => $data['resumeDate'], 'phone' => $data['phone'], 'address' => $data['address'], 'remarks' => $data['remarks'], 'status' => $data['status'], 'updatedby' => $data['updatedby'], 'updateddate' => $data['updateddate']], $table);
                    
                    $mailData['from'] = 'RBI';
                    $mailData['subject'] = 'RBI | Account Information';
                    $mailData['title'] = 'Greetings, '.$data['userName'].'!';
                    $mailData['message'] = "Your account has been activated. \r\n Please Sign In to your RBI Account using this Email: ".$data['email']."\r\n and Password: ".$password.'<br>'."";
                    $mailData['email'] = $data['email'];
                    $sendStatus = mailSend($mailData);
                    }
                    else{
                        $passwordTyped = false;
                if($data['password'] == $data['cpassword']){

                    $fileName = 'profilepic';
                    if(@$_FILES[$fileName]['tmp_name']){
                        $data[$fileName] = $this->fileMove('User', $fileName, $this->companyName, 'profilepic');
                    }

                    $data['inUseBy'] = 0;
                    $data['inUseTime'] = '';

                    if($data['password']){
                        $data['password'] = getHashedPassword($data['password']);
                        unset($data['cpassword']);
                        $passwordTyped = true;
                        $result = $this->loginModel->recordAjaxUpdate('id', substr($_SERVER['HTTP_REFERER'], strrpos($_SERVER['HTTP_REFERER'], '/') + 1), ['email' => $data['email'], 'password' => $data['password'], 'userName' => $data['userName'], 'roleId' => $data['roleId'], 'countryId' => $data['countryId'], 'profilepic' => $data['profilepic'], 'startDate' => $data['startDate'], 'endDate' => $data['endDate'], 'resumeDate' => $data['resumeDate'], 'phone' => $data['phone'], 'address' => $data['address'], 'remarks' => $data['remarks'], 'status' => $data['status'], 'updatedby' => $data['updatedby'], 'updateddate' => $data['updateddate']], $table);
                    }
                    else{
                        unset($data['password']);
                        unset($data['cpassword']);
                        $result = $this->loginModel->recordAjaxUpdate('id', substr($_SERVER['HTTP_REFERER'], strrpos($_SERVER['HTTP_REFERER'], '/') + 1), ['email' => $data['email'], 'userName' => $data['userName'], 'roleId' => $data['roleId'], 'countryId' => $data['countryId'], 'profilepic' => $data['profilepic'], 'startDate' => $data['startDate'], 'endDate' => $data['endDate'], 'resumeDate' => $data['resumeDate'], 'phone' => $data['phone'], 'address' => $data['address'], 'remarks' => $data['remarks'], 'status' => $data['status'], 'updatedby' => $data['updatedby'], 'updateddate' => $data['updateddate']], $table);
                    }
                }
                else{
                    $result = 0;
                }
                    }
                }
                if($data['status'] == 'Rejected'){
                    $mailData['from'] = 'RBI';
                    $mailData['subject'] = 'RBI | Account Information';
                    $mailData['title'] = 'Greetings, '.$data['userName'].'!';
                    $mailData['message'] = "Your account has been rejected. \r\n ".$data['remarks'];
                    $mailData['email'] = $data['email'];
                    $sendStatus = mailSend($mailData);
                }
            }
             else if(isset($this->roleId) && ($this->roleId == 705 || $this->roleId == 706 || $data['roleId'] == 709)){
                if($data['status'] == 'Active'){
                    $currentUser = $this->myModel->myGet('tbls_user', 'BaseTbl.*', 'BaseTbl.email = "' .$data['email']. '"', 'BaseTbl.id', 'asc');
                    if($currentUser[0]->status == 'Pending'){
                    $password = $this->generateRandomString(5);
                    $hashedPassword = getHashedPassword($password);
                    unset($data['cpassword']);
                    $passwordTyped = true;
                    $result = $this->loginModel->recordAjaxUpdate('id', substr($_SERVER['HTTP_REFERER'], strrpos($_SERVER['HTTP_REFERER'], '/') + 1), ['email' => $data['email'], 'password' => $hashedPassword, 'userName' => $data['userName'], 'roleId' => $this->roleId, 'countryId' => $this->countryId, 'profilepic' => $data['profilepic'], 'startDate' => $data['startDate'], 'endDate' => $data['endDate'], 'resumeDate' => $data['resumeDate'], 'phone' => $data['phone'], 'address' => $data['address'], 'remarks' => $data['remarks'], 'status' => $data['status'], 'updatedby' => $data['updatedby'], 'updateddate' => $data['updateddate']], $table);
                    
                    $mailData['from'] = 'RBI';
                    $mailData['subject'] = 'RBI | Account Information';
                    $mailData['title'] = 'Greetings, '.$data['userName'].'!';
                    $mailData['message'] = "Your account has been activated. \r\n Please Sign In to your RBI Account using this Email: ".$data['email']."\r\n and Password: ".$password.'<br>'."";
                    $mailData['email'] = $data['email'];
                    $sendStatus = mailSend($mailData);
                }
                
                    else{
                        $passwordTyped = false;
                if($data['password'] == $data['cpassword']){

                    $fileName = 'profilepic';
                    if(@$_FILES[$fileName]['tmp_name']){
                        $data[$fileName] = $this->fileMove('User', $fileName, $this->companyName, 'profilepic');
                    }

                    $data['inUseBy'] = 0;
                    $data['inUseTime'] = '';

                    if($data['password']){
                        $data['password'] = getHashedPassword($data['password']);
                        unset($data['cpassword']);
                        $passwordTyped = true;
                        $result = $this->loginModel->recordAjaxUpdate('id', substr($_SERVER['HTTP_REFERER'], strrpos($_SERVER['HTTP_REFERER'], '/') + 1), ['email' => $data['email'], 'password' => $data['password'], 'userName' => $data['userName'], 'roleId' => $data['roleId'], 'countryId' => $data['countryId'], 'profilepic' => $data['profilepic'], 'startDate' => $data['startDate'], 'endDate' => $data['endDate'], 'resumeDate' => $data['resumeDate'], 'phone' => $data['phone'], 'address' => $data['address'], 'remarks' => $data['remarks'], 'status' => $data['status'], 'updatedby' => $data['updatedby'], 'updateddate' => $data['updateddate']], $table);
                    }
                    else{
                        unset($data['password']);
                        unset($data['cpassword']);
                        $result = $this->loginModel->recordAjaxUpdate('id', substr($_SERVER['HTTP_REFERER'], strrpos($_SERVER['HTTP_REFERER'], '/') + 1), ['email' => $data['email'], 'userName' => $data['userName'], 'roleId' => $data['roleId'], 'countryId' => $data['countryId'], 'profilepic' => $data['profilepic'], 'startDate' => $data['startDate'], 'endDate' => $data['endDate'], 'resumeDate' => $data['resumeDate'], 'phone' => $data['phone'], 'address' => $data['address'], 'remarks' => $data['remarks'], 'status' => $data['status'], 'updatedby' => $data['updatedby'], 'updateddate' => $data['updateddate']], $table);
                    }
                }
                else{
                    $result = 0;
                }
                    }
                    
            }
            if($data['status'] == 'Rejected'){
                    $mailData['from'] = 'RBI';
                    $mailData['subject'] = 'RBI | Account Information';
                    $mailData['title'] = 'Greetings, '.$data['userName'].'!';
                    $mailData['message'] = "Your account has been rejected. \r\n ".$data['remarks'];
                    $mailData['email'] = $data['email'];
                    $sendStatus = mailSend($mailData);
                }
             }
            else{
                $passwordTyped = false;
                if($data['password'] == $data['cpassword']){

                    $fileName = 'profilepic';
                    if(@$_FILES[$fileName]['tmp_name']){
                        $data[$fileName] = $this->fileMove('User', $fileName, $this->companyName, 'profilepic');
                    }

                    $data['inUseBy'] = 0;
                    $data['inUseTime'] = '';

                    if($data['password']){
                        $data['password'] = getHashedPassword($data['password']);
                        unset($data['cpassword']);
                        $passwordTyped = true;
                        $result = $this->loginModel->recordAjaxUpdate('id', substr($_SERVER['HTTP_REFERER'], strrpos($_SERVER['HTTP_REFERER'], '/') + 1), ['email' => $data['email'], 'password' => $data['password'], 'userName' => $data['userName'], 'roleId' => $data['roleId'], 'countryId' => $data['countryId'], 'profilepic' => $data['profilepic'], 'startDate' => $data['startDate'], 'endDate' => $data['endDate'], 'resumeDate' => $data['resumeDate'], 'phone' => $data['phone'], 'address' => $data['address'], 'remarks' => $data['remarks'], 'status' => $data['status'], 'updatedby' => $data['updatedby'], 'updateddate' => $data['updateddate']], $table);
                    }
                    else{
                        unset($data['password']);
                        unset($data['cpassword']);
                        $result = $this->loginModel->recordAjaxUpdate('id', substr($_SERVER['HTTP_REFERER'], strrpos($_SERVER['HTTP_REFERER'], '/') + 1), ['email' => $data['email'], 'userName' => $data['userName'], 'roleId' => $data['roleId'], 'countryId' => $data['countryId'], 'profilepic' => $data['profilepic'], 'startDate' => $data['startDate'], 'endDate' => $data['endDate'], 'resumeDate' => $data['resumeDate'], 'phone' => $data['phone'], 'address' => $data['address'], 'remarks' => $data['remarks'], 'status' => $data['status'], 'updatedby' => $data['updatedby'], 'updateddate' => $data['updateddate']], $table);
                    }
                }
                else{
                    $result = 0;
                }
            }

            if($result > 0){
                $this->session->setFlashdata('success', 'Record updated successfully.');
            }
            else{
                $this->session->setFlashdata('error', 'Something went wrong.');
            }
            
            if(@$passwordTyped == true && substr($_SERVER['HTTP_REFERER'], strrpos($_SERVER['HTTP_REFERER'], '/') + 1) == $this->userId){
                $this->session->remove('recordId');
                $this->loginModel->onlineStatusUpdate($this->session->get('userId'), array('isOnline' => 0));
                $this->loginModel->auditLogSave(['userId' => $this->session->get('userId'), 'logoutTime' => date($this->dateTimeFullFormat), 'ipAddress'=>$_SERVER['REMOTE_ADDR']]);
                $this->session->destroy();
                sleep(1);
                return redirect()->to('login');
            }
            else{
                return redirect()->to(__FUNCTION__.'/lookup');
            }
        }

        else{
            $this->accessDenied();
            return;
        }
    }
    ///// ..... The End  user() Function ..... /////
    

    // The auditLog function is used to track which user logs in or logs out of the system, as well as which modules are opened and when data is inserted or updated.
    public function auditlog($action = NULL, $id = NULL)
    {
        if (!session()->has('userId')) { // Check if the session key exists
            return redirect()->to('/login'); // Redirect to login if session does not exist
        }
        $isLoggedIn = $this->session->get('isLoggedIn'); if(!isset($isLoggedIn) || $isLoggedIn != TRUE){redirect('login');} $found=false; $rolePage = $this->loginModel->rolePageGet($this->roleId); if(!empty($rolePage)){foreach ($rolePage as $res){$pageName = $res->url; $recordLookup = $res->recordLookup; $recordAdd = $res->recordAdd; $recordEdit = $res->recordEdit; $recordView = $res->recordView; $recordDelete = $res->recordDelete; $recordSubmit = $res->recordSubmit; if(__FUNCTION__ == $pageName){ $found=true; break;}}} if($found == false){$this->accessDenied(); return;}

        $functionName = __FUNCTION__;
        $functionNameEdit = __FUNCTION__.'Edit';

        $data['pageTitle'] = $this->loginModel->pageTitleGet(__FUNCTION__);
        $this->global['pageTitle'] = $this->companyProject.' | '.$data['pageTitle'][0]->friendlyName;

        $myAction = '';
        if($action == 'submit'){
            if(explode('/', $_SERVER['HTTP_REFERER'])[4] == __FUNCTION__){
                $myAction = explode('/', $_SERVER['HTTP_REFERER'])[5];
            }
            if(explode('/', $_SERVER['HTTP_REFERER'])[3] == __FUNCTION__){
                $myAction = explode('/', $_SERVER['HTTP_REFERER'])[4];
            }
            if($myAction == 'add'){
                $myAction = 'save';
            }
            if($myAction == 'edit'){
                $myAction = 'update';
            }
        }

        $table = 'tbls_'.__FUNCTION__;
        $searchText = $this->request->getPost('searchText');
        $data['searchText'] = $searchText;
        $data['records'] = $this->loginModel->$functionName($table, $searchText);
        $data['recordsEdit'] = $this->loginModel->$functionNameEdit($id, $table);

        if($action == 'lookup' && $recordLookup == 1){
            $this->loadViews(__FUNCTION__, $this->global, $data, NULL);
        }

        else if($action == 'add' && $recordAdd == 1){
            $this->accessDenied();
            return;
        }

        else if($action == 'edit' && $recordEdit == 1){
            if(!$id){
                $this->accessDenied();
                return;
            }
            $this->accessDenied();
            return;
        }

        else if($action == 'view' && $recordView == 1){
            if(!$id){
                $this->accessDenied();
                return;
            }
            //$this->session->set_userdata(array('recordId' =>$id));
            
            $this->loadViews(__FUNCTION__, $this->global, $data, NULL);
        }

        else if($action == 'delete' && $recordDelete == 1){
            $this->accessDenied();
            return;
        }

        else if($myAction == 'save' && $recordSubmit == 1){
            $this->accessDenied();
            return;
        }

        else if($myAction == 'update' && $recordSubmit == 1){
            $this->accessDenied();
            return;
        }

        else{
            $this->accessDenied();
            return;
        }
    }
    ///// ..... The End  auditlog() Function ..... /////
    
    // The alert function is used to show system alerts only....
    public function alert($action = NULL, $id = NULL)
    {
        if (!session()->has('userId')) { // Check if the session key exists
            return redirect()->to('/login'); // Redirect to login if session does not exist
        }
        $isLoggedIn = $this->session->get('isLoggedIn'); if(!isset($isLoggedIn) || $isLoggedIn != TRUE){redirect('login');} $found=false; $rolePage = $this->loginModel->rolePageGet($this->roleId); if(!empty($rolePage)){foreach ($rolePage as $res){$pageName = $res->url; $recordLookup = $res->recordLookup; $recordAdd = $res->recordAdd; $recordEdit = $res->recordEdit; $recordView = $res->recordView; $recordDelete = $res->recordDelete; $recordSubmit = $res->recordSubmit; if(__FUNCTION__ == $pageName){ $found=true; break;}}} if($found == false){$this->accessDenied(); return;}

        $functionName = __FUNCTION__;
        $functionNameEdit = __FUNCTION__.'Edit';

        $data['pageTitle'] = $this->loginModel->pageTitleGet(__FUNCTION__);
        $this->global['pageTitle'] = $this->companyProject.' | '.$data['pageTitle'][0]->friendlyName;

        $myAction = '';
        if($action == 'submit'){
            if(explode('/', $_SERVER['HTTP_REFERER'])[4] == __FUNCTION__){
                $myAction = explode('/', $_SERVER['HTTP_REFERER'])[5];
            }
            if(explode('/', $_SERVER['HTTP_REFERER'])[3] == __FUNCTION__){
                $myAction = explode('/', $_SERVER['HTTP_REFERER'])[4];
            }
            if($myAction == 'add'){
                $myAction = 'save';
            }
            if($myAction == 'edit'){
                $myAction = 'update';
            }
        }

        $table = 'tbls_'.__FUNCTION__;
        $searchText = $this->request->getPost('searchText');
        $data['searchText'] = $searchText;
        $data['records'] = $this->loginModel->$functionName($table, $searchText);
        $data['recordsEdit'] = $this->loginModel->$functionNameEdit($id, $table);
        $data['user'] = $this->loginModel->userGet($this->roleId);

        if($action == 'lookup' && $recordLookup == 1){
            $this->loadViews(__FUNCTION__, $this->global, $data, NULL);
        }

        else if($action == 'add' && $recordAdd == 1){
            $this->loadViews(__FUNCTION__, $this->global, $data, NULL);
        }

        else if($action == 'edit' && $recordEdit == 1){
            if(!$id){
                $this->accessDenied();
                return;
            }
            
            $this->loadViews(__FUNCTION__, $this->global, $data, NULL);
        }

        else if($action == 'view' && $recordView == 1){
            if(!$id){
                $this->accessDenied();
                return;
            }
            
            $this->loadViews(__FUNCTION__, $this->global, $data, NULL);
        }

        else if($action == 'delete' && $recordDelete == 1){
            $data = array('isDeleted'=>1, 'updateddate'=>date($this->dateTimeFormat), 'updatedby'=>$this->userId);

            $result = $this->loginModel->recordAjaxUpdate('id', $id, $data, $table);

            if($result > 0){
                $this->session->setFlashdata('success', 'Record deleted successfully.');
            }
            else{
                $this->session->setFlashdata('error', 'Something went wrong.');
            }

            return redirect()->to(__FUNCTION__.'/lookup');
        }

        else if($myAction == 'save' && $recordSubmit == 1){
            $data = $this->request->getPost();
            if(!$data){
                $this->accessDenied();
                return;
            }
            // foreach ($data as $key => $value)
            // {
            //     if($key == 'remarks'){
            //         continue;
            //     }
            //     $this->form_validation->set_rules($key, ucwords(implode(' ', preg_split('/(?=[A-Z])/', $key))), ['required']);
            //     if($key == 'Something'){
            //         // Custom Rule
            //     }
            // }
            // if($this->form_validation->run() == FALSE){
            //     $this->session->setFlashdata('error', validation_errors());
            // }
            $data['dateTime'] = date($this->dateTimeFormat, strtotime($this->request->getPost('dateTime')));
            $data['createdby'] = $this->userId;
            $data['createddate'] = date($this->dateTimeFormat);
            $result = 0;
            $resultdetail = 0;

            $result = $this->loginModel->recordAjaxSave($data, $table);

            if($result > 0){
                $this->session->setFlashdata('success', 'New record saved successfully.');
            }
            else{
                $this->session->setFlashdata('error', 'Something went wrong.');
            }

            return redirect()->to(__FUNCTION__.'/lookup');
        }

        else if($myAction == 'update' && $recordSubmit == 1){
            $data = $this->request->getPost();
            if(!$data){
                $this->accessDenied();
                return;
            }
            // foreach ($data as $key => $value)
            // {
            //     if($key == 'remarks'){
            //         continue;
            //     }
            //     $this->form_validation->set_rules($key, ucwords(implode(' ', preg_split('/(?=[A-Z])/', $key))), ['required']);
            //     if($key == 'Something'){
            //         // Custom Rule
            //     }
            // }
            // if($this->form_validation->run() == FALSE){
            //     $this->session->setFlashdata('error', validation_errors());
            // }
            $data['updatedby'] = $this->userId;
            $data['updateddate'] = date($this->dateTimeFormat);
            $result = 0;
            $resultdetail = 0;

            $data['inUseBy'] = 0;
            $data['inUseTime'] = '';
            $result = $this->loginModel->recordAjaxUpdate('id', substr($_SERVER['HTTP_REFERER'], strrpos($_SERVER['HTTP_REFERER'], '/') + 1), $data, $table);
            //$this->session->remove('recordId');

            if($result > 0){
                $this->session->setFlashdata('success', 'Record updated successfully.');
            }
            else{
                $this->session->setFlashdata('error', 'Something went wrong.');
            }
            
            return redirect()->to(__FUNCTION__.'/lookup');
        }

        else{
            $this->accessDenied();
            return;
        }
    }
    ///// ..... The End  alert() Function ..... /////
    
    // The alerts function is used to show system alerts only....
    public function alerts($action = NULL)
    {
        if (!session()->has('userId')) { // Check if the session key exists
            return redirect()->to('/login'); // Redirect to login if session does not exist
        }
        $isLoggedIn = $this->session->get('isLoggedIn'); if(!isset($isLoggedIn) || $isLoggedIn != TRUE){redirect('login');} $found=false; $rolePage = $this->loginModel->rolePageGet($this->roleId); if(!empty($rolePage)){foreach ($rolePage as $res){$pageName = $res->url; $recordLookup = $res->recordLookup; $recordAdd = $res->recordAdd; $recordEdit = $res->recordEdit; $recordView = $res->recordView; $recordDelete = $res->recordDelete; $recordSubmit = $res->recordSubmit; if(__FUNCTION__ == $pageName){ $found=true; break;}}} if($found == false){$this->accessDenied(); return;}

        $functionName = __FUNCTION__;
        $functionNameEdit = __FUNCTION__.'Edit';

        $data['pageTitle'] = $this->loginModel->pageTitleGet(__FUNCTION__);
        $this->global['pageTitle'] = $this->companyProject.' | '.$data['pageTitle'][0]->friendlyName;

        $myAction = '';
        if($action == 'submit'){
            // if(explode('/', $_SERVER['HTTP_REFERER'])[4] == __FUNCTION__){
            //     $myAction = explode('/', $_SERVER['HTTP_REFERER'])[5];
            // }
            // if(explode('/', $_SERVER['HTTP_REFERER'])[3] == __FUNCTION__){
            //     $myAction = explode('/', $_SERVER['HTTP_REFERER'])[4];
            // }
            // if($myAction == 'add'){
            //     $myAction = 'save';
            // }
            // if($myAction == 'edit'){
                $myAction = 'update';
            // }
        }

        $table = 'tbls_alert';
        $searchText = $this->request->getPost('searchText');
        $data['searchText'] = $searchText;
        $data['records'] = $this->loginModel->$functionName($table, $searchText);

        if($action == 'lookup' && $recordLookup == 1){
            $this->loadViews(__FUNCTION__, $this->global, $data, NULL);
        }

        else if($action == 'add' && $recordAdd == 1){
            $this->accessDenied();
            return;
        }

        else if($action == 'edit' && $recordEdit == 1){
            if(!$id){
                $this->accessDenied();
                return;
            }
            $this->accessDenied();
            return;
        }

        else if($action == 'view' && $recordView == 1){
            if(!$id){
                $this->accessDenied();
                return;
            }
            $this->accessDenied();
            return;
        }

        else if($action == 'delete' && $recordDelete == 1){
            $this->accessDenied();
            return;
        }

        else if($myAction == 'save' && $recordSubmit == 1){
            $this->accessDenied();
            return;
        }

        else if($myAction == 'update' && $recordSubmit == 1){
            $data = $this->request->getPost();
            if(!$data){
                $this->accessDenied();
                return;
            }
            // foreach ($data as $key => $value)
            // {
            //     if($key == 'remarks'){
            //         continue;
            //     }
            //     $this->form_validation->set_rules($key, ucwords(implode(' ', preg_split('/(?=[A-Z])/', $key))), ['required']);
            //     if($key == 'Something'){
            //         // Custom Rule
            //     }
            // }
            // if($this->form_validation->run() == FALSE){
            //     $this->session->setFlashdata('error', validation_errors());
            // }
            $data['updatedby'] = $this->userId;
            $data['updateddate'] = date($this->dateTimeFormat);
            $result = 0;
            $resultdetail = 0;

            $data['inUseBy'] = 0;
            $data['inUseTime'] = '';
            $result = $this->loginModel->recordAjaxUpdate('id', $data['id'], ['isCompleted' => 1, 'status' => 'Inactive', 'inUseBy' => $data['inUseBy'], 'inUseTime' => $data['inUseTime'], 'updatedby' => $data['updatedby'], 'updateddate' => $data['updateddate']], $table);

            if($result > 0){
                $this->session->setFlashdata('success', 'Record updated successfully.');
            }
            else{
                $this->session->setFlashdata('error', 'Something went wrong.');
            }
            
            redirect(__FUNCTION__.'/edit');
        }

        else{
            $this->accessDenied();
            return;
        }
    }
    ///// ..... The End  alerts() Function ..... /////
    

    // The userguide function is used to load the userguide module and run the all actions like add, edit, listing, delete.
    public function userguide($action = NULL)
    {
        if (!session()->has('userId')) { // Check if the session key exists
            return redirect()->to('/login'); // Redirect to login if session does not exist
        }
        $isLoggedIn = $this->session->get('isLoggedIn'); if(!isset($isLoggedIn) || $isLoggedIn != TRUE){redirect('login');} $found=false; $rolePage = $this->loginModel->rolePageGet($this->roleId); if(!empty($rolePage)){foreach ($rolePage as $res){$pageName = $res->url; $recordLookup = $res->recordLookup; $recordAdd = $res->recordAdd; $recordEdit = $res->recordEdit; $recordView = $res->recordView; $recordDelete = $res->recordDelete; $recordSubmit = $res->recordSubmit; if(__FUNCTION__ == $pageName){ $found=true; break;}}} if($found == false){$this->accessDenied(); return;}

        $functionName = __FUNCTION__;
        $functionNameEdit = __FUNCTION__.'Edit';

        $data['pageTitle'] = $this->loginModel->pageTitleGet(__FUNCTION__);
        $this->global['pageTitle'] = $this->companyProject.' | '.$data['pageTitle'][0]->friendlyName;

        $myAction = '';
        if($action == 'submit'){
            // if(explode('/', $_SERVER['HTTP_REFERER'])[4] == __FUNCTION__){
            //     $myAction = explode('/', $_SERVER['HTTP_REFERER'])[5];
            // }
            // if(explode('/', $_SERVER['HTTP_REFERER'])[3] == __FUNCTION__){
            //     $myAction = explode('/', $_SERVER['HTTP_REFERER'])[4];
            // }
            // if($myAction == 'add'){
            //     $myAction = 'save';
            // }
            // if($myAction == 'edit'){
                $myAction = 'update';
            // }
        }

        $table = 'tbls_'.__FUNCTION__;
        $searchText = $this->request->getPost('searchText');
        $data['searchText'] = $searchText;
        $data['records'] = $this->loginModel->$functionName($table, $searchText);

        if($action == 'lookup' && $recordLookup == 1){
            $this->loadViews(__FUNCTION__, $this->global, $data, NULL);
        }

        else if($action == 'add' && $recordAdd == 1){
            $this->accessDenied();
            return;
        }

        else if($action == 'edit' && $recordEdit == 1){
            $this->accessDenied();
            return;
        }

        else if($action == 'view' && $recordView == 1){
            $this->accessDenied();
            return;
        }

        else if($action == 'delete' && $recordDelete == 1){
            $this->accessDenied();
            return;
        }

        else if($myAction == 'save' && $recordSubmit == 1){
            $this->accessDenied();
            return;
        }

        else if($myAction == 'update' && $recordSubmit == 1){
            $this->accessDenied();
            return;
        }

        else{
            $this->accessDenied();
            return;
        }
    }
    ///// ..... The End  userguide() Function ..... /////
    

    // The report function used for load reports module only...
    public function report($action = null, $reportType = null, $id = null)
    {
        // Session Service
        $session = \Config\Services::session();
        $isLoggedIn = $session->get('isLoggedIn');

        if (!$isLoggedIn) {
            return redirect()->to(base_url('login'));
        }

        $rolePage = $this->loginModel->rolePageGet($this->roleId);
        $found = false;
        $recordLookup = $recordAdd = $recordEdit = $recordView = $recordDelete = $recordSubmit = 0;

        if (!empty($rolePage)) {
            foreach ($rolePage as $res) {
                if (__FUNCTION__ == $res->url) {
                    $recordLookup = $res->recordLookup;
                    $recordAdd = $res->recordAdd;
                    $recordEdit = $res->recordEdit;
                    $recordView = $res->recordView;
                    $recordDelete = $res->recordDelete;
                    $recordSubmit = $res->recordSubmit;
                    $found = true;
                    break;
                }
            }
        }

        if (!$found) {
            return $this->accessDenied();
        }

        $functionName = __FUNCTION__;
        $functionNameEdit = __FUNCTION__ . 'Edit';

        $data['pageTitle'] = $this->loginModel->pageTitleGet(__FUNCTION__);
        $global['pageTitle'] = $this->companyProject . ' | ' . $data['pageTitle'][0]->friendlyName;

        $myAction = $action == 'submit' ? 'view' : $action;

        $data['reportType'] = $this->loginModel->reportTypeGet();
        $data['country'] = $this->myModel->countryGet();
        $data['manufacturer'] = $this->myModel->manufacturerGet();

        if ($action == 'lookup' && $recordLookup == 1) {
            return view(__FUNCTION__, $data);
        } elseif ($action == 'add' && $recordAdd == 1) {
            return $this->accessDenied();
        } elseif ($action == 'edit' && $recordEdit == 1) {
            return $this->accessDenied();
        } elseif ($myAction == 'view' && $recordView == 1) {
            $parameters = $this->request->getPost();
            $reportType = urldecode($reportType) ?? $parameters['reportType'] ?? null;

            if (!$reportType) {
                return redirect()->to(base_url('report'));
            }

            $hasReportRights = 0;
            $db = \Config\Database::connect();
            $builder = $db->table('tbls_reporttype as BaseTbl');
            $builder->select('BaseTbl.id')
                    ->where('BaseTbl.isDeleted', 0)
                    ->where('BaseTbl.reportType', $reportType);

            $query = $builder->get();
            if ($query->getNumRows() === 0) {
                $session->setFlashdata('error', 'No record found.');
            } else {
                $hasReportRights = 1;
            }

            if ($hasReportRights && $reportType == 'All Users') {
                // Include TCPDF
                require_once(APPPATH . 'ThirdParty/TCPDF/tcpdf.php');

                // Create new PDF document
                $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

                // Set document information
                $pdf->SetCreator(PDF_CREATOR);
                $pdf->SetAuthor('CyberSoft Technologies');
                $pdf->SetTitle($this->companyNick . ' | ' . $data['pageTitle'][0]->friendlyName);

                // Set default header and footer data
                $pdf->SetHeaderData('logo.png', PDF_HEADER_LOGO_WIDTH, '', '');
                $pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));

                // Set fonts and margins
                $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
                $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
                $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
                $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
                $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
                $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);

                $pdf->AddPage();

                // Generate HTML content
                $data['records'] = $this->loginModel->$functionName($reportType, $id, $parameters, 'Main');
                $_SESSION['reportType'] = $reportType;
                $html = '
                    <h1 style="text-align:center;">All Users</h1>
                    <div style="text-align:right;">
                        FROM <b>' . $parameters['fromDate'] . '</b> TO <b>' . $parameters['toDate'] . '</b>
                    </div>
                    <center>
                    <table border="1" cellspacing="0" cellpadding="5">
                        <thead>
                            <tr>
                                <th width="10%">S.#</th>
                                <th width="30%">Name</th>
                                <th width="20%">Email</th>
                                <th width="20%">Phone</th>
                                <th width="20%">Created On</th>
                            </tr>
                        </thead>
                        <tbody>';

                $sn = 1;
                if (!empty($data['records'])) {
                    foreach ($data['records'] as $record) {
                        $html .= '<tr>
                            <td>' . $sn . '</td>
                            <td>' . $record->userName . '</td>
                            <td>' . $record->email . '</td>
                            <td>' . $record->phone . '</td>
                            <td>' . ($record->dateTime ? date("d-M-y H:i", strtotime($record->dateTime)) : '') . '</td>
                        </tr>';
                        $sn++;
                    }
                }

                $html .= '</tbody></table></center>';

                // Write HTML content to PDF
                $pdf->writeHTML($html, true, false, true, false, '');

                // Output PDF
                $pdf->Output('report.pdf', 'I');
            }
        } elseif ($action == 'delete' && $recordDelete == 1) {
            return $this->accessDenied();
        } elseif ($myAction == 'save' && $recordSubmit == 1) {
            return $this->accessDenied();
        } elseif ($myAction == 'update' && $recordSubmit == 1) {
            return $this->accessDenied();
        } else {
            return $this->accessDenied();
        }
    }
    ///// ..... The End  report() Function ..... /////


    //################## MAIN FUNCTIONS ##################

}
