<?php

namespace App\Controllers;

use App\Models\LoginModel;
use App\Models\MyModel;
use App\Controllers\Login;


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Calculation\Logical\Conditional;

class MyController extends Login
{
    protected $myModel;
    protected $loginModel;
    
    //public function __construct() is used to initialize the controller, setting up necessary configurations or loading models, libraries, and helpers before any other methods are called.
    public function __construct()
    {    
        $this->session = session(); // Ensure session is loaded
        parent::__construct();
        $this->isLoggedIn1();
        $this->loginModel = new LoginModel();
        $this->myModel = new MyModel();
    }

    // The index function is used to check if the user is logged out or logged in to the system, using sessions securely.
    public function index()
    {
        if (session()->has('isLoggedIn') && session()->get('isLoggedIn') === true) {
            return redirect()->to('/dashboard');  // Redirect to dashboard if already logged in
        }
        $data['loginModel'] = $this->loginModel;
        $data['myModel'] = $this->myModel;
        $data['session'] = $this->session;
        // The normal login view logic goes here
        return view('login', $data); // or whatever your login view is called
    }
    

    // The dashboard function used to load the dashboard module only....
    function dashboard($module = null)
    { 

        if (!session()->has('userId')) { // Check if the session key exists
            return redirect()->to('/login'); // Redirect to login if session does not exist
        }

        $data['dashboard'] = '';
        $this->global['pageTitle'] = $this->companyProject.' | Dashboard';

        if($this->roleId == 705 || $this->roleId == 706 || $this->roleId == 707 || $this->roleId == 708 || $this->roleId == 709){
        //$data['countDraft'] = $this->myModel->countDraft();
        $data['countScheduled'] = $this->myModel->countScheduled($this->roleId, $this->countryId);
        $data['countScheduled1'] = $this->myModel->countScheduled1($this->roleId, $this->userId);
        $data['countInProcess'] = $this->myModel->countInProcess($this->roleId, $this->countryId);
        $data['countInProcess1'] = $this->myModel->countInProcess1($this->roleId, $this->userId);
        //$data['countAssessmentCompleted'] = $this->myModel->countAssessmentCompleted();
        $data['countInspectionCompleted'] = $this->myModel->countInspectionCompleted($this->roleId, $this->countryId);
        $data['countInspectionCompleted1'] = $this->myModel->countInspectionCompleted1($this->roleId, $this->userId);
        $data['countUnderCapa'] = $this->myModel->countUnderCapa($this->roleId, $this->countryId);
        $data['countUnderCapa1'] = $this->myModel->countUnderCapa1($this->roleId, $this->userId);
        //$data['countInspectionClosed'] = $this->myModel->countInspectionClosed();
        $data['countInspectionApproved'] = $this->myModel->countInspectionApproved($this->roleId, $this->countryId);
        $data['countInspectionApproved1'] = $this->myModel->countInspectionApproved1($this->roleId, $this->userId);
        }

        if($this->roleId == '1'){
            $this->loadViews('dashboard-admin', $this->global, $data, NULL);
        }
        else{
            $this->loadViews('dashboard', $this->global, $data, NULL);
        }
    }

    //################## AJAX FUNCTIONS ##################
    //These are all AJAX functions used to retrieve data from any table in the database.
    public function example1AjaxGet()
    {
        if ($this->request->getPost('data')) {
            return $this->myModel->example1AjaxGet($this->request->getPost('data'), $this->request->getPost('compare'));
        }
    }

    public function example2AjaxGet()
    {
        return $this->myModel->example2AjaxGet('', '');
    }

    public function example3AjaxGet()
    {
        if ($this->request->getPost('data')) {
            return $this->myModel->example3AjaxGet($this->request->getPost('data'), '');
        }
    }

    public function myAjaxGet()
    {
        return $this->myModel->myAjaxGet($this->request->getPost('table'), '', '', $this->request->getPost('columnName'));
    }

    public function myAjaxGet1()
    {
        return $this->myModel->myAjaxGet1($this->request->getPost('table'), $this->request->getPost('system'), '', $this->request->getPost('columnName'));
    }

    public function myAjaxGet2()
    {
        return $this->myModel->myAjaxGet2($this->request->getPost('table'), $this->request->getPost('system'), '', $this->request->getPost('columnName'));
    }

    public function myAjaxGet3()
    {
        return $this->myModel->myAjaxGet3($this->request->getPost('table'), $this->request->getPost('system'), '', $this->request->getPost('columnName'));
    }

    //################## AJAX FUNCTIONS ##################
    
    //This function is used to upload data through an Excel file.
    //$moduleName = null: This parameter specifies which module's data is being uploaded through the Excel file.
    public function uploadData($moduleName = null)
    {
        $isLoggedIn = session()->get('isLoggedIn');
        if (!isset($isLoggedIn) || $isLoggedIn !== true) {
            return redirect()->to('login');
        }

        $found = false;
        $rolePage = $this->loginModel->rolePageGet($this->roleId);
        if (!empty($rolePage)) {
            foreach ($rolePage as $res) {
                if ($moduleName == $res->url) {
                    $found = true;
                    break;
                }
            }
        }
        if (!$found) {
            return $this->accessDenied();
        }

        $data['pageTitle'] = $this->loginModel->pageTitleGet(__FUNCTION__);
        $this->global['pageTitle'] = $this->companyProject . ' | ' . $data['pageTitle'][0]->friendlyName;

        $table = 'tbl_' . $moduleName;

        $file = $this->request->getFile('attachment');
        $allowedFileTypes = ['application/vnd.ms-excel', 'text/csv'];

        if ($file->isValid() && $file->getSize() <= 5000000 && in_array($file->getClientMimeType(), $allowedFileTypes)) {
            $fileStream = fopen($file->getTempName(), 'r');
            $row = 0;
            $bulkCapaRows = [];
            $bulkSystemElementRows = [];
            $stamp = date($this->dateTimeFormat);

            while (($field = fgetcsv($fileStream, 10000, ',')) !== false) {
                if ($row < 1) {
                    $row++;
                    continue;
                }
            // Remove empty values and count non-empty fields
            $nonEmptyFields = array_filter($field, function ($value) {
                return trim($value) !== ''; // Remove whitespace and check if not empty
            });

            // Skip row if too many empty fields (adjust threshold as needed)
            if (count($nonEmptyFields) < 5) { 
                continue;
            }

                if ($moduleName == 'company') {
                    
                    $data = ['companyName' => $field[0], 'licenseNo' => $field[1], 'siteAddress' => $field[2], 'postalAddress' => $field[3], 'phone' => $field[4], 'email' => $field[5], 'licenseType' => $field[6], 'responsibility' => $field[7], 'licenseIssueDate' => $field[8], 'licenseExpiryDate' => $field[9], 'licenseLastRenewalDate' => $field[10], 'countryId' => $field[11], 'companyCategory' => $field[13], 'productScopeModel' => $field[14], 'productTypeId' => $field[15], 'status' => 'Active', 'createdby' => $this->userId, 'createddate' => date($this->dateTimeFormat), 'updatedby' => $this->userId, 'updateddate' => date($this->dateTimeFormat),
                    ];
                    $this->loginModel->recordAjaxSave($data, $table);

                    $data1 = ['userName' => $field[0], 'address' => $field[1], 'countryId' => $field[4], 'category' => 'GMP', 'phone' => $field[12], 'email' => $field[13], 'roleId' => '709', 'profilepic' => 'avatar5.png', 'wallpaper' => 'wp1.jpg', 'isOnline' => '1', 'status' => 'Pending', 'createdby' => $this->userId, 'createddate' => date($this->dateTimeFormat), 'updatedby' => $this->userId, 'updateddate' => date($this->dateTimeFormat),
                    ];

                    $result = $this->loginModel->recordAjaxSave($data1, 'tbls_user');


                } elseif ($moduleName == 'systemelement') {
                    $bulkSystemElementRows[] = [
                        'id' => $field[2],
                        'group1' => $field[6],
                        'group2' => $field[8],
                        'group3' => $field[10],
                        'createdby' => $this->userId,
                        'createddate' => $stamp,
                        'updatedby' => $this->userId,
                        'updateddate' => $stamp,
                    ];
                } elseif ($moduleName == 'facility') {
                    
                    $data = ['countryRegistrationNo' => $field[0], 'companyName' => $field[1], 'productCategory' => $field[2], 'facilityTypeId' => $field[4], 'publicPrivate' => $field[5], 'physicalAddress' => $field[6], 'postalAddress' => $field[7], 'street' => $field[8], 'cityName' => $field[9], 'stateName' => $field[10], 'longitude' => $field[11], 'latitude' => $field[12], 'phone' => $field[13], 'fax' => $field[14], 'website' => $field[15], 'tradeRegisterNo' => $field[16], 'vatNo' => $field[17], 'qaPocName' => $field[18], 'qaPocPhone' => $field[19], 'qaPocCellNo' => $field[20], 'qaPocEmail' => $field[21], 'raPocName' => $field[22], 'raPocPhone' => $field [23], 'raPocCellNo' => $field[24], 'raPocEmail' => $field[25], 'genPocName' => $field[26], 'genPocPhone' => $field[27], 'genPocCellNo' => $field[28], 'email' => $field[29], 'countryId' => $field[30], 'status' => 'Active', 'createdby' => $this->userId, 'createddate' => date($this->dateTimeFormat), 'updatedby' => $this->userId, 'updateddate' => date($this->dateTimeFormat),
                    ];
                    $this->loginModel->recordAjaxSave($data, $table);
                }elseif ($moduleName == 'gmpexecution') {

                    // Check agar saare important fields empty hain
                    if (empty(trim($field[5])) && empty(trim($field[6])) && empty(trim($field[7])) && empty(trim($field[8])) )
                    {
                        continue;
                    }

                    $bulkCapaRows[] = [
                        'id' => $field[0],
                        'rootCauseAnalysis' => $field[5],
                        'correctionProposed' => $field[6],
                        'steps' => $field[7],
                        'timeline' => $field[8],
                        'preparedCapaStatus' => 'CAPA Prepared',
                        'createdby' => $this->userId,
                        'createddate' => $stamp,
                        'updatedby' => $this->userId,
                        'updateddate' => $stamp,
                    ];
                }

                $row++;
            }
            fclose($fileStream);

            if (!empty($bulkCapaRows)) {
                $this->loginModel->recordAjaxBulkUpdate('id', $bulkCapaRows, 'tbl_gmpinspectioncapaobservationdetail');
            }
            if (!empty($bulkSystemElementRows)) {
                $this->loginModel->recordAjaxBulkUpdate('id', $bulkSystemElementRows, $table);
            }
        }

        $this->session->setFlashdata('success', 'Data uploaded successfully.');
        return redirect()->to('viewLoader/' . $moduleName . '/lookup');
    }
    // The end ... uploaddata() function //

