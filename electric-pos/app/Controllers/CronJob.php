<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\MyModel;
use App\Models\LoginModel; // Ensure the LoginModel exists

//This CronJob Controller use in RBI Tools for Rest API's....
class CronJob extends ResourceController
{   
    //public function __construct() is used to initialize the controller, setting up necessary configurations or loading models, libraries, and helpers before any other methods are called.
    public function __construct()
    {
        date_default_timezone_set("Asia/Karachi");
        $this->myModel = new MyModel(); // Initialize the MyModel
        $this->loginModel = new LoginModel(); // Initialize the LoginModel
    }
    
    // function fileMove is used to save the attachment files in the database...
    // explain parameters...
    //$lable : used for get attach file size 
    //$fileName : used for get attach file name
    //$path: used to which path attach file store
    //$folder : used for get which folder save the file.
    function fileMove($label, $fileName, $path, $folder)
    {  
        $db = \Config\Database::connect(); // Load the database connection
        $session = \Config\Services::session(); // Load session service 

        $file = $label.'_'.rand(0,999999);
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
    // The End fileMove function //


    //The viewUploader() function is the main function for the REST API in this system. It can be used to load all module data in JSON format.
    //$view = NULL: This parameter is used to specify which module's data to retrieve.
    //$action = NULL: This parameter is used to specify which action to perform, such as select, insert, update, or delete.
    //$id = NULL: This parameter is used to specify which row ID to update or insert.
    public function viewUploader($view = NULL, $action = NULL, $id = NULL)
    {   

        $db = \Config\Database::connect(); // Load the database connection
        $session = \Config\Services::session(); // Load session service

        $this->dateFormat = 'd-M-y';
        $this->dateTimeFormat = 'd-M-y H:i';
        $this->dateTimeFullFormat = 'd-M-y H:i:s';

        $data = $this->request->getPost();
        if(!$data){
            json_output(200, array(array('output' => 'Nothing Posted')));
            return;
        }
        $this->userId = @$data['userId'];
        $this->dateTimeFormat = 'd-M-y H:i';
        $this->dateTimeFullFormat = 'd-M-y H:i:s';
        if($data['key'] == 'KeyfR1DL2MTtlgCIz29NNo1pgHAPI7UO.'){
            unset($data['key']);
            unset($data['userId']);
            $table = 'tbl_'.$view;
            if($action == 'select'){
                if ($view == 'login') {
                    // Build the query
                    $query = $db->table('tbls_user as BaseTbl')->select('BaseTbl.id, BaseTbl.email, BaseTbl.userName, BaseTbl.password, BaseTbl.profilepic, Country.id as countryId, Country.countryName, Country.countryFlag, BaseTbl.status, Role.id as roleId, Role.department, Role.designation, BaseTbl.pin')->join('tbls_role as Role', 'Role.id = BaseTbl.roleId', 'left')->join('tbls_country as Country', 'Country.id = BaseTbl.countryId', 'left')->where('BaseTbl.email', $data['email'] ?? null)->where('BaseTbl.isDeleted', 0)->get();

                    $user = $query->getResult();

                    if (!empty($user)) {
                        // Verify password
                        if (password_verify($data['password'] ?? '', $user[0]->password)) {
                            return $this->response->setStatusCode(200)->setJSON($user);
                        } else {
                            return $this->response->setStatusCode(400)->setJSON([
                                'output' => 'Email or password mismatch'
                            ]);
                        }
                    } else {
                        // Handle different cases
                        if (count($user) === 0) {
                            return $this->response->setStatusCode(400)->setJSON([
                                'output' => 'Account associated with this email does not appear to be registered here.'
                            ]);
                        } elseif ($user[0]->status === 'Inactive') {
                            return $this->response->setStatusCode(400)->setJSON([
                                'output' => 'Your account is temporarily inactive. Please contact Admin for assistance.'
                            ]);
                        } else {
                            return $this->response->setStatusCode(400)->setJSON([
                                'output' => 'Invalid arguments'
                            ]);
                        }
                    }
                }
                else if ($view == 'resetPassword') {
                    $result = 0;

                    // Fetch user data
                    $user = $db->table('tbls_user as BaseTbl')
                        ->select('BaseTbl.id, BaseTbl.email, BaseTbl.userName')
                        ->where('BaseTbl.email', $data['email'] ?? null) // Use null coalescing operator
                        ->get()
                        ->getRow();

                    if ($user) {
                        $email = $user->email;
                        $userName = $user->userName;

                        // Validate token length
                        if (isset($data['token']) && strlen($data['token']) == 64) {
                            $mailData = [
                                'from' => 'RBI',
                                'subject' => 'RBI | Account Reset Password Confirmation',
                                'title' => "Greetings, $userName!",
                                'message' => "Are you trying to reset your RBI Account Password?<br>Please click the following link to confirm your reset password:<br>
                                    <a href='" . base_url("generatePassword/" . $data['token']) . "'>" . $data['token'] . "</a>",
                                'email' => $email,
                            ];

                            // Send email
                            $sendStatus = mailSend($mailData); // Ensure this helper is implemented correctly
                            if ($sendStatus) {
                                $session->set([
                                    'email_token' => $data['token'],
                                    'email_email' => $email,
                                ]);
                                $result = 1;
                                return $this->response->setStatusCode(200)->setJSON([
                                    'output' => 'Reset Password Confirmation Email has been sent. Please check your Email.'
                                ]);
                            } else {
                                return $this->response->setStatusCode(200)->setJSON([
                                    'output' => 'Email not sent.'
                                ]);
                            }
                        } else {
                            return $this->response->setStatusCode(200)->setJSON([
                                'output' => 'Invalid Token.'
                            ]);
                        }
                    } else {
                        return $this->response->setStatusCode(400)->setJSON([
                            'output' => 'No record found.'
                        ]);
                    }
                }
                else if ($view == 'contact') {
                    if (!empty($data['email']) && !empty($data['message']) && !empty($data['name'])) {
                        $emailService = \Config\Services::email(); // Load Email service

                        // Set email configuration
                        $emailService->setFrom($data['email'], $data['name']);
                        $emailService->setTo('muhammad.asghar@gmail.com');
                        $emailService->setSubject('RBI | Feedback');
                        $emailService->setMessage($data['message']);

                        if ($emailService->send()) {
                            return $this->response->setStatusCode(200)->setJSON([
                                'output' => 'Email sent.'
                            ]);
                        } else {
                            return $this->response->setStatusCode(500)->setJSON([
                                'output' => 'Email not sent.'
                            ]);
                        }
                    } else {
                        return $this->response->setStatusCode(400)->setJSON([
                            'output' => 'Invalid or missing data. Please check your input.'
                        ]);
                    }
                }
                else if($view == 'system'){
                    if(@$data['lang'] == 'en'){
                        $queryData = $this->myModel->myGet($table, 'BaseTbl.id, BaseTbl.name', 'BaseTbl.status = "Active" AND BaseTbl.category = "'.@$data['category'].'"', 'BaseTbl.id', 'asc');
                        if (!empty($queryData)) {
                            return $this->response->setStatusCode(200)->setJSON($queryData);
                        } else {
                            return $this->response->setStatusCode(400)->setJSON(['output' => 'No record found']);
                        }
                    }
                    if(@$data['lang'] == 'fr'){
                        $queryData = $this->myModel->myGet($table, 'BaseTbl.id, BaseTbl.name_fr as name', 'BaseTbl.status = "Active" AND BaseTbl.category = "'.@$data['category'].'"', 'BaseTbl.id', 'asc');
                        if (!empty($queryData)) {
                            return $this->response->setStatusCode(200)->setJSON($queryData);
                        } else {
                            return $this->response->setStatusCode(400)->setJSON(['output' => 'No record found']);
                        }
                    }
                }
                else if($view == 'systemelement'){
                    if(@$data['lang'] == 'en'){
                        if(@$data['category'] == 'GMP'){
                            $queryData = $this->myModel->myGet($table, 'BaseTbl.id, BaseTbl.masterId, BaseTbl.systemSubType, BaseTbl.element', 'BaseTbl.status = "Active" AND BaseTbl.masterId IN ( SELECT id FROM tbl_system WHERE category = "GMP")', 'BaseTbl.id', 'asc');
                            if (!empty($queryData)) {
                                return $this->response->setStatusCode(200)->setJSON($queryData);
                            } else {
                                return $this->response->setStatusCode(400)->setJSON(['output' => 'No record found']);
                            }
                        }
                        if(@$data['category'] == 'GSDP'){
                            $queryData = $this->myModel->myGet($table, 'BaseTbl.id, BaseTbl.masterId, BaseTbl.systemSubType, BaseTbl.element', 'BaseTbl.status = "Active" AND BaseTbl.masterId IN ( SELECT id FROM tbl_system WHERE category = "GSDP")', 'BaseTbl.id', 'asc');
                            if (!empty($queryData)) {
                                return $this->response->setStatusCode(200)->setJSON($queryData);
                            } else {
                                return $this->response->setStatusCode(400)->setJSON(['output' => 'No record found']);
                            }
                        }
                    }
                    if(@$data['lang'] == 'fr'){
                        if(@$data['category'] == 'GMP'){
                            $queryData = $this->myModel->myGet($table, 'BaseTbl.id, BaseTbl.masterId, BaseTbl.systemSubType_fr as systemSubType, BaseTbl.element_fr as element', 'BaseTbl.status = "Active" AND BaseTbl.masterId IN ( SELECT id FROM tbl_system WHERE category = "GMP")', 'BaseTbl.id', 'asc');
                            if (!empty($queryData)) {
                                return $this->response->setStatusCode(200)->setJSON($queryData);
                            } else {
                                return $this->response->setStatusCode(400)->setJSON(['output' => 'No record found']);
                            }
                        }
                        if(@$data['category'] == 'GSDP'){
                            $queryData = $this->myModel->myGet($table, 'BaseTbl.id, BaseTbl.masterId, BaseTbl.systemSubType_fr as systemSubType, BaseTbl.element_fr as element', 'BaseTbl.status = "Active" AND BaseTbl.masterId IN ( SELECT id FROM tbl_system WHERE category = "GSDP")', 'BaseTbl.id', 'asc');
                            if (!empty($queryData)) {
                                return $this->response->setStatusCode(200)->setJSON($queryData);
                            } else {
                                return $this->response->setStatusCode(400)->setJSON(['output' => 'No record found']);
                            }
                        }
                    }
                }
                else if($view == 'gmpschedule'){
                    $queryData = $this->myModel->myGet('tbl_gmpinspection', 'BaseTbl.*, (SELECT tbl_panelpool.userId FROM tbl_gmpinspectionmemberdetail LEFT JOIN tbl_panelpool ON tbl_panelpool.id = tbl_gmpinspectionmemberdetail.memberId WHERE tbl_gmpinspectionmemberdetail.masterId = BaseTbl.id AND tbl_gmpinspectionmemberdetail.role = "Lead" ORDER BY tbl_gmpinspectionmemberdetail.id DESC LIMIT 1) as leadUserId, (SELECT tbl_panelpool.memberName FROM tbl_gmpinspectionmemberdetail LEFT JOIN tbl_panelpool ON tbl_panelpool.id = tbl_gmpinspectionmemberdetail.memberId WHERE tbl_gmpinspectionmemberdetail.masterId = BaseTbl.id AND tbl_gmpinspectionmemberdetail.role = "Lead" ORDER BY tbl_gmpinspectionmemberdetail.id DESC LIMIT 1) as leadUserName', 'BaseTbl.status IN ("Draft", "Scheduled")', 'BaseTbl.id', 'desc');
                    if (!empty($queryData)) {
                        return $this->response->setStatusCode(200)->setJSON($queryData);
                    } else {
                        return $this->response->setStatusCode(200)->setJSON(['output' => 'No record found']);
                    }
                }
                else if($view == 'gmpexecution'){
                    $queryData =$this->myModel->myGet('tbl_gmpinspection', 'BaseTbl.*, (SELECT tbl_panelpool.userId FROM tbl_gmpinspectionmemberdetail LEFT JOIN tbl_panelpool ON tbl_panelpool.id = tbl_gmpinspectionmemberdetail.memberId WHERE tbl_gmpinspectionmemberdetail.masterId = BaseTbl.id AND tbl_gmpinspectionmemberdetail.role = "Lead" ORDER BY tbl_gmpinspectionmemberdetail.id DESC LIMIT 1) as leadUserId, (SELECT tbl_panelpool.memberName FROM tbl_gmpinspectionmemberdetail LEFT JOIN tbl_panelpool ON tbl_panelpool.id = tbl_gmpinspectionmemberdetail.memberId WHERE tbl_gmpinspectionmemberdetail.masterId = BaseTbl.id AND tbl_gmpinspectionmemberdetail.role = "Lead" ORDER BY tbl_gmpinspectionmemberdetail.id DESC LIMIT 1) as leadUserName', 'BaseTbl.status <> "Draft"', 'BaseTbl.inspectionFromDate', 'asc');
                    if (!empty($queryData)) {
                        return $this->response->setStatusCode(200)->setJSON($queryData);
                    } else {
                        return $this->response->setStatusCode(200)->setJSON(['output' => 'No record found']);
                    }
                }
                else if($view == 'gmpexecutionscoringdetail'){
                    $queryData =$this->myModel->myGet('tbl_gmpinspectionscoringdetail', 'BaseTbl.*', 'BaseTbl.masterId ='.$id, 'BaseTbl.id', 'desc');
                    if (!empty($queryData)) {
                        return $this->response->setStatusCode(200)->setJSON($queryData);
                    } else {
                        return $this->response->setStatusCode(200)->setJSON(['output' => 'No record found']);
                    }
                }
                else if($view == 'gmpexecutionassessmentdetail'){
                    $queryData =$this->myModel->myGet('tbl_gmpinspectionassessmentdetail', 'BaseTbl.*', 'BaseTbl.masterId ='.$id, 'BaseTbl.id', 'desc');
                    if (!empty($queryData)) {
                        return $this->response->setStatusCode(200)->setJSON($queryData);
                    } else {
                        return $this->response->setStatusCode(200)->setJSON(['output' => 'No record found']);
                    }
                }
                else if($view == 'gmpinspectionmemberdetail'){
                    $queryData =$this->myModel->myGet($table, 'BaseTbl.*', 'BaseTbl.masterId = "'.@$data['masterId'].'"', 'BaseTbl.id', 'desc');
                    if (!empty($queryData)) {
                        return $this->response->setStatusCode(200)->setJSON($queryData);
                    } else {
                        return $this->response->setStatusCode(200)->setJSON(['output' => 'No record found']);
                    }
                }
                else if($view == 'gmpexecutionriskleveldetail'){
                    $queryData =$this->myModel->myGet('tbl_gmpinspectionriskleveldetail', 'BaseTbl.*', 'BaseTbl.masterId ='.$id, 'BaseTbl.id', 'desc');
                    if (!empty($queryData)) {
                        return $this->response->setStatusCode(200)->setJSON($queryData);
                    } else {
                        return $this->response->setStatusCode(200)->setJSON(['output' => 'No record found']);
                    }
                }
                else if($view == 'gdpexecution'){
                    $queryData =$this->myModel->myGet('tbl_gdpinspection', 'BaseTbl.*, (SELECT tbl_panelpool.userId FROM tbl_gdpinspectionmemberdetail LEFT JOIN tbl_panelpool ON tbl_panelpool.id = tbl_gdpinspectionmemberdetail.memberId WHERE tbl_gdpinspectionmemberdetail.masterId = BaseTbl.id AND tbl_gdpinspectionmemberdetail.role = "Lead" ORDER BY tbl_gdpinspectionmemberdetail.id DESC LIMIT 1) as leadUserId, (SELECT tbl_panelpool.memberName FROM tbl_gdpinspectionmemberdetail LEFT JOIN tbl_panelpool ON tbl_panelpool.id = tbl_gdpinspectionmemberdetail.memberId WHERE tbl_gdpinspectionmemberdetail.masterId = BaseTbl.id AND tbl_gdpinspectionmemberdetail.role = "Lead" ORDER BY tbl_gdpinspectionmemberdetail.id DESC LIMIT 1) as leadUserName', 'BaseTbl.status <> "Draft" AND BaseTbl.leadUserId = '.@$data['userId1'], 'BaseTbl.inspectionFromDate', 'asc');
                    if (!empty($queryData)) {
                        return $this->response->setStatusCode(200)->setJSON($queryData);
                    } else {
                        return $this->response->setStatusCode(400)->setJSON(['output' => 'No record found']);
                    }
                }
                else if($view == 'gdpexecutionassessmentdetail'){
                    $queryData =$this->myModel->myGet('tbl_gdpinspectionassessmentdetail', 'BaseTbl.*', 'BaseTbl.masterId ='.$id, 'BaseTbl.id', 'desc');
                    if (!empty($queryData)) {
                        return $this->response->setStatusCode(200)->setJSON($queryData);
                    } else {
                        return $this->response->setStatusCode(400)->setJSON(['output' => 'No record found']);
                    }
                }
                else if($view == 'gdpexecutionscoringdetail'){
                    $queryData =$this->myModel->myGet('tbl_gdpinspectionscoringdetail', 'BaseTbl.*', 'BaseTbl.masterId ='.$id, 'BaseTbl.id', 'desc');
                    if (!empty($queryData)) {
                        return $this->response->setStatusCode(200)->setJSON($queryData);
                    } else {
                        return $this->response->setStatusCode(400)->setJSON(['output' => 'No record found']);
                    }
                }
                else if($view == 'gmpinspectiondefleveldetail'){
                    $queryData =$this->myModel->myGet('tbl_gmpinspectiondefleveldetail', 'BaseTbl.*', 'BaseTbl.masterId ='.$id, 'BaseTbl.id', 'desc');
                    if (!empty($queryData)) {
                        return $this->response->setStatusCode(200)->setJSON($queryData);
                    } else {
                        return $this->response->setStatusCode(400)->setJSON(['output' => 'No record found']);
                    }
                }
                else if($view == 'gdpinspectiondefleveldetail'){
                    $queryData =$this->myModel->myGet('tbl_gdpinspectiondefleveldetail', 'BaseTbl.*', 'BaseTbl.masterId ='.$id, 'BaseTbl.id', 'desc');
                    if (!empty($queryData)) {
                        return $this->response->setStatusCode(200)->setJSON($queryData);
                    } else {
                        return $this->response->setStatusCode(400)->setJSON(['output' => 'No record found']);
                    }
                }
                else if($view == 'gdpinspectionmemberdetail'){
                    $queryData =$this->myModel->myGet($table, 'BaseTbl.*, (SELECT tbl_panelpool.memberName FROM tbl_panelpool WHERE tbl_panelpool.id = BaseTbl.memberId ORDER BY tbl_panelpool.id DESC LIMIT 1) as memberName', 'BaseTbl.masterId = "'.@$data['masterId'].'"', 'BaseTbl.id', 'desc');
                    if (!empty($queryData)) {
                        return $this->response->setStatusCode(200)->setJSON($queryData);
                    } else {
                        return $this->response->setStatusCode(400)->setJSON(['output' => 'No record found']);
                    }
                }
                else if($view == 'gdpexecutionriskleveldetail'){
                    $queryData =$this->myModel->myGet('tbl_gdpinspectionriskleveldetail', 'BaseTbl.*', 'BaseTbl.masterId ='.$id, 'BaseTbl.id', 'desc');
                    if (!empty($queryData)) {
                        return $this->response->setStatusCode(200)->setJSON($queryData);
                    } else {
                        return $this->response->setStatusCode(400)->setJSON(['output' => 'No record found']);
                    }
                }
                else if($view == 'gmpreport'){
                    $queryData =$this->myModel->myGet('tbl_gmpinspection', 'BaseTbl.*, (SELECT tbl_panelpool.userId FROM tbl_gmpinspectionmemberdetail LEFT JOIN tbl_panelpool ON tbl_panelpool.id = tbl_gmpinspectionmemberdetail.memberId WHERE tbl_gmpinspectionmemberdetail.masterId = BaseTbl.id AND tbl_gmpinspectionmemberdetail.role = "Lead" ORDER BY tbl_gmpinspectionmemberdetail.id DESC LIMIT 1) as leadUserId, (SELECT tbl_panelpool.memberName FROM tbl_gmpinspectionmemberdetail LEFT JOIN tbl_panelpool ON tbl_panelpool.id = tbl_gmpinspectionmemberdetail.memberId WHERE tbl_gmpinspectionmemberdetail.masterId = BaseTbl.id AND tbl_gmpinspectionmemberdetail.role = "Lead" ORDER BY tbl_gmpinspectionmemberdetail.id DESC LIMIT 1) as leadUserName', 'BaseTbl.status = "Completed"', 'BaseTbl.inspectionFromDate', 'asc');
                    if (!empty($queryData)) {
                        return $this->response->setStatusCode(200)->setJSON($queryData);
                    } else {
                        return $this->response->setStatusCode(400)->setJSON(['output' => 'No record found']);
                    }
                }
                else if($view == 'gmpcapa'){
                    $queryData =$this->myModel->myGet('tbl_gmpinspection', 'BaseTbl.*, (SELECT tbl_panelpool.userId FROM tbl_gmpinspectionmemberdetail LEFT JOIN tbl_panelpool ON tbl_panelpool.id = tbl_gmpinspectionmemberdetail.memberId WHERE tbl_gmpinspectionmemberdetail.masterId = BaseTbl.id AND tbl_gmpinspectionmemberdetail.role = "Lead" ORDER BY tbl_gmpinspectionmemberdetail.id DESC LIMIT 1) as leadUserId, (SELECT tbl_panelpool.memberName FROM tbl_gmpinspectionmemberdetail LEFT JOIN tbl_panelpool ON tbl_panelpool.id = tbl_gmpinspectionmemberdetail.memberId WHERE tbl_gmpinspectionmemberdetail.masterId = BaseTbl.id AND tbl_gmpinspectionmemberdetail.role = "Lead" ORDER BY tbl_gmpinspectionmemberdetail.id DESC LIMIT 1) as leadUserName', 'BaseTbl.status = "Under CAPA"', 'BaseTbl.inspectionFromDate', 'asc');
                    if (!empty($queryData)) {
                        return $this->response->setStatusCode(200)->setJSON($queryData);
                    } else {
                        return $this->response->setStatusCode(400)->setJSON(['output' => 'No record found']);
                    }
                }
                else if($view == 'gdpschedule'){
                    $queryData =$this->myModel->myGet('tbl_gdpinspection', 'BaseTbl.*, (SELECT tbl_panelpool.userId FROM tbl_gmpinspectionmemberdetail LEFT JOIN tbl_panelpool ON tbl_panelpool.id = tbl_gmpinspectionmemberdetail.memberId WHERE tbl_gmpinspectionmemberdetail.masterId = BaseTbl.id AND tbl_gmpinspectionmemberdetail.role = "Lead" ORDER BY tbl_gmpinspectionmemberdetail.id DESC LIMIT 1) as leadUserId, (SELECT tbl_panelpool.memberName FROM tbl_gmpinspectionmemberdetail LEFT JOIN tbl_panelpool ON tbl_panelpool.id = tbl_gmpinspectionmemberdetail.memberId WHERE tbl_gmpinspectionmemberdetail.masterId = BaseTbl.id AND tbl_gmpinspectionmemberdetail.role = "Lead" ORDER BY tbl_gmpinspectionmemberdetail.id DESC LIMIT 1) as leadUserName', 'BaseTbl.status IN ("Draft", "Scheduled")', 'BaseTbl.id', 'desc');
                    if (!empty($queryData)) {
                        return $this->response->setStatusCode(200)->setJSON($queryData);
                    } else {
                        return $this->response->setStatusCode(400)->setJSON(['output' => 'No record found']);
                    }
                }
                else if($view == 'gmpexecution'){
                    $queryData =$this->myModel->myGet('tbl_gdpinspection', 'BaseTbl.*, (SELECT tbl_panelpool.userId FROM tbl_gmpinspectionmemberdetail LEFT JOIN tbl_panelpool ON tbl_panelpool.id = tbl_gmpinspectionmemberdetail.memberId WHERE tbl_gmpinspectionmemberdetail.masterId = BaseTbl.id AND tbl_gmpinspectionmemberdetail.role = "Lead" ORDER BY tbl_gmpinspectionmemberdetail.id DESC LIMIT 1) as leadUserId, (SELECT tbl_panelpool.memberName FROM tbl_gmpinspectionmemberdetail LEFT JOIN tbl_panelpool ON tbl_panelpool.id = tbl_gmpinspectionmemberdetail.memberId WHERE tbl_gmpinspectionmemberdetail.masterId = BaseTbl.id AND tbl_gmpinspectionmemberdetail.role = "Lead" ORDER BY tbl_gmpinspectionmemberdetail.id DESC LIMIT 1) as leadUserName', 'BaseTbl.status <> "Draft" Having leadUserId = '.@$data['userId1'], 'BaseTbl.inspectionFromDate', 'asc');
                    if (!empty($queryData)) {
                        return $this->response->setStatusCode(200)->setJSON($queryData);
                    } else {
                        return $this->response->setStatusCode(400)->setJSON(['output' => 'No record found']);
                    }
                }
                else if($view == 'gdpreport'){
                    $queryData =$this->myModel->myGet('tbl_gdpinspection', 'BaseTbl.*, (SELECT tbl_panelpool.userId FROM tbl_gmpinspectionmemberdetail LEFT JOIN tbl_panelpool ON tbl_panelpool.id = tbl_gmpinspectionmemberdetail.memberId WHERE tbl_gmpinspectionmemberdetail.masterId = BaseTbl.id AND tbl_gmpinspectionmemberdetail.role = "Lead" ORDER BY tbl_gmpinspectionmemberdetail.id DESC LIMIT 1) as leadUserId, (SELECT tbl_panelpool.memberName FROM tbl_gmpinspectionmemberdetail LEFT JOIN tbl_panelpool ON tbl_panelpool.id = tbl_gmpinspectionmemberdetail.memberId WHERE tbl_gmpinspectionmemberdetail.masterId = BaseTbl.id AND tbl_gmpinspectionmemberdetail.role = "Lead" ORDER BY tbl_gmpinspectionmemberdetail.id DESC LIMIT 1) as leadUserName', 'BaseTbl.status = "Completed"', 'BaseTbl.inspectionFromDate', 'asc');
                    if (!empty($queryData)) {
                        return $this->response->setStatusCode(200)->setJSON($queryData);
                    } else {
                        return $this->response->setStatusCode(400)->setJSON(['output' => 'No record found']);
                    }
                }

                else if($view == 'gdpcapa'){
                    $queryData =$this->myModel->myGet('tbl_gdpinspection', 'BaseTbl.*, (SELECT tbl_panelpool.userId FROM tbl_gmpinspectionmemberdetail LEFT JOIN tbl_panelpool ON tbl_panelpool.id = tbl_gmpinspectionmemberdetail.memberId WHERE tbl_gmpinspectionmemberdetail.masterId = BaseTbl.id AND tbl_gmpinspectionmemberdetail.role = "Lead" ORDER BY tbl_gmpinspectionmemberdetail.id DESC LIMIT 1) as leadUserId, (SELECT tbl_panelpool.memberName FROM tbl_gmpinspectionmemberdetail LEFT JOIN tbl_panelpool ON tbl_panelpool.id = tbl_gmpinspectionmemberdetail.memberId WHERE tbl_gmpinspectionmemberdetail.masterId = BaseTbl.id AND tbl_gmpinspectionmemberdetail.role = "Lead" ORDER BY tbl_gmpinspectionmemberdetail.id DESC LIMIT 1) as leadUserName', 'BaseTbl.status = "Under CAPA"', 'BaseTbl.inspectionFromDate', 'asc');
                    if (!empty($queryData)) {
                        return $this->response->setStatusCode(200)->setJSON($queryData);
                    } else {
                        return $this->response->setStatusCode(400)->setJSON(['output' => 'No record found']);
                    }
                }
                else if($view == 'inspectiontype'){
                    $queryData =$this->myModel->myGet($table, 'BaseTbl.*', 'BaseTbl.status = "Active" AND BaseTbl.category = "'.@$data['category'].'"', 'BaseTbl.id', 'desc');
                    if (!empty($queryData)) {
                        return $this->response->setStatusCode(200)->setJSON($queryData);
                    } else {
                        return $this->response->setStatusCode(400)->setJSON(['output' => 'No record found']);
                    }
                }
                else if($view == 'risklevel'){
                    $queryData =$this->myModel->myGet($table, 'BaseTbl.*', 'BaseTbl.status = "Active" AND BaseTbl.category = "'.@$data['category'].'"', 'BaseTbl.id', 'desc');
                    if (!empty($queryData)) {
                        return $this->response->setStatusCode(200)->setJSON($queryData);
                    } else {
                        return $this->response->setStatusCode(400)->setJSON(['output' => 'No record found']);
                    }
                }
                else if($view == 'profilepic'){
                    $queryData =$this->myModel->myGet('tbls_user', 'BaseTbl.id, BaseTbl.profilepic', 'BaseTbl.roleId = 3', 'BaseTbl.id', 'desc');
                    if (!empty($queryData)) {
                        return $this->response->setStatusCode(200)->setJSON($queryData);
                    } else {
                        return $this->response->setStatusCode(400)->setJSON(['output' => 'No record found']);
                    }
                }
                else if($view == 'panelpool'){
                    $queryData =$this->myModel->myGet('tbl_panelpool', 'BaseTbl.*', 'BaseTbl.id = '.@$data['memberId'].'', 'BaseTbl.id', 'desc');
                    if (!empty($queryData)) {
                        return $this->response->setStatusCode(200)->setJSON($queryData);
                    } else {
                        return $this->response->setStatusCode(200)->setJSON(['output' => 'No record found']);
                    }
                }
                else if($view == 'facility'){
                    $panelpool =$this->myModel->myGet('tbl_panelpool', 'BaseTbl.id', 'BaseTbl.userId = '.@$data['userId1'].'', 'BaseTbl.id', 'desc');
                    $assignedInspection =$this->myModel->myGet('tbl_gdpinspectionmemberdetail', ' Distinct BaseTbl.masterId', 'BaseTbl.memberId = '.$panelpool[0]->id, 'BaseTbl.id', 'desc');
                    @$facilityIds = '';
                    if(isset($assignedInspection)){
                        foreach($assignedInspection as $record0){
                            $inspectionFacility =$this->myModel->myGet('tbl_gdpinspection', 'BaseTbl.companyId', 'BaseTbl.id = '.$record0->masterId, 'BaseTbl.id', 'desc');
                                $facilityIds = $facilityIds.$inspectionFacility[0]->companyId.',';
                            
                        }
                    }
                    if(!empty($facilityIds)){
                    $queryData =$this->myModel->myGet('tbl_facility', 'BaseTbl.*', 'BaseTbl.id in ('.rtrim($facilityIds, ',').')', 'BaseTbl.id', 'desc');
                    }
                    if(empty($facilityIds)){
                    $queryData;
                    }
                    if (!empty($queryData)) {
                        return $this->response->setStatusCode(200)->setJSON($queryData);
                    } else {
                        return $this->response->setStatusCode(400)->setJSON(['output' => 'No record found']);
                    }
                }
                else{
                    $queryData = $this->myModel->myGet($table, 'BaseTbl.*', 'BaseTbl.status = "Active"', 'BaseTbl.id', 'asc');
                    if (!empty($queryData)) {
                        return $this->response->setStatusCode(200)->setJSON($queryData);
                    } else {
                        return $this->response->setStatusCode(400)->setJSON(['output' => 'No record found']);
                    }
                }
            }

            else if($action == 'insert'){
                if($view == 'gmpinspection' || $view == 'gdpinspection'){
                    $data['createdby'] = $this->userId;
                    $data['createddate'] = date($this->dateTimeFormat);
                    $data['updatedby'] = $this->userId;
                    $data['updateddate'] = date($this->dateTimeFormat);
                    $result = $this->loginModel->recordAjaxSave($data, $table);
                    if ($result > 0) {
                        return $this->response->setStatusCode(200)->setJSON(['output' => 'Data Inserted Successfully']);
                    } else {
                        return $this->response->setStatusCode(400)->setJSON(['output' => 'Data Not Inserted']);
                    }
                }    
                else{
                    return $this->response->setStatusCode(400)->setJSON(['output' => 'Access Denied']);
                }
            }

            else if($action == 'update'){
                if($view == 'gmpschedule' || $view == 'gmpexecution' || $view == 'gmpreport' || $view == 'gmpcapa'){
                    $data['updatedby'] = $this->userId;
                    $data['updateddate'] = date($this->dateTimeFormat);
                    
                    foreach ($data as $key => $row){
                        if(@$_FILES[$key]['tmp_name']){
                            $data[$key] = $this->fileMove('Attachment', $key, 'RBI', 'docs');
                        }
                    }

                    $recordsEdit = $this->myModel->myGet('tbl_gmpinspection', 'BaseTbl.*', 'BaseTbl.id ='.$id, 'BaseTbl.id', 'asc');
                    if(@$data['status'] == 'Draft'){
                        $data['status'] = $recordsEdit[0]->status;
                    }
                    if(@$data['status'] == 'Proceed'){
                        $stages = ['Draft', 'Scheduled', 'In Process', 'Completed', 'Under CAPA'];
                        $currentKey = array_search($recordsEdit[0]->status, $stages);
                        $data['status'] = $stages[$currentKey + 1];
                    }
                    $result = $this->loginModel->recordAjaxUpdate('id', $id, $data, 'tbl_gmpinspection');
                    if($result > 0){
                        return $this->response->setStatusCode(200)->setJSON(['output' => 'Data Updated Successfully']);
                    }
                    else{
                        return $this->response->setStatusCode(400)->setJSON(['output' => 'Data Not Updated']);
                    }
                }
                else if($view == 'gdpschedule' || $view == 'gdpexecution' || $view == 'gdpreport' || $view == 'gdpcapa'){
                    $data['updatedby'] = $this->userId;
                    $data['updateddate'] = date($this->dateTimeFormat);
                    
                    foreach ($data as $key => $row){
                        if(@$_FILES[$key]['tmp_name']){
                            $data[$key] = $this->fileMove('Attachment', $key, 'RBI', 'docs');
                        }
                    }
                    
                    $recordsEdit = $this->myModel->myGet('tbl_gdpinspection', 'BaseTbl.*', 'BaseTbl.id ='.$id, 'BaseTbl.id', 'asc');
                    if(@$data['status'] == 'Draft'){
                        $data['status'] = $recordsEdit[0]->status;
                    }
                    if(@$data['status'] == 'Proceed'){
                        $stages = ['Draft', 'Scheduled', 'In Process', 'Completed', 'Under CAPA'];
                        $currentKey = array_search($recordsEdit[0]->status, $stages);
                        $data['status'] = $stages[$currentKey + 1];
                    }
                    $result = $this->loginModel->recordAjaxUpdate('id', $id, $data, 'tbl_gdpinspection');
                    if($result > 0){
                        return $this->response->setStatusCode(200)->setJSON(['output' => 'Data Updated Successfully']);
                    }
                    else{
                        return $this->response->setStatusCode(200)->setJSON(['output' => 'Data Not Updated']);
                    }
                }
                else if($view == 'gmpexecutionscoringdetail'){
                    $data['updatedby'] = $this->userId;
                    $data['updateddate'] = date($this->dateTimeFormat);
                    $result = $this->loginModel->recordAjaxUpdate('id', $id, $data, 'tbl_gmpinspectionscoringdetail');
                    if($result > 0){
                        return $this->response->setStatusCode(400)->setJSON(['output' => 'Data Updated Successfully']);
                    }
                    else{
                        return $this->response->setStatusCode(400)->setJSON(['output' => 'Data Not Updated']);
                    }
                }
                 else if($view == 'gdpexecutionscoringdetail'){
                    $data['updatedby'] = $this->userId;
                    $data['updateddate'] = date($this->dateTimeFormat);
                    $result = $this->loginModel->recordAjaxUpdate('id', $id, $data, 'tbl_gdpinspectionscoringdetail');
                    if($result > 0){
                        return $this->response->setStatusCode(400)->setJSON(['output' => 'Data Updated Successfully']);
                    }
                    else{
                        return $this->response->setStatusCode(200)->setJSON(['output' => 'Data Not Updated']);
                    }
                }
                 else if($view == 'gmpexecutionassessmentdetail'){
                    $data['updatedby'] = $this->userId;
                    $data['updateddate'] = date($this->dateTimeFormat);
                    $result = $this->loginModel->recordAjaxUpdate('id', $id, $data, 'tbl_gmpinspectionassessmentdetail');
                    if($result > 0){
                        return $this->response->setStatusCode(200)->setJSON(['output' => 'Data Updated Successfully']);
                    }
                    else{
                        return $this->response->setStatusCode(200)->setJSON(['output' => 'Data Not Updated']);
                    }
                }
                 else if($view == 'gdpexecutionassessmentdetail'){
                    $data['updatedby'] = $this->userId;
                    $data['updateddate'] = date($this->dateTimeFormat);
                    $result = $this->loginModel->recordAjaxUpdate('id', $id, $data, 'tbl_gdpinspectionassessmentdetail');
                    if($result > 0){
                        return $this->response->setStatusCode(200)->setJSON(['output' => 'Data Updated Successfully']);
                    }
                    else{
                        return $this->response->setStatusCode(200)->setJSON(['output' => 'Data Not Updated']);
                    }
                }
                else if($view == 'gmpinspectiondefleveldetail'){
                    $data['updatedby'] = $this->userId;
                    $data['updateddate'] = date($this->dateTimeFormat);
                    $result = $this->loginModel->recordAjaxUpdate('id', $id, $data, 'tbl_gmpinspectiondefleveldetail');
                    if($result > 0){
                        return $this->response->setStatusCode(200)->setJSON(['output' => 'Data Updated Successfully']);
                    }
                    else{
                        return $this->response->setStatusCode(200)->setJSON(['output' => 'Data Not Updated']);
                    }
                }
                else if($view == 'gdpinspectiondefleveldetail'){
                    $data['updatedby'] = $this->userId;
                    $data['updateddate'] = date($this->dateTimeFormat);
                    $result = $this->loginModel->recordAjaxUpdate('id', $id, $data, 'tbl_gdpinspectiondefleveldetail');
                    if($result > 0){
                        return $this->response->setStatusCode(200)->setJSON(['output' => 'Data Updated Successfully']);
                    }
                    else{
                        return $this->response->setStatusCode(200)->setJSON(['output' => 'Data Not Updated']);
                    }
                }
                else if($view == 'company'){
                    //$data['updatedby'] = $this->userId;
                    $data['updateddate'] = date($this->dateTimeFormat);
                    $result = $this->loginModel->recordAjaxUpdate('id', $id, $data, 'tbl_company');
                    if($result > 0){
                        return $this->response->setStatusCode(200)->setJSON(['output' => 'Data Updated Successfully']);
                    }
                    else{
                        return $this->response->setStatusCode(200)->setJSON(['output' => 'Data Not Updated']);
                    }
                }
                else if($view == 'facility'){
                    //$data['updatedby'] = $this->userId;
                    $data['updateddate'] = date($this->dateTimeFormat);
                    $result = $this->loginModel->recordAjaxUpdate('id', $id, $data, 'tbl_facility');
                    if($result > 0){
                        return $this->response->setStatusCode(200)->setJSON(['output' => 'Data Updated Successfully']);
                    }
                    else{
                        return $this->response->setStatusCode(200)->setJSON(['output' => 'Data Not Updated']);
                    }
                }
                else{
                    return $this->response->setStatusCode(200)->setJSON(['output' => 'Access Denied']);
                }
            }
            else{
                return $this->response->setStatusCode(200)->setJSON(['output' => 'Invalid Arguments']);
            }
        }
        else{
            return $this->response->setStatusCode(200)->setJSON(['output' => 'Access Denied']);
        }      
    }
    /// ...The End viewUpload() function... ///
}