    public function uploadDataUpdate($moduleName = null)
    {
        $isLoggedIn = session()->get('isLoggedIn');
        if (!isset($isLoggedIn) || $isLoggedIn !== true) {
            return redirect()->to('login');
        }
        $found = false;
        $rolePage = $this->loginModel->rolePageGet($this->roleId);
        if (!empty($rolePage)) {
            foreach ($rolePage as $res) {
                if ($moduleName == $res->url) {
                    $found = true;
                    break;
                }
            }
        }
        if (!$found) {
            return $this->accessDenied();
        }
        $file = $this->request->getFile('attachment');
        $allowedFileTypes = ['application/vnd.ms-excel', 'text/csv'];

        // Implementation Level ka text -> actual stored value mapping
        $implementationLevelMap = [
            'Not Assessed'      => 'Not Assessed',
            'Does Not Comply'   => 1,
            'Partially Comply'  => 2,
            'Fully Comply'      => 3,
        ];

        if ($file->isValid() && $file->getSize() <= 5000000 && in_array($file->getClientMimeType(), $allowedFileTypes)) {
            $fileStream = fopen($file->getTempName(), 'r');
            $row = 0;
            $bulkRows = [];
            $updatedAt = date($this->dateTimeFormat);
            while (($field = fgetcsv($fileStream, 10000, ',')) !== false) {
                if ($row < 1) {
                    $row++;
                    continue;
                }
                // Remove empty values and count non-empty fields
                $nonEmptyFields = array_filter($field, function ($value) {
                    return trim($value) !== '';
                });
                // Skip row if too many empty fields (adjust threshold as needed)
                if (count($nonEmptyFields) < 5) {
                    continue;
                }
                if ($moduleName == 'gmpexecution') {
                    // Naye column order ke mutabiq (0-indexed):
                    // 0 Sr.No | 1 Master ID | 2 System ID | 3 System | 4 Element ID
                    // 5 Element | 6 Risk Rating | 7 Implementation Level | 8 Deficiency Level | 9 Observation
                    $masterId = trim($field[1] ?? '');
                    $elementId = trim($field[4] ?? '');

                    $implementationLevelRaw = trim($field[7] ?? '');
                    $gmpRatingValue = $implementationLevelMap[$implementationLevelRaw] ?? $implementationLevelRaw;

                    // Constraint columns (masterId + elementId) must be in each row for updateBatch
                    $bulkRows[] = [
                        'masterId'    => $masterId,
                        'elementId'   => $elementId,
                        'systemId'    => $field[2],
                        'riskRating'  => $field[6],
                        'gmpRating'   => $gmpRatingValue,
                        'deficiency'  => $field[8],
                        'observation' => $field[9] ?? '',
                        'updatedby'   => $this->userId,
                        'updateddate' => $updatedAt,
                    ];
                }
                $row++;
            }
            fclose($fileStream);

            if ($moduleName == 'gmpexecution' && !empty($bulkRows)) {
                $this->loginModel->recordAjaxBulkUpdate(
                    ['masterId', 'elementId'],
                    $bulkRows,
                    'tbl_gmpinspectionscoringdetail'
                );
            }
        }
        $this->session->setFlashdata('success', 'Data uploaded successfully.');
        return redirect()->to('viewLoader/' . $moduleName . '/lookup');
    }

    public function selfAssessmentUpload()
    {
        $isLoggedIn = session()->get('isLoggedIn');
        if (!isset($isLoggedIn) || $isLoggedIn !== true) {
            return redirect()->to('login');
        }

        $id = $this->request->getPost('recordId');

        if (!$id) {
            $this->session->setFlashdata('error', 'Record not found.');
            return redirect()->to('viewLoader/gmpschedule/lookup');
        }

        $file = $this->request->getFile('attachment');

        if (!$file || !$file->isValid() || $file->hasMoved()) {
            $this->session->setFlashdata('error', 'Please select a valid file.');
            return redirect()->to('viewLoader/gmpexecution/lookup');
        }

        // Sirf .docx allow karna hai
        $allowedExt = ['docx', 'pdf'];
        $ext = strtolower($file->getClientExtension());

        if (!in_array($ext, $allowedExt)) {
            $this->session->setFlashdata('error', 'Only .docx OR .pdf files are allowed.');
            return redirect()->to('viewLoader/gmpexecution/lookup');
        }

        // Max 5 MB
        if ($file->getSize() > 5000000) {
            $this->session->setFlashdata('error', 'File size should not exceed 5 MB.');
            return redirect()->to('viewLoader/gmpexecution/lookup');
        }

        // File ko move karo (tumhare existing fileMove() helper ki tarah, jo 'Attachment' folder + companyName/docs mein save karta hai)
        $fileName = $this->fileMove('Attachment', 'attachment', $this->companyUniqueNo, 'docs');

        if (!$fileName) {
            $this->session->setFlashdata('error', 'File upload failed.');
            return redirect()->to('viewLoader/gmpexecution/lookup');
        }

        // tbl_gmpinspection ke column 'selfAssessmentAttachment' mein filename update karo
        $data = [
            'selfAssessmentAttachment' => $fileName,
            'updatedby'                => $this->userId,
            'updateddate'              => date($this->dateTimeFormat),
        ];

        $result = $this->loginModel->recordAjaxUpdate('id', $id, $data, 'tbl_gmpinspection');

        if ($result > 0) {
            $this->session->setFlashdata('success', 'Self Assessment uploaded successfully.');
        } else {
            $this->session->setFlashdata('error', 'Something went wrong.');
        }

        return redirect()->to('viewLoader/gmpexecution/lookup');
    }


    

    //The viewLoader() function is used to load a module. Only the modules that are available in this function can be loaded.
    // Explain parameters:
    //$view = NULL: This parameter gets the module name.
    //$action = NULL: This parameter checks which action to load, such as add, update, submit, etc.
    //$id = NULL: This parameter checks which row ID to update.
    function viewLoader($view = NULL, $action = NULL, $id = NULL)
    {   
        $isLoggedIn=$this->session->get('isLoggedIn');if(!isset($isLoggedIn)||$isLoggedIn != TRUE){return redirect()->to('login');}$found=false; $rolePage = $this->loginModel->rolePageGet($this->roleId);if(!empty($rolePage)){foreach ($rolePage as $res){$pageName = $res->url; $recordLookup = $res->recordLookup; $recordAdd = $res->recordAdd; $recordEdit = $res->recordEdit; $recordView = $res->recordView; $recordDelete = $res->recordDelete; $recordSubmit = $res->recordSubmit; if($view == $pageName){ $found=true; break;}}}if($found == false){$this->accessDenied();return;}

        $functionName = __FUNCTION__; 
        $functionNameEdit = __FUNCTION__.'Edit';

        $data['pageTitle'] = $this->loginModel->pageTitleGet($view);
        $this->global['pageTitle'] = $this->companyProject.' | '.$data['pageTitle'][0]->friendlyName;
        $this->global['pageTitleUrl'] = $data['pageTitle'][0]->url;
        $this->global['pageTitleIcon'] = $data['pageTitle'][0]->icon;
        $this->global['pageTitleFriendlyName'] = $data['pageTitle'][0]->friendlyName;

        $data['pageTitle'] = $this->global['pageTitle'];
        $data['myModel'] = $this->myModel;
        helper('custom');

        //print_r($functionName); exit();

        $myAction = '';
        if(@$action == 'submit'){
            //print_r('expression'); exit();
            $httpReferer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;

            if ($httpReferer) {
                $refererParts = explode('/', $httpReferer);
                
                if (isset($refererParts[5]) && $refererParts[5] == $view) {
                    $myAction = isset($refererParts[6]) ? $refererParts[6] : '';
                }
                if (isset($refererParts[4]) && $refererParts[4] == $view) {
                    $myAction = isset($refererParts[5]) ? $refererParts[5] : '';
                }
            }
            if($myAction == 'add'){
                $myAction = 'save';
            }
            if($myAction == 'edit'){
                $myAction = 'update';
            }
        }
        
        if($view == 'deletefacility'){
            $myAction = 'update';
            $table = 'tbl_facility';
        }
        if($view == 'undeletefacility'){
            $myAction = 'update';
            $table = 'tbl_facility';
        }
        if($view == 'deletemanufacturer'){
            $myAction = 'update';
            $table = 'tbl_company';
        }


        if($view == 'product' || $view == 'productRejected' || $view == 'productRenewal'){
            $table = 'tbl_registration';
        }
        else if($view == 'inspectorcalendar' || $view == 'gmpschedule' || $view == 'gmpexecution' || $view == 'gmpreport' || $view == 'gmpcapa' || $view == 'gmpschedulingscheduled' || $view == 'gmpschedulingundercapa' || $view == 'gmpschedulinginprocess' || $view == 'gmpschedulingcompleted' || $view == 'gmpschedulingapproved'){
            $table = 'tbl_gmpinspection';
        }
        else if($view == 'deletemanufacturer'){
            $myAction = 'update';
            $table = 'tbl_company';
        }
        else if($view == 'deletefacility' || $view == 'undeletefacility'){
            $myAction = 'update';
            $table = 'tbl_facility';
        }
        else if($view == 'test'){
            $table = 'tbl_mytest';
        }
        else if($view == 'inspectionReport'){
            $table = 'tbl_company';
        }
        else{
            $table = 'tbl_'.$view;
        }

        $data['formId'] = $id;
        $searchText = $this->request->getPost('searchText');
        $data['searchText'] = $searchText;
        // Edit/view don't need the full inspection list — skip expensive table scan
        if ($action === 'lookup' || $action === 'add' || empty($action)) {
            $data['records'] = $this->myModel->$functionName($table, $searchText);
        } else {
            $data['records'] = [];
        }
        $data['recordsEdit'] = $this->myModel->$functionNameEdit($id, $table);
        

        if($action == 'lookup' && $recordLookup == 1){
            $this->loadViews('company/'.$this->companyName.'/'.$view, $this->global, $data, NULL);
        }

        else if($action == 'add' && $recordAdd == 1){
            $this->loadViews('company/'.$this->companyName.'/'.$view, $this->global, $data, NULL);
        }

        else if($action == 'edit' && $recordEdit == 1){
            if(!$id){
                $this->accessDenied();
                return;
            }

            if($view == 'something'){
                if($this->roleId == 1){
                    $this->session->setFlashdata('error', 'You are not authorized to edit this record.');
                    return redirect()->to('viewLoader/'.$view.'/lookup');
                }
            }
            
            $this->loadViews('company/'.$this->companyName.'/'.$view, $this->global, $data, NULL);
        }

        else if($action == 'view' && $recordView == 1){
            if(!$id){
                $this->accessDenied();
                return;
            }
            
            $this->loadViews('company/'.$this->companyName.'/'.$view, $this->global, $data, NULL);
        }

        else if($action == 'delete' && $recordDelete == 1 && $view <> 'panelpool'){
            
            $data = array('isDeleted'=>1, 'updateddate'=>date($this->dateTimeFormat), 'updatedby'=>$this->userId);

            //ob_clean(); print_r($data); exit();

            $result = $this->loginModel->recordAjaxUpdate('id', $id, $data, $table);

            if($result > 0){
                $this->session->setFlashdata('success', 'Record deleted successfully.');
            }
            else{
                $this->session->setFlashdata('error', 'Something went wrong.');
            }

            return redirect()->to('viewLoader/'.$view.'/lookup');
        }

        else if($action == 'delete' && $recordDelete == 1 && $view == 'panelpool'){

            //$this->db->table('users')->where('id', $id)->delete();
            $checkPanelPool = myGet('tbl_panelpool', 'BaseTbl.*', 'BaseTbl.id ='.$id, 'BaseTbl.id', 'asc');

            $result = $this->loginModel->panelPoolUser($id);

            $result1 = $this->loginModel->userDeleted($checkPanelPool[0]->userId);

            
            


            if($result > 0){
                $this->session->setFlashdata('success', 'Record deleted successfully.');
            }
            else{
                $this->session->setFlashdata('error', 'Something went wrong.');
            }

            return redirect()->to('viewLoader/'.$view.'/lookup');
        }
        
        else if($myAction == 'save' && $recordSubmit == 1){
            $data = $this->request->getPost();
            
            if(!$data){
                $this->accessDenied();
                return;
            }
            
            $data['createdby'] = $this->userId;
            $data['createddate'] = date($this->dateTimeFormat);
            $data['updatedby'] = $this->userId;
            $data['updateddate'] = date($this->dateTimeFormat);
            $result = 0;
            $resultdetail = 0;

            foreach ($data as $key => $row){
                if (strpos($key, '-') !== false || strpos($key, '_length') !== false) {
                    unset($data[$key]);
                }
            }

            foreach ($data as $key => $row){
                if(@$_FILES[$key]['tmp_name']){
                    $data[$key] = $this->fileMove('Attachment', $key, $this->companyUniqueNo, 'docs');
                }
            }

            if(@$mailSend == 'Yes'){
                // ------------------- Send Mail Start -------------------
                if(isset($mail['email'])){
                    helper('custom');
                    $mailData['from'] = $this->companyName;
                    $mailData['subject'] = $this->companyName.' | '.$mail['subject'];
                    $mailData['title'] = 'Greetings, '.$mail['userName'].'!';
                    $mailData['message'] = $mail['message'].". In case of any query please write us at: support@company.com";
                    $mailData['email'] = $mail['email'];
                    $sendStatus = mailSend($mailData);
                    if($sendStatus == true){
                        $result = 1;
                    }
                }
                if(!isset($mail['email'])){
                    $result = 0;
                }
                // ------------------- Send Mail End -------------------
            }

            //unset($data['something']);
            
            if($view == 'panelpool'){
                $panelpool = $this->myModel->myGet('tbl_panelpool', 'BaseTbl.*', 'BaseTbl.status = "Active" and BaseTbl.email = "'.$data['email'].'"', 'BaseTbl.id', 'asc');
                if(count($panelpool) > 0){
                    $this->session->setFlashdata('error', 'Email already exists.');
                    return redirect()->to('viewLoader/'.$view.'/lookup');
                }   
            }

            $result = $this->loginModel->recordAjaxSave($data, $table);
            if($view == 'registration'){
                $this->newRegistration($result);
            }
            if($view == 'gmpschedule'){
                $gmpschedulemain = $this->myModel->myGet('tbl_gmpinspection', 'BaseTbl.*', 'BaseTbl.id = "'.$result.'" ', 'BaseTbl.id', 'asc');
                $companyProductType = $this->myModel->myGet('tbl_company', 'BaseTbl.*', 'BaseTbl.id = "'.$gmpschedulemain[0]->companyId.'" ', 'BaseTbl.id', 'desc');
                $system = $this->myModel->myGet('tbl_system', 'BaseTbl.*', 'BaseTbl.status = "Active" AND BaseTbl.category = "GMP"', 'BaseTbl.id', 'asc');
                $element = $this->myModel->myGet('tbl_systemelement', 'BaseTbl.*', 'BaseTbl.status = "Active"', 'BaseTbl.id', 'asc');

                $company = !empty($companyProductType) ? $companyProductType[0] : null;

                // ===== Placeholder/junk values jo dropdown ke default option se galti se DB mein save ho gayi hain =====
                // Inhe bhi "empty" hi treat karna hai
                $placeholderValues = ['Select Product Scope Model', 'Select', 'Select...'];

                // ===== Helper: comma separated string ko clean array mein convert krna (TRIM(BOTH ',' ...) jaisa) =====
                // + placeholder text ko filter out karta ha
                $toArray = function($str) use ($placeholderValues){
                    if(empty($str)) return [];
                    $raw = trim($str, ', ');
                    if($raw === '') return [];

                    // Agar poori value hi placeholder text ke barabar ha (case-insensitive), to empty treat karo
                    foreach($placeholderValues as $ph){
                        if(strcasecmp(trim($raw), trim($ph)) === 0){
                            return [];
                        }
                    }

                    $parts = array_map('trim', explode(',', $raw));

                    // Comma-separated list ke andar bhi agar koi part placeholder text ho, usko filter kardo
                    $parts = array_filter($parts, function($p) use ($placeholderValues){
                        if($p === '') return false;
                        foreach($placeholderValues as $ph){
                            if(strcasecmp($p, $ph) === 0) return false;
                        }
                        return true;
                    });

                    return array_values($parts);
                };

                $companyTypes     = $company ? $toArray($company->productTypeId) : [];
                $companyMedicineBy = $company ? $toArray($company->medicineBy) : [];

                // ===== tbl_assessmentconfiguration ke saare configs (matching PHP mein array-intersect se hogi,
                //       kyunki comma-list ka SQL WHERE mein reliable exact-match/LIKE se check nahi ho sakta) =====
                $assessmentConfig = $this->myModel->myGet('tbl_assessmentconfiguration', 'BaseTbl.*', '1=1', 'BaseTbl.id', 'asc');

                // ===== Symmetric match helper (productTypeId aur productScopeModel ke liye) =====
                // Dono (config aur company) empty -> match true
                // Dono mein value + intersect -> match true
                // Sirf ek empty (dusra mein value) -> match false
                $matchField = function($configArr, $companyArr){
                    $configEmpty  = empty($configArr);
                    $companyEmpty = empty($companyArr);

                    if($configEmpty && $companyEmpty){
                        return true; // dono empty -> match
                    }
                    if($configEmpty xor $companyEmpty){
                        return false; // sirf ek empty -> match nahi
                    }
                    // dono ke pass values hain -> intersect check
                    foreach($companyArr as $val){
                        if(in_array($val, $configArr)){
                            return true;
                        }
                    }
                    return false;
                };

                // ===== medicineBy ka alag rule =====
                // Config ki value empty ha -> condition consider hi nahi hogi (hamesha true, company kuch bhi ho)
                // Config mein value ha -> tabhi company se intersect check hoga (company empty ho ya value mismatch ho -> false)
                $matchMedicineBy = function($configArr, $companyArr){
                    if(empty($configArr)){
                        return true; // config restrict nahi kr raha -> condition ignore
                    }
                    if(empty($companyArr)){
                        return false; // config ne value maangi ha but company ke pass nahi
                    }
                    foreach($companyArr as $val){
                        if(in_array($val, $configArr)){
                            return true;
                        }
                    }
                    return false;
                };

                $matchedConfigs = [];
                if(!empty($assessmentConfig)){
                    foreach ($assessmentConfig as $config){
                        $configTypes      = $toArray($config->productTypeId);
                        $configScope      = $toArray($config->productScopeModel);
                        $configMedicineBy = $toArray($config->medicineBy);

                        $typeMatch       = $matchField($configTypes, $companyTypes);
                        $medicineByMatch = $matchMedicineBy($configMedicineBy, $companyMedicineBy);

                        if($typeMatch && $medicineByMatch){
                            $matchedConfigs[] = $config;
                        }
                    }
                }

                // ===== Baseline systemIds (picsParentBaselineId) -> hamesha elementStatus = 1 =====
                $baselineSystemIds = [];
                // ===== Crosscutting + ProductSpecific json se systemId => 1/0 map banana =====
                $conditionalSystemStatus = [];

                $fourFields = ['computerizedSystem', 'sterileAsepticProcess', 'ionisingRadiation', 'rtrtParametricRelease'];

                foreach ($matchedConfigs as $config){
                    // baseline systems
                    foreach ($toArray($config->picsParentBaselineId) as $sysId){
                        $baselineSystemIds[$sysId] = true;
                    }

                    // dono json (crosscutting + productspecific) same tarah process hongi
                    $jsonSets = [
                        ['data' => $config->jsonDataDetailCrossCutting,    'key' => 'crosscuttingannexesId'],
                        ['data' => $config->jsonDataDetailProductSpecific, 'key' => 'productSpecificId'],
                    ];

                    foreach ($jsonSets as $set){
                        if(empty($set['data'])) continue;
                        $rows = json_decode($set['data'], true);
                        if(empty($rows)) continue;

                        foreach ($rows as $row){
                            if(empty($row[$set['key']])) continue;
                            $sysId = $row[$set['key']];

                            $hasYesCondition  = false; // json mein kisi field ki value 'Yes' ha?
                            $conditionMatched = false; // company ka corresponding column bhi 'Yes' ha?

                            foreach ($fourFields as $field){
                                $jsonVal = isset($row[$field]) ? trim($row[$field]) : '';
                                if($jsonVal === 'Yes'){
                                    $hasYesCondition = true;
                                    $companyVal = ($company && isset($company->$field)) ? trim($company->$field) : '';
                                    if($companyVal === 'Yes'){
                                        $conditionMatched = true;
                                    }
                                }
                            }

                            if(!$hasYesCondition){
                                // charo fields empty/No hain -> koi condition nahi, hamesha applicable
                                $status = 1;
                            } else {
                                // json mein Yes tha, tabhi status 1 hoga jab company ka column bhi Yes ho
                                $status = $conditionMatched ? 1 : 0;
                            }

                            // agar kisi dusre matched config se pehle hi status set ha, tu 1 ko priority do (OR logic)
                            if(!isset($conditionalSystemStatus[$sysId]) || $status == 1){
                                $conditionalSystemStatus[$sysId] = $status;
                            }
                        }
                    }
                }

                if(!empty($system)){
                    foreach ($system as $record0){
                        $elementStatus = 0;

                        if(isset($baselineSystemIds[$record0->id])){
                            $elementStatus = 1;
                        } elseif(isset($conditionalSystemStatus[$record0->id])){
                            $elementStatus = $conditionalSystemStatus[$record0->id];
                        }

                        if(!empty($element)){
                            foreach ($element as $record00){
                                if($record0->id == $record00->masterId){
                                    $this->loginModel->recordAjaxSave([
                                        'masterId'      => $result,
                                        'systemId' => $record0->id,
                                        'elementId'     => $record00->id,
                                        'riskRating'    => $record00->riskRating,
                                        'elementStatus' => $elementStatus
                                    ], 'tbl_gmpinspectionscoringdetail');
                                }
                            }
                        }
                        $this->loginModel->recordAjaxSave([
                            'masterId' => $result,
                            'systemId' => $record0->id,
                            'elementStatus' => $elementStatus
                        ], 'tbl_gmpinspectionassessmentdetail');
                    }
                }

                $riskLevel = $this->myModel->myGet('tbl_risklevel', 'BaseTbl.*', 'BaseTbl.status = "Active" AND BaseTbl.category = "GMP"', 'BaseTbl.id', 'asc');
                if(!empty($riskLevel)){
                    foreach ($riskLevel as $record1){
                        $this->loginModel->recordAjaxSave([
                            'masterId'    => $result,
                            'riskLevelId' => $record1->id
                        ], 'tbl_gmpinspectionriskleveldetail');
                    }
                }
            }

            if($view == 'panelpool'){
                if($data['type'] == 'Inspector'){
                    if($data['status'] == 'Active'){
                        $password = $this->generateRandomString(5);
                        $hashedPassword = getHashedPassword($password);

                        if($data['category'] == 'GMP'){
                        $result0 = $this->loginModel->recordAjaxSave(['email' => $data['email'], 'password' => $hashedPassword, 'userName' => $data['memberName'], 'roleId' => 707, 'category' => $data['category'], 'countryId' => $this->countryId, 'profilepic' => 'avatar5.png', 'phone' => $data['phone'], 'status' => 'Active', 'wallpaper' => 'wp1.jpg', 'createdby' => $data['createdby'], 'createddate' => $data['createddate']], 'tbls_user');
                        }

                        $mailData['from'] = 'RBI';
                        $mailData['subject'] = 'RBI | Account Information';
                        $mailData['title'] = 'Greetings, '.$data['memberName'].'!';
                        $mailData['message'] = "Your account on RBI as an Inspector has been created. Please use this email and ".$password." as password to login.";
                        $mailData['email'] = $data['email'];
                        $sendStatus = mailSend($mailData);
                        if($sendStatus == true){
                            //$result = 1;
                        }

                        $this->loginModel->recordAjaxUpdate('id', $result, ['userId' => $result0], $table);
                    }
                }
            }
            //$currentUserAdminEmail[0]=(object)[];
            
            //if($data['masterId'] == 0){
            if(isset($data['masterId']) && $data['masterId'] == 0){
            if($view == 'technicalquery'){
                $currentUserAdmin = $this->myModel->myGet('tbls_user', 'BaseTbl.*', 'BaseTbl.id = "'.$this->userId.'"', 'BaseTbl.id', 'asc');
                if($currentUserAdmin[0]->roleId == 707){
                $currentUserAdminEmail = $this->myModel->myGet('tbls_user', 'BaseTbl.*', 'BaseTbl.roleId = 705 AND BaseTbl.countryId ='.$this->countryId, 'BaseTbl.id', 'asc');
                $mailData['from'] = 'RBI';
                $mailData['subject'] = 'RBI | Technical Query';
                $mailData['title'] = 'Greetings, Admin!';
                $mailData['message'] = "New technical query received.";
                $mailData['email'] = $currentUserAdminEmail[0]->email;
                $sendStatus = mailSend($mailData);
                if($sendStatus == true){
                    //$result = 1;
                }
                }
                if($currentUserAdmin[0]->roleId == 708){
                $currentUserAdminEmail = $this->myModel->myGet('tbls_user', 'BaseTbl.*', 'BaseTbl.roleId = 706 AND BaseTbl.countryId ='.$this->countryId, 'BaseTbl.id', 'asc');
                $mailData['from'] = 'RBI';
                $mailData['subject'] = 'RBI | Technical Query';
                $mailData['title'] = 'Greetings, Admin!';
                $mailData['message'] = "New technical query received.";
                $mailData['email'] = $currentUserAdminEmail[0]->email;
                $sendStatus = mailSend($mailData);
                if($sendStatus == true){
                    //$result = 1;
                }
                }
                
            }
            }
            
            //if($data['masterId'] <> 0){
            if (isset($data['masterId']) && $data['masterId'] != 0) {
                if ($view == 'technicalquery') {
                    // Fetch the technical query
                    $currentUserAdmin = $this->myModel->myGet('tbl_technicalquery', 'BaseTbl.*', 'BaseTbl.id = "'.$data['masterId'].'"', 'BaseTbl.id', 'asc');

                    if (!empty($currentUserAdmin) && isset($currentUserAdmin[0])) {
                        // Fetch the user who created the query
                        $currentUserAdminEmail = $this->myModel->myGet('tbls_user', 'BaseTbl.*', 'BaseTbl.id = '.$currentUserAdmin[0]->createdby, 'BaseTbl.id', 'asc');

                        if (!empty($currentUserAdminEmail) && isset($currentUserAdminEmail[0])) {
                            // Prepare and send the email
                            $mailData['from'] = 'RBI';
                            $mailData['subject'] = 'RBI | Technical Query';
                            $mailData['title'] = 'Greetings!';
                            $mailData['message'] = "Technical query responded.";
                            $mailData['email'] = $currentUserAdminEmail[0]->email;
                            $sendStatus = mailSend($mailData);
                        } 
                    }
                }
            }

            if($result > 0){
                $this->session->setFlashdata('success', 'New record saved successfully.');
            }
            else{
                $this->session->setFlashdata('error', 'Something went wrong.');
            }

            if($view == 'gmpschedule'){
                return redirect()->to('viewLoader/'.$view.'/edit/'.$result);
            }
            //print_r('expression'); exit();
            return redirect()->to('viewLoader/'.$view.'/lookup');
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

            foreach ($data as $key => $row){
                if (strpos($key, '-') !== false || strpos($key, '_length') !== false) {
                    unset($data[$key]);
                }
            }

            foreach ($data as $key => $row){
                if(@$_FILES[$key]['tmp_name']){
                    $data[$key] = $this->fileMove('Attachment', $key, $this->companyUniqueNo, 'docs');
                }
            }
            
            if($view == 'deletefacility'){
                $result0 = $this->loginModel->recordAjaxCustomQuery('UPDATE `tbl_facility` SET `isDeleted` = 1 WHERE `tbl_facility`.`updatedby` = "'.$this->userId.'" AND STR_TO_DATE(`tbl_facility`.`createddate`,"%d-%M-%y") = "'.$data['myDate'].'"');
                $this->session->setFlashdata('success', 'Record deleted successfully.');
                return redirect()->to('viewLoader/facility/lookup');
            }
            if($view == 'undeletefacility'){
                $result0 = $this->loginModel->recordAjaxCustomQuery('UPDATE `tbl_facility` SET `isDeleted` = 0 WHERE `tbl_facility`.`isDeleted` = "1" AND `tbl_facility`.`updatedby` = "'.$this->userId.'" AND STR_TO_DATE(`tbl_facility`.`createddate`,"%d-%M-%y") = "'.$data['myDate'].'"');
                $this->session->setFlashdata('success', 'Record undeleted successfully.');
                return redirect()->to('viewLoader/facility/lookup');
            }
            if($view == 'deletemanufacturer'){
                $result0 = $this->loginModel->recordAjaxCustomQuery('UPDATE `tbl_company` SET `isDeleted` = 1 WHERE `tbl_company`.`updatedby` = "'.$this->userId.'" AND STR_TO_DATE(`tbl_company`.`createddate`,"%d-%M-%y") = "'.$data['myDate'].'"');
                $this->session->setFlashdata('success', 'Record deleted successfully.');
                return redirect()->to('viewLoader/company/lookup');
            }
            
            // Workflow START
            $recordsEdit = $this->myModel->myGet($table, 'BaseTbl.*', 'BaseTbl.id ='.substr($_SERVER['HTTP_REFERER'], strrpos($_SERVER['HTTP_REFERER'], '/') + 1), 'BaseTbl.id', 'asc');
            if(@$data['status'] == 'Save'){
                $data['status'] = $recordsEdit[0]->status;
            }
            if(explode('+', @$data['status'])[0] == 'Proceed'){
                if($view == 'gmpschedule' || $view == 'gmpexecution' || $view == 'gmpreport' || $view == 'gmpcapa'){
                    $companyName = '';
                    $company = $this->myModel->myGet('tbl_company', 'BaseTbl.*', 'BaseTbl.status = "Active"', 'BaseTbl.id', 'asc');
                        if(!empty($company)){
                            foreach ($company as $record0){
                                if($record0->id == @$recordsEdit[0]->companyId){
                                    $companyName = $record0->companyName;
                                    $email = $record0->email;
                                }
                            }
                        }
                    if($this->roleId == 709){
                    $stages = ['Scheduled', 'In Process', 'Inspection Completed', 'Under CAPA', 'CAPA Submitted', 'Site Accepted', 'Site Not Accepted'];
                    }
                    if($this->roleId <> 709){
                    $stages = ['Scheduled', 'In Process', 'Inspection Completed', 'Under CAPA', 'Site Accepted', 'Site Not Accepted'];
                    }
                    $currentKey = array_search(explode('+', @$data['status'])[1], $stages);
                    $data['status'] = $stages[$currentKey + 1];

                    if($view == 'gmpexecution' && @$data['status'] == 'Inspection Completed'){
                        $company = $this->myModel->myGet('tbl_company', 'BaseTbl.*', 'BaseTbl.status = "Active"', 'BaseTbl.id', 'asc');
                        if(!empty($company)){
                            foreach ($company as $record0){
                                if($record0->id == @$recordsEdit[0]->companyId){
                                    $companyName = $record0->companyName;
                                    $email = $record0->email;
                                }
                            }
                        }
                        $mailData['from'] = 'RBI';
                        $mailData['subject'] = 'RBI | GMP Inspection Completed';
                        $mailData['title'] = 'Greetings, Concerned NMRA Members!';
                        $mailData['message'] = "Inspection for ".$companyName." has been completed by ".$this->userName." dated ".@$data['nextInspectionDate'].".";
                        $mailData['email'] = $email;
                        $sendStatus = mailSend($mailData);

                        $recordsDetailInspectionScoringCapa = $this->myModel->myGet('tbl_gmpinspectionscoringdetail', 'BaseTbl.*', 'BaseTbl.masterId ="'.@$recordsEdit[0]->id.'" AND BaseTbl.elementStatus = "1"', 'BaseTbl.id', 'asc');

                        // ===== NEW: Fetch the existing observation rows only for this masterId =====
                        $currentMasterId = @$recordsEdit[0]->id;
                        $existingCapaObservations = $this->myModel->myGet('tbl_gmpinspectioncapaobservationdetail', 'BaseTbl.*', 'BaseTbl.masterId ="'.$currentMasterId.'"', 'BaseTbl.id', 'asc');

                        // ===== NEW: The key is now composite as masterId_elementId, not just elementId =====
                        $existingByMasterElement = [];
                        if(!empty($existingCapaObservations)){
                            foreach($existingCapaObservations as $exRow){
                                $compositeKey = $exRow->masterId.'_'.$exRow->elementId;
                                $existingByMasterElement[$compositeKey] = $exRow;
                            }
                        }
                        // ===== END NEW =====

                        foreach($recordsDetailInspectionScoringCapa as $detail){

                            $lookupKey = $currentMasterId.'_'.$detail->elementId;

                            // ===== NEW: The masterId and elementId must both match. =====
                            if(isset($existingByMasterElement[$lookupKey])
                               && $existingByMasterElement[$lookupKey]->masterId == $currentMasterId
                               && $existingByMasterElement[$lookupKey]->elementId == $detail->elementId){

                                $existingRow = $existingByMasterElement[$lookupKey];
                                $existingId  = $existingRow->id;

                                $data5 = [];
                                $data5['deficiency']   = $detail->deficiency;
                                $data5['observation']  = $detail->observation;
                                $data5['updatedby']    = $this->userId;
                                $data5['updateddate']  = date($this->dateTimeFormat);
                                $this->loginModel->recordAjaxUpdate('id', $existingId, $data5, 'tbl_gmpinspectioncapaobservationdetail');

                                // Target the follow-up root row using both masterId and ref
                                $this->loginModel->recordAjaxCustomQuery(
                                    "UPDATE tbl_gmpinspectioncapafollowupdetail 
                                     SET deficiency = '".addslashes($detail->deficiency)."', 
                                         observation = '".addslashes($detail->observation)."', 
                                         updateddate = '".date($this->dateTimeFormat)."' 
                                     WHERE masterId = '".$currentMasterId."' AND ref = '".$existingId.".1'"
                                );

                                continue;
                            }
                            // ===== END NEW =====

                            // New element or masterId (completed for the first time) → insert it.
                            $data5['deficiency'] = $detail->deficiency;
                            $data5['observation'] = $detail->observation;
                            $data5['elementId'] = $detail->elementId;
                            $data5['masterId'] = $currentMasterId;
                            $data5['createdby'] = $this->userId;
                            $data5['createddate'] = date($this->dateTimeFormat);
                            $data5['updateddate'] = date($this->dateTimeFormat);
                            $result5 = $this->loginModel->recordAjaxSave($data5, 'tbl_gmpinspectioncapaobservationdetail');

                            $data4['deficiency'] = $detail->deficiency;
                            $data4['observation'] = $detail->observation;
                            $data4['masterId'] = $currentMasterId;
                            $data4['ref'] = $result5.'.1';
                            $data4['createdby'] = $this->userId;
                            $data4['createddate'] = date($this->dateTimeFormat);
                            $data4['updateddate'] = date($this->dateTimeFormat);
                            $result4 = $this->loginModel->recordAjaxSave($data4, 'tbl_gmpinspectioncapafollowupdetail');
                        }
                    }
                    
                    if(@$data['status'] == 'Inspection Completed'){
                        $data['inspectionCompletionDate'] = date('Y-m-d');
                    }

                    if($this->roleId == 709 && $view == 'gmpexecution' && @$data['status'] == 'CAPA Submitted'){
                        $data['status'] = 'Under CAPA';
                        $data['capaSubmittedFlag'] = 1;
                    }   
                }
            }
            if(@$data['status'] == 'Cancelled'){
                $data['status'] = 'Cancelled';
            }
            
            // when Configuration is open like value is "0", then only send attachment email to company only.
            $configurationNo = $this->myModel->myGet('tbl_config', 'BaseTbl.*', 'BaseTbl.configName = "Company Login" AND BaseTbl.configKey = "CL"', 'BaseTbl.id', 'asc');
            if($configurationNo[0]->configValue == '0'){
                if(@$data['status'] == 'Under CAPA' && $recordsEdit[0]->mailCAPA == 0 && @$recordsEdit[0]->capaAttachment){
                    if($this->roleId == 707){
                        if(@$data['sendCAPAMail'] == 'Yes'){
                            $company = $this->myModel->myGet('tbl_company', 'BaseTbl.*', 'BaseTbl.status = "Active"', 'BaseTbl.id', 'asc');
                            if(!empty($company)){
                                $companyName = '';
                                $email = '';
                                foreach ($company as $record0){
                                    if($record0->id == @$recordsEdit[0]->companyId){
                                        $companyName = $record0->companyName;
                                        $email = $record0->email;
                                    }
                                }
                            }
                            $mailData['from'] = 'RBI';
                            $mailData['subject'] = 'RBI | GMP Inspection CAPA';
                            $mailData['title'] = 'Greetings, '.$companyName.'!';
                            $mailData['message'] = "
                            <p>Dear Manufacturer,</p>

                            <p>Please note that after conducting the <strong>&lt;Inspection Type&gt;</strong> of your manufacturing facility, inspection panel members observed some deficiencies that are available in the attachment of this email.</p>

                            <p>You are kindly requested to prepare the <strong>Corrective &amp; Preventive Action (CAPA)</strong> against the provided panel members’ observations within <strong>30 days</strong> of this email.</p>

                            <p>Warm regards,<br>
                            National Medicine Regulatory Authority</p>";
                            $mailData['email'] = $email;
                            $mailData['attach'] = base_url().'/uploads/company/'.$this->companyName.'/docs/'.@$recordsEdit[0]->capaAttachment;
                            $sendStatus = mailSend($mailData);
                            if($sendStatus == true){
                                $result = 1;
                                $data['mailCAPA'] = 1;
                                $data['mailCAPADate'] = $data['updateddate'];
                            }
                        }
                    }
                }
            }

            // when Configuration is open like value is "1", then only send email to company only.
            if($configurationNo[0]->configValue == '1'){
                if(@$data['status'] == 'Under CAPA' && $this->roleId == 707){
                    $company = $this->myModel->myGet('tbl_company', 'BaseTbl.*', 'BaseTbl.status = "Active"', 'BaseTbl.id', 'asc');
                    if(!empty($company)){
                        $companyName = '';
                        $email = '';
                        foreach ($company as $record0){
                            if($record0->id == @$recordsEdit[0]->companyId){
                                $companyName = $record0->companyName;
                                $email = $record0->email;
                            }
                        }
                    }
                    $mailData['from'] = 'RBI';
                    $mailData['subject'] = 'RBI | GMP Inspection CAPA';
                    $mailData['title'] = 'Greetings, '.$companyName.'!';
                    $mailData['message'] = "
                    <p>Dear Manufacturer,</p>
                        Please note that after conducting the <Inspection Type>  of your manufacturing facility, inspection panel members observed some deficiencies that are available in the RBI Tool.

                        You are requested to logon to the RBI Tool and please prepare the Corrective & Preventive Action (CAPA) against the provided panel members’ observations within 30 days of this email.

                        Warm regards,
                        National Medicine Regulatory Authority
                        Country: $this->countryName ";
                    $mailData['email'] = $email;

                    $sendStatus = mailSend($mailData);
                    if($sendStatus == true){
                        $result = 1;
                    }
                
                }
            }
                        
            if($view == 'deletefacility'){
                $result0 = $this->loginModel->recordAjaxCustomQuery('UPDATE `tbl_facility` SET `isDeleted` = 1 WHERE `tbl_facility`.`updatedby` = "'.$this->userId.'" AND STR_TO_DATE(`tbl_facility`.`createddate`,"%d-%M-%y") = "'.$data['myDate'].'"');
            }
            if($view == 'undeletefacility'){
                $result0 = $this->loginModel->recordAjaxCustomQuery('UPDATE `tbl_facility` SET `isDeleted` = 0 WHERE `tbl_facility`.`isDeleted` = "1" AND `tbl_facility`.`updatedby` = "'.$this->userId.'" AND STR_TO_DATE(`tbl_facility`.`createddate`,"%d-%M-%y") = "'.$data['myDate'].'"');
            }
            if($view == 'deletemanufacturer'){
                $result0 = $this->loginModel->recordAjaxCustomQuery('UPDATE `tbl_company` SET `isDeleted` = 1 WHERE `tbl_facility`.`updatedby` = "'.$this->userId.'" AND STR_TO_DATE(`tbl_facility`.`createddate`,"%d-%M-%y") = "'.$data['myDate'].'"');
            }

            if(@$mailSend == 'Yes'){
                // ------------------- Send Mail Start -------------------
                if(isset($mail['email'])){
                    $mailData['from'] = $this->companyName;
                    $mailData['subject'] = $this->companyName.' | '.$mail['subject'];
                    $mailData['title'] = 'Greetings, '.$mail['userName'].'!';
                    $mailData['message'] = $mail['message'].". In case of any query please write us at: support@company.com";
                    $mailData['email'] = $mail['email'];
                    $sendStatus = mailSend($mailData);
                    if($sendStatus == true){
                        $result = 1;
                    }
                }
                if(!isset($mail['email'])){
                    $result = 0;
                }
                // ------------------- Send Mail End -------------------
            }

            //unset($data['something']);
            $result = $this->loginModel->recordAjaxUpdate('id', substr($_SERVER['HTTP_REFERER'], strrpos($_SERVER['HTTP_REFERER'], '/') + 1), $data, $table);

            if($view == 'panelpool'){
                if($data['type'] == 'Inspector'){
                    if($data['status'] == 'Active'){
                        $this->loginModel->recordAjaxUpdate('id', $data['userId'], ['status' => 'Active'], 'tbls_user');
                    }
                    if($data['status'] == 'Inactive'){
                        $this->loginModel->recordAjaxUpdate('id', $data['userId'], ['status' => 'Inactive'], 'tbls_user');
                    }
                }
            }

            foreach ($this->request->getPost() as $key => $row){
                if (substr($key, -6) == 'detail') {
                    $dataDetailTable[$key] = $row;
                }
            }

            if(@$dataDetailTable){
                $sId = 0;
                $keys = array_keys($dataDetailTable);
                foreach (array_keys($dataDetailTable) as $key){
                    $current_key = current($keys);
                    $current_value = $dataDetailTable[$current_key];
                    $next_key = next($keys);
                    $next_value = @$dataDetailTable[$next_key];
                    $tableName = substr($current_key, 0, strpos($current_key, '-'));
                    $nextTableName = substr($next_key, 0, strpos($next_key, '-'));
                    $tables = [
                        'tabledetailinspectionmembergmp' => 'tbl_gmpinspectionmemberdetail', 
                        'tabledetailinspectionquerygmp' => 'tbl_gmpinspectionquerydetail', 
                        'tabledetailinspectionscoringgmp' => 'tbl_gmpinspectionscoringdetail', 
                        'tabledetailinspectionassessmentgmp' => 'tbl_gmpinspectionassessmentdetail', 
                        'tabledetailinspectionrisklevelgmp' => 'tbl_gmpinspectionriskleveldetail', 
                        'tabledetailinspectioncompliancelevelgmp' => 'tbl_gmpinspectionassessmentdetail',
                        'tabledetailigmpnspectiondeflevel' => 'tbl_gmpinspectiondefleveldetail',
                        'tabledetailgmpinspectioncapacritical' => 'tbl_gmpinspectioncapafollowupdetail',
                        'tabledetailgmpinspectioncapamajor' => 'tbl_gmpinspectioncapafollowupdetail',
                        'tabledetailgmpinspectioncapaother' => 'tbl_gmpinspectioncapafollowupdetail',
                        'tabledetailgmpinspectioncritical' => 'tbl_gmpinspectioncapaobservationdetail',
                        'tabledetailgmpinspectionmajor' => 'tbl_gmpinspectioncapaobservationdetail',
                        'tabledetailgmpinspectionother' => 'tbl_gmpinspectioncapaobservationdetail',
                        'tabledetailgmpinspectionelement' => 'tbl_gmpinspectionscoringdetail',
                        'tabledetailgmpinspectiondocument' => 'tbl_gmpinspectiondocumentdetail',
                        'tabledetailgmpexecutionquery' => 'tbl_gmpexecutionquerydetail',
                        'tabledetailgmpinspectionattendance' => 'tbl_gmpinspectionattendancedetail',
                        'tabledetailgmpexecutionsystemassign' => 'tbl_gmpexecutionsystemassigndetail'
                    ];
                    foreach ($tables as $tableId => $tableDB){
                        if($tableName == $tableId){
                            $tableDetail = $tableDB;
                            $datadetail[substr($current_key, strpos($current_key, '-') + 1, strrpos($current_key, '_') - strrpos($current_key, '-') - 1)] = $current_value;
                            $totalFiles = 0;
                            if (isset($_FILES[$current_key]['tmp_name'])) {
                                $totalFiles = count($_FILES[$current_key]['tmp_name']);
                            }
                            $fileNames = is_array($current_value) ? $current_value : [];
                            for ($i = 0; $i < $totalFiles; $i++) {
                                if ($_FILES[$current_key]['tmp_name'][$i]) {
                                    $fileNames[$i] = $this->fileMoveMultiple('Attachment', $current_key, $i, $this->companyName, 'docs');
                                    $datadetail[substr($current_key, strpos($current_key, '-') + 1, strrpos($current_key, '_') - strrpos($current_key, '-') - 1)] = $fileNames;
                                }
                            }
                            if($myAction == 'save'){
                                $masterId = $result;
                            }
                            if($myAction == 'update'){
                                $masterId = substr($_SERVER['HTTP_REFERER'], strrpos($_SERVER['HTTP_REFERER'], '/') + 1);
                            }
                            if($nextTableName <> $tableName){
                                $resultdetail = $this->dataDetailTableSave($masterId, $datadetail, $tableDetail);
                                unset($datadetail);
                            }
                        }
                    }
                    $sId++;
                }
            }

            if($result > 0){
                $this->session->setFlashdata('success', 'Record updated successfully.');
            }
            else{
                $this->session->setFlashdata('error', 'Something went wrong.');
            }
            
            return redirect()->to('viewLoader/'.$view.'/lookup');
        }

        else{
            $this->accessDenied();
            return;
        }
    }
    // The end ... viewLoader() function //
    
    /** * exportWord() */
    function exportWord($templateName = null, $id = null)
    {
        try {
            if (!$templateName || !$id) {
                throw new \InvalidArgumentException('Template name and ID are required.');
            }
            if (!is_numeric($id)) {
                throw new \InvalidArgumentException('Invalid ID.');
            }
            $id = (int) $id;

            // Manually include PhpWord library
            $phpWordBootstrapPath = FCPATH . 'assets/dist/vendors/PHPWord/bootstrap.php';
            if (!file_exists($phpWordBootstrapPath)) {
                throw new \RuntimeException("Required file '{$phpWordBootstrapPath}' not found.");
            }
            require_once $phpWordBootstrapPath;
            if (!class_exists(\PhpOffice\PhpWord\PhpWord::class)) {
                throw new \RuntimeException('PhpWord class could not be loaded.');
            }

            // Prepare data
            $data['myModel'] = $this->myModel;
            $data['id'] = $id;

            $phpWord = new \PhpOffice\PhpWord\PhpWord();

            // ===== Heading styles so <h1>/<h2>/<h3> in the view map to real Word
            //       heading styles, which the Table of Contents needs to find them. =====
            $phpWord->addTitleStyle(1, ['bold' => true, 'size' => 16, 'name' => 'Arial'], ['spaceBefore' => 200, 'spaceAfter' => 120]);
            $phpWord->addTitleStyle(2, ['bold' => true, 'size' => 13, 'name' => 'Arial'], ['spaceBefore' => 160, 'spaceAfter' => 100]);
            $phpWord->addTitleStyle(3, ['bold' => true, 'italic' => true, 'size' => 12, 'name' => 'Arial'], ['spaceBefore' => 120, 'spaceAfter' => 80]);

            // Set section options based on template type
            $sectionOptions = ($templateName === 'gmpcapatemplate')
                ? ['orientation' => 'landscape']
                : [];
            $section = $phpWord->addSection($sectionOptions);

            // ===================== HEADER (repeats on every page) =====================
            // Column widths (1702 / 5403 / 2835 twips) and all font sizes below are taken
            // directly from the approved OF-INSP-GMP-02A_v2 template's header XML.
            $subsequent = $section->addHeader();

            $headerTable = $subsequent->addTable([
                'borderSize'  => 4,
                'borderColor' => '000000',
                'cellMargin'  => 80,
                'unit'        => 'dxa',
                'width'       => 9940,
            ]);

            // ---- Row 1 ----
            $headerTable->addRow(991);

            $docNumCell = $headerTable->addCell(1702, ['valign' => 'center']);
            $docNumCell->addText('Doc Number:', ['bold' => true, 'size' => 9], ['alignment' => 'center']);
            $docNumCell->addText('OF-INSP-GMP-02A', ['size' => 9], ['alignment' => 'center']);

            $titleCell = $headerTable->addCell(5403, ['vMerge' => 'restart', 'valign' => 'center']);
            $titleCell->addText(
                $templateName === 'gmpinspectionreport' ? 'GMP Inspection Report' : 'INSPECTION PROGRAMME',
                ['bold' => true, 'size' => 14],
                ['alignment' => 'center']
            );

            $logoCell = $headerTable->addCell(2835, ['valign' => 'center']);
            $logoPath = FCPATH . 'uploads/company/RBI/logo/sahpra.png';
            if (file_exists($logoPath)) {
                // NOTE: using the local server path (FCPATH ...) instead of base_url()/a URL.
                // PHPWord has to fetch the image file directly - a local path is faster and
                // doesn't depend on allow_url_fopen or the app being able to call its own URL.
                $logoCell->addImage($logoPath, [
                    'width'     => 103,
                    'height'    => 48,
                    'alignment' => 'center',
                ]);
            }

            // ---- Row 2 ----
            $headerTable->addRow();

            $revisionCell = $headerTable->addCell(1702, ['valign' => 'center']);
            $revisionCell->addText('Revision: 2.0', ['size' => 9], ['alignment' => 'center']);

            // Continuation of the vertically merged title cell — must stay empty.
            $headerTable->addCell(5403, ['vMerge' => 'continue']);

            $effectiveDateCell = $headerTable->addCell(2835, ['valign' => 'center']);
            $effectiveDateCell->addText(
                'Effective date: 15 November 2023',
                ['size' => 9],
                ['alignment' => 'center']
            );
            // ===================== END HEADER =====================

            // ===================== FOOTER (page numbers) =====================
            $footer = $section->addFooter();
            $footerPath = FCPATH . 'uploads/company/RBI/logo/footer.png';
            if (file_exists($footerPath)) {
                $footer->addImage($footerPath, ['width' => 490, 'height' => 60]);
            }
            $footer->addPreserveText('Page {PAGE} of {NUMPAGES}', ['size' => 9], ['alignment' => 'center']);
            // ===================== END FOOTER =====================

            // ===================== TABLE OF CONTENTS =====================
            
            // ===================== END TOC =====================

            // ===== Render the view, splitting on the <!--PAGEBREAK--> markers so
            //       each Part starts on its own page — a real Word page break,
            //       not a guess based on how many <br> tags fit. =====
            $viewPath = 'company/RBI/' . $templateName;
            if (!is_file(APPPATH . 'Views/' . $viewPath . '.php')) {
                throw new \RuntimeException("View '{$viewPath}' not found.");
            }
            $htmlContent = view($viewPath, $data);

            $chunks = explode('<!--PAGEBREAK-->', $htmlContent);
            foreach ($chunks as $i => $chunk) {
                if ($i > 0) {
                    $section->addPageBreak();
                }
                \PhpOffice\PhpWord\Shared\Html::addHtml($section, $chunk, false, false);
            }

            // Clear output buffer to avoid sending unwanted content
            if (ob_get_level()) {
                ob_end_clean();
            }

            // Set headers for download
            $filename = $templateName . '.docx';
            header('Content-Description: File Transfer');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Expires: 0');

            // Save file directly to output
            $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
            $objWriter->save('php://output');
            exit;
        } catch (\Exception $e) {
            log_message('error', $e->getMessage());
            return redirect()->back()->with('error', 'Failed to export Word document.');
        }
    }
    // The end ...exportWord() function //

    public function exportExcel($templateName = null, $id = null){
        $systemIds = $this->request->getGet('systemIds');
        $memberId  = $this->request->getGet('memberId');
        // ===== Get memberName =====
        $memberName = '';
        if(!empty($memberId)){
            $memberRecord = $this->myModel->myGet('tbl_panelpool', 'BaseTbl.*', 'BaseTbl.id = "'.$memberId.'"', 'BaseTbl.id', 'asc');
            if(!empty($memberRecord)){
                $memberName = $memberRecord[0]->memberName;
            }
        }
        // ===== END =====
        $system = $this->myModel->myGet('tbl_system', 'BaseTbl.*', 'BaseTbl.status = "Active" AND BaseTbl.category = "GMP"', 'BaseTbl.id', 'asc');
        $element = $this->myModel->myGet('tbl_systemelement', 'BaseTbl.*', 'BaseTbl.status = "Active"', 'BaseTbl.id', 'asc');
        $systemIdsArray = [];
        if(!empty($systemIds)){
            $systemIdsArray = array_filter(array_map('trim', explode(',', $systemIds)));
            if(!empty($systemIdsArray) && !empty($system)){
                $system = array_values(array_filter($system, function($sysRow) use ($systemIdsArray){
                    return in_array($sysRow->id, $systemIdsArray);
                }));
            }

            if(!empty($systemIdsArray) && !empty($element)){
                $element = array_values(array_filter($element, function($sysRow) use ($systemIdsArray){
                    return in_array($sysRow->masterId, $systemIdsArray);
                }));
            }
        }
        
        $records = $this->myModel->myGet('tbl_systemelement', 'BaseTbl.*', "BaseTbl.id = ".$id, 'BaseTbl.id', 'desc');
        $recordsDetailInspectionScoring = $this->myModel->myGet('tbl_gmpinspectionscoringdetail', 'BaseTbl.*', "BaseTbl.gmpRating <> 'Not Assessed' AND BaseTbl.masterId = ".$id, 'BaseTbl.id', 'asc');
        $recordsDetailInspectionScoringOnline = $this->myModel->myGet('tbl_gmpinspectionscoringdetail', 'BaseTbl.*', "BaseTbl.elementStatus = '1' AND BaseTbl.masterId = ".$id, 'BaseTbl.id', 'asc');

        // ===== NEW: filter by systemIdsArray right here, before maxDataRow is computed =====
        if(!empty($systemIdsArray)){
            $recordsDetailInspectionScoringOnline = array_values(array_filter(
                $recordsDetailInspectionScoringOnline,
                function($record) use ($systemIdsArray){
                    return in_array($record->systemId, $systemIdsArray);
                }
            ));
        }
        // ===== END NEW =====

        $capaObservation = $this->myModel->myGet('tbl_gmpinspectioncapaobservationdetail', 'BaseTbl.*', "BaseTbl.masterId = ".$id, 'BaseTbl.id', 'desc');
        // Autoload PhpSpreadsheet
        require_once(APPPATH . 'Views/libraryexcel/vendor/autoload.php');

        function setDataToField($sheet, $value, $column, $rangeStart, $rangeEnd, $isBold, $fontSize, $isFill, $cellBackground, $textColor){
            if($value){
              $sheet->setCellValue($column, $value);
            }   
            $sheet->getStyle($rangeStart . ':' . $rangeEnd)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            $style = $sheet->getStyle($rangeStart . ':' . $rangeEnd);
            $alignment = $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $font = $style->getFont();
            $font->setSize($fontSize);
            $font->setBold($isBold);
            $font->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color($textColor));

            if($isFill){
                $fill = $style->getFill();
                $fill->setFillType(Fill::FILL_SOLID);
                $fill->setStartColor(new \PhpOffice\PhpSpreadsheet\Style\Color($cellBackground));
            }
        }
        // ===== NEW: helper to write a cell with *word* segments bold, rest normal =====
        function setRichTextField($sheet, $cellRef, $text, $fontSize = 12){
            $richText = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
            $lines = explode("\n", $text);
            foreach($lines as $idx => $line){
                $parts = preg_split('/(\*[^*]+\*)/', $line, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
                foreach($parts as $part){
                    if(preg_match('/^\*(.+)\*$/', $part, $m)){
                        $run = $richText->createTextRun($m[1]);
                        $run->getFont()->setBold(true)->setSize($fontSize);
                    } else {
                        $run = $richText->createTextRun($part);
                        $run->getFont()->setBold(false)->setSize($fontSize);
                    }
                }
                // Line break between lines (createBreak() not available in this PhpSpreadsheet version)
                if($idx < count($lines) - 1){
                    $breakRun = $richText->createTextRun("\n");
                    $breakRun->getFont()->setBold(false)->setSize($fontSize);
                }
            }
            $sheet->getCell($cellRef)->setValue($richText);
            $sheet->getStyle($cellRef)->getAlignment()->setWrapText(true);
            $sheet->getStyle($cellRef)->getAlignment()->setVertical(Alignment::VERTICAL_TOP);
        }
        // ===== END NEW =====

        // Remove any previous output
        if (ob_get_length()) {
            ob_end_clean();
        }

        if($templateName == 'capaexceltemplate'){
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('CAPA Observation');
            for($i='A'; $i < 'Z' ; $i++){
                $sheet->getColumnDimension($i)->setAutoSize(true);
              }
            setDataToField($sheet, 'ID', 'A1', 'A1', 'A1', true, 12, true , 'ffffff','000000');
            setDataToField($sheet, '', 'A2', 'A2', 'A500',  false, 12, true , 'ffffff','000000');

            setDataToField($sheet, 'Deficiency Level', 'B1', 'B1', 'B1', true, 12, true , 'ffffff','000000');
            setDataToField($sheet, '', 'B2', 'B2', 'B500', false, 12, true , 'ffffff','000000');

            setDataToField($sheet, 'System', 'C1', 'C1', 'C1', true, 12, true , 'ffffff','000000');
            setDataToField($sheet, '', 'C2', 'C2', 'C500',  false, 12, true , 'ffffff','000000');

            setDataToField($sheet, 'Element', 'D1', 'D1', 'D1' ,true, 12, true , 'ffffff','000000');
            setDataToField($sheet, '', 'D2', 'D2', 'D500' ,false, 12, true , 'ffffff','000000');

            setDataToField($sheet, 'Observation', 'E1', 'E1', 'E1', true, 12, true , 'ffffff','000000');
            setDataToField($sheet, '', 'E2', 'E2', 'E500',  false, 12, true , 'ffffff','000000');

            setDataToField($sheet, 'Root cause analysis (Additional information may it be attached as annexes)', 'F1', 'F1', 'F1', true, 12, true , 'ffffff','000000');
            setDataToField($sheet, '', 'F2', 'F2', 'F500', false, 12, true , 'ffffff','000000');

            setDataToField($sheet, 'Correction and proposed corrective action (Additional information may it will be attached as annexes)', 'G1', 'G1', 'G1', true, 12, true , 'ffffff','000000');
            setDataToField($sheet, '', 'G2', 'G2', 'G500', false, 12, true , 'ffffff','000000');

            setDataToField($sheet, 'The steps that have or will be taken for the demonstration of effectiveness of the actions taken', 'H1', 'H1', 'H1', true, 12, true , 'ffffff','000000');
            setDataToField($sheet, '', 'H2', 'H2', 'H500', false, 12, true , 'ffffff','000000');

            setDataToField($sheet, 'Timeline', 'I1', 'I1', 'I1', true, 12, true , 'ffffff','000000');
            setDataToField($sheet, '', 'I2', 'I2', 'I500', false, 12, true , 'ffffff','000000');

            
            // Pehle sorting order define karo
            $order = ['Critical' => 1, 'Major' => 2, 'Other' => 3];

            // Sort karo records ko deficiency level ke hisaab se
            usort($recordsDetailInspectionScoring, function($a, $b) use ($order) {
                return $order[$a->deficiency] <=> $order[$b->deficiency];
            });

            // Pehle IDs fill karo
            $count2 = 2;
            foreach($capaObservation as $record1){
                $sheet->setCellValue("A" . $count2, $record1->id);
                $count2++;
            }

            // Ab sorted records ka data fill karo
            $count1 = 2;
            foreach($recordsDetailInspectionScoring as $record){
                foreach ($system as $systemdetail){
                    foreach ($element as $elementdetail){
                        if(isset($record->elementId) && ($elementdetail->id == $record->elementId)){
                            if($elementdetail->masterId == $systemdetail->id){
                                
                                $sheet->setCellValue("B" . $count1, $record->deficiency);
                                $sheet->setCellValue("C" . $count1, $systemdetail->name);
                                $sheet->setCellValue("D" . $count1, $elementdetail->element);
                                $sheet->setCellValue("E" . $count1, $record->observation);
                                $count1++;

                            }
                        }
                    }        
                }
            }
        }

        if($templateName == 'RBI-V3 Offline Assessment'){

            // Create new excel file
            $spreadsheet = new Spreadsheet();
            // Add data to first worksheet
            $sheet1 = $spreadsheet->getActiveSheet();
            $sheet1->setShowGridlines(false);

            // Sheet Title
            $sheet1->setTitle('Instructions');

            // ===== UPDATED INSTRUCTIONS =====
            $sheet1->mergeCells('B2:P2');
            setDataToField($sheet1, 'INSTRUCTIONS TO CREATE, SAVE AND UPLOAD THE OFFLINE GMP ASSESSMENT DATA INTO RBI TOOL', 'B2', 'B2', 'B2' ,true, 15, true , 'c0504d', 'ffffff');

            $sheet1->mergeCells('C4:M4');
            setDataToField($sheet1, 'Step-1', 'B4', 'B4', 'B4' ,false, 12, true , '000000', 'ffffff');
            setRichTextField($sheet1, 'C4', 'Please Goto *GMP Offline Assessment* Sheet');

            $sheet1->mergeCells('C5:M5');
            $sheet1->getRowDimension(5)->setRowHeight(30);
            setDataToField($sheet1, 'Step-2', 'B5', 'B5', 'B5' ,false, 12, true , '000000', 'ffffff');
            setRichTextField($sheet1, 'C5', "Select data from *Column H* & *Column I* and Input *observations* in *Column J* and remove any *un-used rows* before saving to csv format.\n*Do Not Modify Any Other Column's Value*.");

            $sheet1->mergeCells('C6:M6');
            $sheet1->getRowDimension(6)->setRowHeight(30);
            setDataToField($sheet1, 'Step-3', 'B6', 'B6', 'B6',false, 12, true , '000000', 'ffffff');
            setRichTextField($sheet1, 'C6', "Copy all data rows from *Column A* to *Column J* and then create a *new Excel file* and *paste data as value* into new sheet at *Cell A1* and then Save new excel sheet with new name as Simple *Coma Separated Value* (.*CSV*) as *UTF-8* file format using MS Office.");

            $sheet1->mergeCells('C7:M7');
            setDataToField($sheet1, 'Step-4', 'B7', 'B7', 'B7' ,false, 12, true , '000000', 'ffffff');
            setRichTextField($sheet1, 'C7', 'Upload *newly created CSV file* in the Online RBI Tool Ver. 3.0');

            $sheet1->mergeCells('C8:M8');
            setDataToField($sheet1, 'Step-5', 'B8', 'B8', 'B8' ,false, 12, true , '000000', 'ffffff');
            setRichTextField($sheet1, 'C8', 'Verify the uploaded data in the *Assessment tab* of *GMP Inspection module*.');

            setDataToField($sheet1, 'Notes:', 'B11', 'B11', 'B11' ,false, 12, true , 'FF0000', 'ffffff');

            $sheet1->setCellValue('c12', "Don't refresh or close the RBI Tool Ver. 3.0 while uploading the offline data");
            $sheet1->setCellValue('c13', 'Make sure the file you are uploading is a CSV File and that should be upto 5 MB in size');
            $sheet1->setCellValue('c14', "Don't change the column sequence of the CSV file");
            $sheet1->mergeCells('C15:M15');
            setRichTextField($sheet1, 'C15', "Make sure your data in the CSV file *must start from Row No. 2*. Row number 1 will contain the column headings");
            $sheet1->setCellValue('c16', 'Your Microsoft Office should be Genuine and Activated.');
            $sheet1->setCellValue('c17', 'Your data must not contain (") Double Quotation mark and (\) Backslash');
            // ===== END UPDATED INSTRUCTIONS =====

            // Add data to second worksheet
            $sheet2 = $spreadsheet->createSheet();
            $sheet2->setTitle('GMP Assessment Detail');

            for($i='A'; $i < 'ZZ' ; $i++){ 
                $sheet2->getColumnDimension($i)->setAutoSize(true);
            }

            // ===== NEW: dynamic last row based on actual data instead of hardcoded 500 =====
            $maxDataRow = count($recordsDetailInspectionScoringOnline) + 1; // +1 for header row
            if($maxDataRow < 2){
                $maxDataRow = 2; // keep at least one empty data row
            }
            // ===== END NEW =====

            // ===== A: Sr.No =====
            setDataToField($sheet2, 'Sr.No', 'A1', 'A1', 'A1' ,true, 12, true , '87CEEB','000000');
            for($i = 1; $i < $maxDataRow; $i++){
                $sheet2->setCellValue('A'.($i+1), $i);
            }
            setDataToField($sheet2, '', 'A2', 'A2', 'A'.$maxDataRow ,false, 12, true , '87CEEB','000000');

            // ===== B: Master ID =====
            setDataToField($sheet2, 'Master ID', 'B1', 'B1', 'B1', true, 12, true , '87CEEB','000000');
            setDataToField($sheet2, '', 'B2', 'B2', 'B'.$maxDataRow, false, 12, true , '87CEEB','000000');
            for($i = 2; $i <= $maxDataRow; $i++){
                $sheet2->setCellValue('B'.$i, $id);
            }

            // ===== C: System ID =====
            setDataToField($sheet2, 'System ID', 'C1', 'C1', 'C1', true, 12, true , '87CEEB','000000');
            setDataToField($sheet2, '', 'C2', 'C2', 'C'.$maxDataRow, false, 12, true , '87CEEB','000000');

            // ===== D: System (dropdown) =====
            setDataToField($sheet2, 'System', 'D1', 'D1', 'D1' ,true, 12, true , '87CEEB','000000');
            setDataToField($sheet2, '', 'D2', 'D2', 'D'.$maxDataRow ,false, 12, true , '87CEEB','000000');

            // ===== E: Element ID =====
            setDataToField($sheet2, 'Element ID', 'E1', 'E1', 'E1', true, 12, true , '87CEEB','000000');
            setDataToField($sheet2, '', 'E2', 'E2', 'E'.$maxDataRow, false, 12, true , '87CEEB','000000');

            // ===== F: Element (dependent dropdown) =====
            setDataToField($sheet2, 'Element', 'F1', 'F1', 'F1', true, 12, true , '87CEEB','000000');
            setDataToField($sheet2, '', 'F2', 'F2', 'F'.$maxDataRow,  false, 12, true , '87CEEB','000000');

            // ===== G: Risk Rating =====
            setDataToField($sheet2, 'Risk Rating', 'G1', 'G1', 'G1', true, 12, true , '87CEEB','000000');
            setDataToField($sheet2, '', 'G2', 'G2', 'G'.$maxDataRow, false, 12, true , '87CEEB','000000');

            // ===== H: Implementation Level (dropdown) =====
            setDataToField($sheet2, 'Implementation Level', 'H1', 'H1', 'H1', true, 12, true , 'ffffff','000000');
            setDataToField($sheet2, '', 'H2', 'H2', 'H'.$maxDataRow, false, 12, true , 'ffffff','000000');

            // ===== I: Deficiency Level (dropdown) =====
            setDataToField($sheet2, 'Deficiency Level', 'I1', 'I1', 'I1', true, 12, true , 'ffffff','000000');
            setDataToField($sheet2, '', 'I2', 'I2', 'I'.$maxDataRow, false, 12, true , 'ffffff','000000');

            // ===== J: Observation =====
            setDataToField($sheet2, 'Observation', 'J1', 'J1', 'J1', true, 12, true , 'ffffff','000000');
            setDataToField($sheet2, '', 'J2', 'J2', 'J'.$maxDataRow, false, 12, true , 'ffffff','000000');

            // Width / wrap for readable columns
            $sheet2->getColumnDimension('D')->setAutoSize(false);
            $sheet2->getColumnDimension('D')->setWidth(50);
            $sheet2->getStyle('D2:D'.$maxDataRow)->getAlignment()->setWrapText(true);

            $sheet2->getColumnDimension('F')->setAutoSize(false);
            $sheet2->getColumnDimension('F')->setWidth(50);
            $sheet2->getStyle('F2:F'.$maxDataRow)->getAlignment()->setWrapText(true);

            // ===== NEW: Observation column (J) width increase =====
            $sheet2->getColumnDimension('J')->setAutoSize(false);
            $sheet2->getColumnDimension('J')->setWidth(60);
            $sheet2->getStyle('J2:J'.$maxDataRow)->getAlignment()->setWrapText(true);

            $lastSystemRow = count($system) + 1;

            for($i = 2; $i <= $maxDataRow; $i++){

                // Column D -> System name dropdown (Systems sheet se)
                $validationD = $sheet2->getCell('D'.$i)->getDataValidation();
                $validationD->setType(DataValidation::TYPE_LIST);
                $validationD->setErrorStyle(DataValidation::STYLE_INFORMATION);
                $validationD->setAllowBlank(true);
                $validationD->setShowInputMessage(true);
                $validationD->setShowErrorMessage(true);
                $validationD->setShowDropDown(true);
                $validationD->setFormula1('Systems!$B$2:$B$'.$lastSystemRow);

                // Column C -> selected system ki ID auto aa jaye (INDEX+MATCH, ab D column se)
                $sheet2->setCellValue('C'.$i,
                    '=IFERROR(INDEX(Systems!$A$2:$A$'.$lastSystemRow.',MATCH(TRIM(D'.$i.'),Systems!$B$2:$B$'.$lastSystemRow.',0)),"")'
                );

                // Column F -> Elements dependent dropdown, sirf selected system ke elements (ab C column ki ID se)
                $validationF = $sheet2->getCell('F'.$i)->getDataValidation();
                $validationF->setType(DataValidation::TYPE_LIST);
                $validationF->setErrorStyle(DataValidation::STYLE_INFORMATION);
                $validationF->setAllowBlank(true);
                $validationF->setShowInputMessage(true);
                $validationF->setShowErrorMessage(true);
                $validationF->setShowDropDown(true);
                $validationF->setFormula1('=INDIRECT("SYS_"&C'.$i.')');

                // Column E -> Element ID (ab F column se match)
                $sheet2->setCellValue('E'.$i, '=IFERROR(INDEX(Elements!$A:$A,MATCH(F'.$i.',Elements!$B:$B,0)),"")');

                // Column H -> Implementation Level dropdown (display labels)
                $validationH = $sheet2->getCell('H'.$i)->getDataValidation();
                $validationH->setType(DataValidation::TYPE_LIST);
                $validationH->setErrorStyle(DataValidation::STYLE_INFORMATION);
                $validationH->setAllowBlank(true);
                $validationH->setShowInputMessage(true);
                $validationH->setShowErrorMessage(true);
                $validationH->setShowDropDown(true);
                $validationH->setFormula1('"Not Assessed,Does Not Comply,Partially Comply,Fully Comply"');

                // Column I -> Deficiency Level dropdown
                $validationI = $sheet2->getCell('I'.$i)->getDataValidation();
                $validationI->setType(DataValidation::TYPE_LIST);
                $validationI->setErrorStyle(DataValidation::STYLE_INFORMATION);
                $validationI->setAllowBlank(true);
                $validationI->setShowInputMessage(true);
                $validationI->setShowErrorMessage(true);
                $validationI->setShowDropDown(true);
                $validationI->setFormula1('"Compliant,Critical,Major,Other"');
            }

            // Risk Rating (G column) - fill from online scoring records
            $systemNameLookup = [];
            if(!empty($system)){
                foreach($system as $sysRow){
                    $systemNameLookup[$sysRow->id] = trim($sysRow->name);
                }
            }

            $elementNameLookup = [];
            if(!empty($element)){
                foreach($element as $elRow){
                    $elementNameLookup[$elRow->id] = trim($elRow->element);
                }
            }

            $count1 = 2;
            foreach($recordsDetailInspectionScoringOnline as $record){
                $sysId = $record->systemId ?? '';
                $elId  = $record->elementId ?? '';
                $sysName = $systemNameLookup[$sysId] ?? '';
                $elName  = $elementNameLookup[$elId] ?? '';

                $sheet2->setCellValue('C' . $count1, $sysId);
                $sheet2->setCellValue('D' . $count1, $sysName);
                $sheet2->setCellValue('E' . $count1, $elId);
                $sheet2->setCellValue('F' . $count1, $elName);
                $sheet2->setCellValue('G' . $count1, $record->riskRating);
                $count1++;
            }

            $sheet3 = $spreadsheet->createSheet();
            $sheet3->setTitle('Systems');

            for($i='A'; $i < 'Z' ; $i++){ 
                $sheet3->getColumnDimension($i)->setAutoSize(true);
            }

            setDataToField($sheet3, 'ID', 'A1', 'A1', 'A1', true, 12, true , 'ffffff','000000');
            setDataToField($sheet3, 'System Name', 'B1', 'B1', 'B1', true, 12, true , 'ffffff','000000');

            $row = 2;
            $scoringSystemIds = array_unique(array_column($recordsDetailInspectionScoringOnline, 'systemId'));
            if(!empty($system)){
                foreach($system as $sysRow){
                    if(in_array($sysRow->id, $scoringSystemIds)){
                        $sheet3->setCellValue('A'.$row, $sysRow->id);
                        $sheet3->setCellValue('B'.$row, trim($sysRow->name));
                        $row++;
                    }
                        
                }
            }

            $sheet4 = $spreadsheet->createSheet();
            $sheet4->setTitle('Elements');

            for($i='A'; $i < 'Z' ; $i++){ 
                $sheet4->getColumnDimension($i)->setAutoSize(true);
            }

            setDataToField($sheet4, 'ID', 'A1', 'A1', 'A1', true, 12, true , 'ffffff','000000');
            setDataToField($sheet4, 'Elements', 'B1', 'B1', 'B1', true, 12, true , 'ffffff','000000');
            setDataToField($sheet4, 'System ID', 'C1', 'C1', 'C1', true, 12, true , 'ffffff','000000');

            // Group by system so that all elements for each system appear in consecutive rows.
            $groupedElements = [];
            if(!empty($element)){
                foreach($element as $elRow){
                    $groupedElements[$elRow->masterId][] = $elRow;
                }
            }

            $row = 2;
            $systemRanges = []; // masterId => [startRow, endRow]

            $scoringElementIds = array_unique(array_column($recordsDetailInspectionScoringOnline, 'elementId'));

            foreach($groupedElements as $masterId => $elRows){
                $start = $row;
                foreach($elRows as $elRow){
                    if(in_array($elRow->id, $scoringElementIds)){
                        $sheet4->setCellValue('A'.$row, $elRow->id);
                        $sheet4->setCellValue('B'.$row, trim($elRow->element));
                        $sheet4->setCellValue('C'.$row, $elRow->masterId);
                        $row++;
                    }
                }
                $systemRanges[$masterId] = [$start, $row - 1];
            }

            foreach($systemRanges as $masterId => $range){
                $cleanId = (int) $masterId;
                $rangeAddress = "Elements!\$B\${$range[0]}:\$B\${$range[1]}";
                $definedName = new \PhpOffice\PhpSpreadsheet\NamedRange('SYS_'.$cleanId, $sheet4, $rangeAddress, false);
                $spreadsheet->addNamedRange($definedName);
            }

            $spreadsheet->setActiveSheetIndex(0);
        }
        
        if($templateName == 'RBI-V3 Offline Assessment'){
            $safeMemberName = !empty($memberName) ? preg_replace('/[^A-Za-z0-9_\- ]/', '', $memberName) : 'Inspector';
            $currentDate = date('d-M-Y');

            $filename = $templateName . ' - ' . $safeMemberName . ' - ' . $currentDate . '.xlsx';
        }else{
            $filename = $templateName . '.xlsx';
        }
        // Set headers for download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Expires: 0');
        header('Pragma: public');

        $writer = new Xlsx($spreadsheet);
        $writer->setPreCalculateFormulas(true);
        $writer->save('php://output');
        exit;
    }

}    