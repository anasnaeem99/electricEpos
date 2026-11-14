<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <title>RBI - V2 | Landing Page</title>
  <link id="favicon" rel="icon" href="<?php echo base_url(); ?>uploads/company/RBI/logo/logo.png"/>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/adminlte.min.css">
  <style type="text/css">
    ::placeholder {
      color: black !important;
      opacity: 1!important;
    }

    .gmp-hero-side {
      padding: 8% 6% 8% 10%;
      display: flex;
      align-items: center;
      color: #f0f8ff;
      font-family: 'Source Sans Pro', Arial, sans-serif;
    }

    .gmp-intro-panel {
      background: rgba(255, 255, 255, 0.09);
      border: 1px solid rgba(255, 255, 255, 0.18);
      border-radius: 30px;
      padding: 30px 34px;
      box-shadow: 0 12px 40px rgba(0, 0, 0, 0.22);
      max-width: 540px;
      width: 100%;
    }

    .gmp-intro-brand {
      display: flex;
      align-items: center;
      gap: 18px;
      margin-bottom: 22px;
      padding-bottom: 18px;
      border-bottom: 1px solid rgba(255, 255, 255, 0.12);
    }

    .gmp-intro-logo {
      width: 76px;
      height: 76px;
      border-radius: 50%;
      box-shadow: 0 6px 16px rgba(0, 0, 0, 0.35);
      flex-shrink: 0;
      object-fit: cover;
    }

    .gmp-intro-brand-text {
      display: flex;
      flex-direction: column;
      gap: 4px;
    }

    .gmp-intro-title {
      margin: 0;
      font-size: 1.45rem;
      font-weight: 700;
      letter-spacing: 0.3px;
      color: #ffffff;
      line-height: 1.2;
    }

    .gmp-intro-subtitle {
      font-size: 0.92rem;
      font-weight: 500;
      color: rgba(200, 225, 255, 0.9);
      letter-spacing: 0.4px;
      text-transform: uppercase;
    }

    .gmp-intro-description {
      margin: 0;
      padding-left: 18px;
      border-left: 4px solid #5da3e8;
      font-size: 1.05rem;
      font-weight: 400;
      line-height: 1.75;
      color: rgba(240, 248, 255, 0.95);
      text-align: left;
    }

    .gmp-intro-tag {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 6px 14px;
      border-radius: 20px;
      background: rgba(93, 163, 232, 0.18);
      border: 1px solid rgba(93, 163, 232, 0.35);
      color: #d4e8ff;
      font-size: 0.82rem;
      font-weight: 600;
    }

    .gmp-intro-tag i {
      font-size: 0.75rem;
      color: #7ec0ff;
    }

    @media (max-width: 991px) {
      .gmp-hero-side {
        padding: 6% 5%;
        justify-content: center;
      }

      .gmp-intro-panel {
        max-width: 100%;
      }
    }
  </style>
</head>
<body class="hold-transition login-page" style="//background-image: linear-gradient(#ededed, #b7b7b7); overflow-x: hidden;">
  <?php
  $result = $loginModel->systemInfoGet();
  if(count($result) > 0)
  {
    foreach ($result as $res)
    { 
        $mode = $res->mode;
    }
  }
  ?>
  <div id="bigScreen" class="wrapper" style="width: 100%;">
    <div class="col-md-12 myPage">
      <div class="col-md-6" style="float: left;">
        <img style="width: -webkit-fill-available; border-top-left-radius: 1em 1em; border-top-right-radius: 1em 1em; border-bottom-left-radius: 1em 1em; border-bottom-right-radius: 1em 1em; //min-width: 80%; width: 30%; margin-top: 5%; box-shadow: 0px 0px 8px 3px #c9c9cd;" src="<?php echo base_url(); ?>uploads/company/RBI/logo/usp.jpg" class="img-fluid pull-right" alt="Logo">
      </div>
      <div class="col-md-6" style="float: left; text-align: right;">
        <img style="border-top-left-radius: 1em 1em; border-top-right-radius: 1em 1em; border-bottom-left-radius: 1em 1em; border-bottom-right-radius: 1em 1em; width: 30%; margin-top: 20px; position: relative; right: 20px; top: 10px; box-shadow: inset 0px -20px 20px 3px #c9c9cd;" src="<?php echo base_url(); ?>uploads/company/RBI/logo/logo.png" class="img-fluid pull-right" alt="Logo">
      </div>

      <div class="col-md-12 myPage0" style="position: relative; background: #284576; display: inline-flex; width: 100%;  height:80vh; //border-radius: 0 60em 0em 0; box-shadow: inset 0px 0px 20px 0px #151515;">
        <div class="col-md-6 gmp-hero-side">
          <div class="gmp-intro-panel">
            <div class="gmp-intro-brand">
              <img src="<?php echo base_url(); ?>uploads/company/RBI/logo/gmp.png" class="gmp-intro-logo" alt="GMP Logo" aria-label="GMP Logo">
              <div class="gmp-intro-brand-text">
                <h2 class="gmp-intro-title">RBI Tool</h2>
                <span class="gmp-intro-subtitle">GMP Inspection</span>
              </div>
            </div>
            <p class="gmp-intro-description">
              <span class="translateMe">The GMP inspection tool is system-based quality assessment tool for regulators and manufacturers that is designed to measure the extent of compliance in pharmaceutical manufacturing and quality control qualitatively and quantitatively.</span>
            </p>
          </div>
        </div>
        <div class="col-md-6" style="padding-top: 9%;">
          <center>
                <div id="a1" class="img-header">
                <div class="login-box" style="border-radius:2%; //background-color: #f6f6f6; box-shadow: 0px 0px 20px 0px #151515; margin: auto; border-top-left-radius: 100em 7em;
                  border-top-right-radius: 100em 7em;">
                  <!-- /.login-logo -->
                  <div class="card card-outline card-primary" style="//border-top: 3px solid #fd0805; border-top: 20px solid #f26246; background-color: #fff; border-top-left-radius: 100em 7em; border-top-right-radius: 100em 7em;">
                    <div class="card-body" style="background-color: #e3ceca;">
                      <p class="login-box-msg"><?php if($mode == 'Maintenance'){echo '<span class="text-danger"> <span style="border-bottom: 3px solid #dc3545; border-bottom-left-radius: 1em 100em; border-bottom-right-radius: 1em 100em; border-bottom-width: thick; padding: 10px;">● <i class="fas fa-cog fa-spin fa-6x"></i> ●</span></span>';}else{echo '<span style="border-bottom: 3px solid #10a659; border-bottom-left-radius: 100em 50em; border-bottom-right-radius: 100em 50em; border-bottom-width: thick; padding: 10px;"><span style="color:#10a659;">●</span> <span class="translateMe">Sign In</span> <span style="color:#10a659;">●</span></span>';} ?></p>

                      <form onsubmit="loginSound();hide();" action="<?php echo base_url(); ?>loginMe" method="post" <?php if($mode == 'Maintenance'){echo 'style="opacity:0;"';} ?>>
                        <div class="input-group mb-3">
                          <input type="email" name="email" onfocus="this.removeAttribute('readonly');" value="<?php if($session->get('myEmail')){echo $session->get('myEmail');} else{echo '' ;}?>" class="form-control" placeholder="Email" required>
                          <div class="input-group-append">
                            <div class="input-group-text">
                              <span class="fas fa-envelope"></span>
                            </div>
                          </div>
                        </div>
                        <div class="input-group mb-3">
                          <input type="password" name="password" onfocus="this.removeAttribute('readonly');" value="<?php if($session->get('myPassword')){echo $session->get('myPassword');}?>" class="form-control" placeholder="Password" required>
                          <div class="input-group-append">
                            <div class="input-group-text">
                              <span class="fas fa-lock"></span>
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-7">
                            
                          </div>

                          <!-- /.col -->
                          <div class="col-5">
                            <button type="submit" class="btn btn-primary btn-block" style="background-color: #006ee5 !important; border-color: #006ee5 !important;"><span class="translateMe">Sign In</span></button>
                          </div>
                          <!-- /.col -->
                        </div>
                        <div class="row">
                          <div class="col-md-12 col-xs-12">
                            <?php
                                if($session->getFlashdata('error'))
                                {
                            ?>
                            <br>
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <?php echo $session->getFlashdata('error'); ?>                    
                            </div>
                            <?php } ?>
                          </div>
                        </div>
                      </form>

                      <?php
                        $configAttempts = $myModel->myGet('tbl_config', 'BaseTbl.*', 'BaseTbl.configKey = "DisableLogin"', 'BaseTbl.id', 'asc');

                        // Check if user exists (regardless of status)
                        $usersData = $myModel->myGet('tbls_user', 'BaseTbl.*', 'BaseTbl.email = "' . $session->get('myEmail') . '"', 'BaseTbl.id', 'asc');

                        if (!empty($usersData)) {
                            $user = $usersData[0];

                            // Auto re-activate after 2 hours if inactive
                            if ($user->status === 'Inactive') {
                                // Agar updateddate ya koi timestamp field hai to use karo
                                $lastUpdate = isset($user->updateddate) ? strtotime($user->updateddate) : 0;

                                if ($lastUpdate > 0 && (time() - $lastUpdate) >= (2 * 60 * 60)) {
                                    $data = [
                                        'status' => 'Active',
                                        'loginAttempts' => 0
                                    ];
                                    recordAjaxUpdate('email', $session->get('myEmail'), $data, 'tbls_user');
                                    $user->status = 'Active'; // local object update
                                    $user->loginAttempts = 0;
                                }
                            }

                            if ($user->status === 'Active') {
                                $hashPassword = $user->password;
                                $failedAttempts = (int) $user->loginAttempts;

                                if (password_verify($session->get('myPassword'), $hashPassword)) {
                                    // Correct password: reset failed attempts
                                    $data['loginAttempts'] = 0;
                                    recordAjaxUpdate('email', $session->get('myEmail'), $data, 'tbls_user');
                                    echo ""; // redirect to dashboard
                                } else {
                                    // Wrong password
                                    $failedAttempts++;

                                    if ($failedAttempts >= $configAttempts[0]->configValue) {
                                        // 3 failed attempts: deactivate account
                                        $data = [
                                            'status' => 'Inactive',
                                            'loginAttempts' => $failedAttempts,
                                            'updateddate' => date('d-M-y H:i:s')
                                        ];
                                    } else {
                                        // Increment failed attempts
                                        $data = [
                                            'loginAttempts' => $failedAttempts
                                        ];
                                    }

                                    recordAjaxUpdate('email', $session->get('myEmail'), $data, 'tbls_user');
                                }
                            }
                        }
                        ?>


                      <div style="float: left; font-weight: bold;">
                        <a href="<?php echo base_url(); ?>forgotPassword"><span class="translateMe" style="color: #0058b9;">I forgot my password</span></a>
                      </div>
                      <br>
                      <?php $configurationNo = $myModel->myGet('tbl_config', 'BaseTbl.*', 'BaseTbl.configName = "Company Login" AND BaseTbl.configKey = "CL"', 'BaseTbl.id', 'asc');?>
                      <?php if($configurationNo[0]->configValue == '1'){ ?>
                      <div style="float: left; font-weight: bold;">
                        <a href="#" data-toggle="modal" data-target="#registration" class="text-center"><span class="translateMe" style="color: #0058b9;">Register a new account</span></a>
                      </div>
                      <br>
                      <?php } ?>
                      
                      <div style="float: left; font-weight: bold;">
                        <a href="#" data-toggle="modal" data-target="#documentation" class="text-center"><span class="translateMe" style="color: #0058b9;">Documentation</span></a>
                      </div>
                      <br>
                      <?php
                      if(!isset($_SESSION['myLanguage'])){
                          $_SESSION['myLanguage'] = 'english';
                      }
                      if(@$_POST['languageSelect']){
                          $_SESSION['myLanguage'] = $_POST['languageSelect'];
                      }
                      ?>
                      <div class="col-md-12">
                      <div class="col-md-2" style="float: left; margin-top:5%;">
                          <span class="fas fa-globe"></span>
                      </div>
                      <div class="col-md-10" style="float: right; margin-top: 3%;">
                        <div class="form-group">
                          <form id="myFormLanguage" method="post">
                          <select class="form-control" id="languageSelect" name="languageSelect" aria-label="Select Language" onchange="$('#myFormLanguage').submit();">
                            <option value="english" <?php if('english' == @$_SESSION['myLanguage']){ echo 'selected'; } ?>>English</option>
                            <option value="french" <?php if('french' == @$_SESSION['myLanguage']){ echo 'selected'; } ?>>Français</option>
                          </select>
                          </form>
                        </div>
                      </div>
                      </div>
                    </div>
                    <!-- <br> -->
                    <div style="text-align: center; font-weight: bold; color: white; //font-size: initial; font-size: 80%; background-color: #c34e37;"><span class="translateMe">RBI Tool Ver. 3.0</span></div>
                    <!-- <br> -->
                    <!-- /.card-body -->
                  </div>
                  <!-- /.card -->
                </div>
                <!-- /.login-box -->
                </div>
              <!-- <a style="padding: 4%; margin-left: 2%; border-radius: 10em 10em; box-shadow: inset 0px 0px 20px 0px #151515;  background-color: #f1f1f1; border-color: #7a55b8;" href="#" data-toggle="modal" data-target="#gdpLogin" data-backdrop="static" class="btn btn-warning">GDP <i class="fa fa-lock"></i></a> -->
            <!-- </div> -->
          </center>
        </div>
      </div>
      
    </div>
    <div class="col-md-12" style="margin-top: -5%; color: #000;">
      <center style="box-shadow: inset 0px 0px 8px 3px #838e9f; margin-left: 10%; margin-right: 10%; padding: 1%; border-radius: 20em; background-color: #e9ecef;">
        Copyright &copy; <?php echo date('Y'); ?> The U.S. Pharmacopeial Convention. All rights reserved | <a href="#" data-toggle="modal" data-target="#termsofuse" data-backdrop="static" style="color: #3f51b5;"><span class="translateMe">Terms of use</span></a> | <a href="#" data-toggle="modal" data-target="#privacypolicy" data-backdrop="static" style="color: #3f51b5;"><span class="translateMe">Privacy Policy</span></a> | <a href="#" data-toggle="modal" data-target="#disclaimer" data-backdrop="static" style="color: #3f51b5;"><span class="translateMe">Disclaimer</span></a> | <a href="#" data-toggle="modal" data-target="#contactus" data-backdrop="static" style="color: #3f51b5;"><span class="translateMe">Contact Us</span></a>
      </center>
      <!-- <div style="float: right">
        <a href="#" data-toggle="modal" data-target="#aboutmedrs" data-backdrop="static"><img style="margin-top: -6vh;" src="<?php echo base_url(); ?>uploads/company/RBI/logo/logo.png" class="img-fluid pull-right" alt="Logo"></a>
      </div> -->
    </div>
  </div>
  <div id="smallScreen" class="wrapper">
    <div id="cover24" style="width: 100%;">
      <div id="cover23456" style="//background: url(assets/dist/img/wp1.jpg); //background: url(assets/dist/img/wp15.jpg); //background: url(assets/dist/img/wp35.jpg); //background: url(assets/dist/img/wp10.jpg); border-top-left-radius: 100em 10em; border-top-right-radius: 100em 10em; background-size: 100%;">
      <div id="a167" class="img-header" style="height: 60.83650190114068vh;">
      <div class="login-box" style="border-radius:2%; //background-color: #f6f6f6; box-shadow: 0px 0px 20px 0px #151515; margin: auto; border-top-left-radius: 100em 7em;
        border-top-right-radius: 100em 7em;">
        <!-- /.login-logo -->
        <div class="card card-outline card-primary" style="//border-top: 3px solid #fd0805; border-top: 20px solid #284576; background-color: #fff; border-top-left-radius: 100em 7em;
        border-top-right-radius: 100em 7em;">
          <img style="margin-top: 10px; width: 60%; //height: 200px; vertical-align: middle; padding: 10%; align-self: center;" src="<?php echo base_url(); ?>uploads/company/RBI/logo/logo.png" class="img-fluid" alt="Logo">
          <div class="card-header text-center" style="//margin: -10px; border-bottom: 0px solid #000 !important; background-color: #e8e8e8;">
            <a href="<?php echo base_url(); ?>" class="h1" style="color: #fff;"><b style="color: #284576; text-shadow: 2px 2px 0px #000;">RBI</b><sub style="bottom: 0em; font-size: 45%;"></sub></a>
          </div>
          <div class="card-body" style="background-color: #e8e8e8;">
            <p class="login-box-msg"><?php if($mode == 'Maintenance'){echo '<span class="text-danger"> <span style="border-bottom: 3px solid #dc3545; border-bottom-left-radius: 1em 100em; border-bottom-right-radius: 1em 100em; border-bottom-width: thick; padding: 10px;">● <i class="fas fa-cog fa-spin fa-6x"></i> ●</span></span>';}else{echo '<span style="border-bottom: 3px solid #10a659; border-bottom-left-radius: 100em 50em; border-bottom-right-radius: 100em 50em; border-bottom-width: thick; padding: 10px;"><span style="color:#10a659;">●</span> <span class="translateMe">Sign In</span> <span style="color:#10a659;">●</span></span>';} ?></p>

            <form onsubmit="loginSound();hide();" action="<?php echo base_url(); ?>loginMe" method="post" <?php if($mode == 'Maintenance'){echo 'style="opacity:0;"';} ?>>
              <div class="input-group mb-3">
                <input type="email" name="email" onfocus="this.removeAttribute('readonly');" value="<?php if($session->get('myEmail')){echo $session->get('myEmail');} else{echo '' ;}?>" class="form-control" placeholder="Email" required>
                <div class="input-group-append">
                  <div class="input-group-text">
                    <span class="fas fa-envelope"></span>
                  </div>
                </div>
              </div>
              <div class="input-group mb-3">
                <input type="password" name="password" onfocus="this.removeAttribute('readonly');" value="<?php if($session->get('myPassword')){echo $session->get('myPassword');}?>" class="form-control" placeholder="Password" required>
                <div class="input-group-append">
                  <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-8">
                  
                </div>

                <!-- /.col -->
                <div class="col-4">
                  <button type="submit" class="btn btn-primary btn-block" style="background-color: #006ee5 !important; border-color: #006ee5 !important;"><span class="translateMe">Sign In</span></button>
                </div>
                <!-- /.col -->
              </div>
              <div class="row">
                <div class="col-md-12 col-xs-12">
                  <?php
                      if($session->getFlashdata('error'))
                      {
                  ?>
                  <br>
                  <div class="alert alert-danger alert-dismissible">
                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                      <?php echo $session->getFlashdata('error'); ?>                    
                  </div>
                  <?php } ?>
                </div>
              </div>
            </form>

            <p class="mb-1">
              <a href="<?php echo base_url(); ?>forgotPassword">I forgot my password</a>
            </p>
          </div>
          <!-- <br> -->
          <div style="text-align: center; font-weight: bold; color: white; //font-size: initial; font-size: 80%; background-color: #284576;"></div>
          <!-- <br> -->
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
      <!-- /.login-box -->
      </div>
      </div>
      </div>
    </div>

    <div class="modal hide fade" id="disclaimer">
      <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title"><b style="font-size: 30px;"><span class="translateMe">Disclaimer</span></b></h4>
            <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button> -->
          </div>
          <div class="modal-body" style="overflow-y: auto; max-height: 80vh;">
            <div class="row">
              <div class="col-md-12">

                <p style="text-align: justify; font-size: 20px;">
                  <span class="translateMe">The contents are the responsibility of U.S. Pharmacopeial Convention (USP) and do not necessarily reflect the views of the United States Government.</span>
                </p>

                <p style="text-align: justify; font-size: 20px;">
                  <span class="translateMe">USP is an independent, scientific nonprofit public health organization and is not a part of the United States Food and Drug Administration (FDA) or any other U.S. government agency. This program, USP, is unaffiliated with, and has not been evaluated by FDA. References to FDA or to FDA publications do not constitute FDA endorsement of the USP program or of the information provided by it.</span>
                </p>
 
                <div class="modal-footer">

                  <div class="icheck-primary">
                  <input type="checkbox" id="confirmTerms">
                  <label for="confirmTerms">
                  <span class="translateMe">I agree to the</span> <a href="#" data-toggle="modal" data-target="#termsofuse" data-backdrop="static"><span class="translateMe" style="color: #006ee5; background-color: #ffffff;">Terms of use</span></a>
                  </label>
                  </div>
                  
                  <button type="button" class="btn btn-secondary" data-dismiss="modal" disabled="disabled" style="visibility:hidden;"><span class="translateMe">Okokoknnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnn</span></button>

                  <button type="button" class="btn btn-secondary" data-dismiss="modal" style="visibility:hidden;"><span class="translateMe">Ok</span></button>
                  <button type="button" class="btn btn-secondary" data-dismiss="modal" style="visibility:hidden;"><span class="translateMe">O</span></button>

                  <button type="button" class="btn btn-secondary" data-dismiss="modal" style="visibility:hidden;"><span class="translateMe">Ok</span></button>

                  <button id="submitButton" type="button" class="btn btn-secondary" data-dismiss="modal" ><span class="translateMe">Ok</span></button>
                </div>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <div class="modal fade" id="termsofuse">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title"><span class="translateMe">Terms of use</span></h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body" style="overflow-y: auto; max-height: 80vh;">
            <div class="row">
              <div class="col-md-12">

                <p style="text-align: justify;">
                <span class="translateMe">YOUR USE OF THIS APPLICATION CONSTITUTES YOUR AGREEMENT TO BE BOUND BY THESE TERMS AND CONDITIONS OF USE.</span>
                <br><br>

                <span class="translateMe">This Application, The Risk-based Inspection (RBI) Tool, including the platform, all its features and content (the "RBI") is a service made available by The United States Pharmacopeial Convention ("USP") and the Promoting the Quality of Medicines Plus (USP) Program. The Application, its content, information, and services provided on or through the Application (“Content”) may be used solely under the following terms and conditions ("Terms of Use").</span>
                <br><br>

                <b><span class="translateMe">Limitations on Use.</span></b>
                <br><br>

                <span class="translateMe">1. Application Limited License. As a user of this Application you are granted a nonexclusive, nontransferable, revocable, limited license to access and use this Application and Content in accordance with these Terms of Use.</span>
                <br><br>

                <span class="translateMe">2. Copyright and Use. The Website and its Contents are protected by copyright pursuant to U.S. and international copyright laws. Except as expressly provided in these Terms of Use, nothing contained herein shall be construed as conferring any license or right, by implication, estoppel or otherwise, under copyright or other intellectual property rights. You agree that the Content and Web site are protected by copyrights, trademarks, service marks, patents or other proprietary rights and laws</span>
                <br><br>

                <span class="translateMe">The Application and content are for your personal use only and not for commercial exploitation. You may not decompile, reverse engineer, disassemble, rent, lease, loan, sell, sublicense, or create derivative works from the Application and Content. You may not use any network monitoring or discovery software to determine the site architecture, or extract information about usage, individual identities or users. You may not use any robot, spider, other automatic software or device, or manual process to monitor or copy our Website or the Content without USP's prior written permission. You may not copy, modify, reproduce, republish, distribute, display, or transmit for commercial, non-profit or public purposes all or any portion of this Application, except to the extent permitted below. You may not use or otherwise export or re-export this Application or any portion thereof, or the Content in violation of the export control laws and regulations of the United States of America. Any unauthorized use of this Application or its Content is prohibited.</span>
                <br><br>

                <span class="translateMe">3. Attribution and citation. Information obtained from this Application and content may be reproduced and modified if credit is given to the US Pharmacopeial Convention (USP) and the Promoting the Quality of Medicines plus (USP) Program, Rockville, Maryland, United States.</span>
                <br><br>

                <span class="translateMe">4. Not Legal Advice. Content is not intended to and does not constitute legal advice, nor is anything submitted to this Website treated as confidential. The accuracy, completeness, adequacy or currency of the Content is not warranted or guaranteed. Your use of Content and Analysis from this Application is at your own risk.</span>
                <br><br>

                <span class="translateMe">5. Registration. Certain sections of this Application require you to register. If registration is requested, you agree to provide USP with accurate and complete registration information. It is your responsibility to inform USP of any changes to that information. Each registration is for a single individual only, unless specifically designated otherwise on the registration page.</span>
                <br><br>

                <span class="translateMe">6. Errors and Corrections. USP does not represent or warrant that this Application or the Content will be error-free, free of viruses or other harmful components, or that defects will be corrected or that it will always be accessible. USP does not warrant or represent that the Content available on or through this Application will be correct, accurate, timely, or otherwise reliable. USP may make improvements and/or changes to its features, functionality or Content at any time.</span>
                <br><br>

                <span class="translateMe">7. DISCLAIMER. THE RISK-BASED INSPECTION (RBI) TOOL, AND THE CONTENT THEREOF IS PROVIDED "AS IS" AND WITH ALL FAULTS. TO THE FULLEST EXTENT PERMITTED BY LAW, USP SPECIFICALLY DISCLAIMS THE IMPLIED WARRANTY OF MERCHANTABILITY OF A COMPUTER PROGRAM, AND THE IMPLIED WARRANTY OF ACCURACY. THERE IS NO WARRANTY THAT THE INFORMATION CONTAINED WITHIN IT WILL FULFILL ANY OF YOUR PARTICULAR PURPOSES OR NEEDS. USP MAKES NO REPRESENTATION OR WARRANTY WITH RESPECT TO THE RISK-BASED INSPECTION (RBI) TOOL ACCURACY, COMPLETENESS, OR CURRENCY; AND USP SPECIFICALLY DISCLAIMS ANY OTHER WARRANTY, EXPRESS, IMPLIED, OR STATUTORY, INCLUDING BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE. USP SHALL NOT BE LIABLE ON ACCOUNT OF ANY SUCH ERRORS, OMISSIONS, DELAYS, OR LOSSES. YOU AGREE THAT IN NO EVENT WILL USP BE LIABLE FOR THE RESULTS OF YOUR USE OF THE RISK-BASED INSPECTION (RBI) TOOL, FOR YOUR INABILITY OR FAILURE TO CONDUCT YOUR BUSINESS, OR FOR INDIRECT, SPECIAL, OR CONSEQUENTIAL DAMAGES. THE FOREGOING LIMITATION OF LIABILITY AND EXCLUSION OF CERTAIN DAMAGES SHALL APPLY REGARDLESS OF THE SUCCESS OR EFFECTIVENESS OF OTHER REMEDIES.</span>
                <br><br>

                <span class="translateMe">8. LIMITATION OF LIABILITY. USP SHALL NOT BE LIABLE FOR ANY LOSS, INJURY, CLAIM, LIABILITY, OR DAMAGE OF ANY KIND RESULTING FROM YOUR USE OF THE RISK-BASED INSPECTION (RBI) TOOL, OR THE CONTENT HEREOF. USP SHALL NOT BE LIABLE FOR ANY SPECIAL, DIRECT, INDIRECT, INCIDENTAL, PUNITIVE, OR CONSEQUENTIAL DAMAGES OF ANY KIND WHATSOEVER (INCLUDING, WITHOUT LIMITATION, ATTORNEYS' FEES) IN ANY WAY DUE TO, RESULTING FROM, OR ARISING IN CONNECTION WITH THE USE OF OR INABILITY TO USE THE RISK-BASED INSPECTION (RBI) TOOL,.</span>
                <br><br>

                <span class="translateMe">9. Indemnification. You agree to indemnify, defend and hold harmless USP, its officers, directors, employees, agents, licensors, suppliers and any third-party information providers to the Application from and against all claims, losses, expenses, damages and costs, including reasonable attorneys' fees, resulting from any violation of these Terms of Use by you.</span>
                <br><br>

                <span class="translateMe">10. Unlawful Activity. USP reserves the right to investigate complaints or reported violations of our Terms of Use and to take any action deemed appropriate including but not limited to reporting any suspected unlawful activity to law enforcement officials, regulators, or other third parties and disclosing any information necessary or appropriate to such persons or entities relating to user profiles, e-mail addresses, usage history, posted materials, IP addresses and traffic information.</span>
                <br><br>

                <span class="translateMe">11. Remedies for Violations. USP reserves the right to seek all remedies available at law and in equity for violations of these Terms of Use, including but not limited to the right to block access from a particular Internet address to this Web site and any other USP Applications and their features.</span>
                <br><br>

                <span class="translateMe">12. Governing Law and Jurisdiction. These Terms of Use are governed by and construed in accordance with the laws of the State of Maryland and any action arising out of or relating to these terms shall be filed only in state or federal courts located in Maryland and you hereby consent and submit to the personal jurisdiction of such courts for the purpose of litigating any such action.</span>
                <br><br>

                <span class="translateMe">13. Privacy. Your use of this Application is subject to USP's Privacy Policy. http://www.usp.org/legal-notices/privacy-policy.</span>
                <br><br>

                <span class="translateMe">14. Severability of Provisions. These Terms of Use incorporate by reference any notices contained on this Application and USP's Privacy Policy and constitute the entire agreement with respect to access to and use of this Application and the Content. If any provision of these Terms of Use is unlawful, void or unenforceable, then that provision shall be deemed severable from the remaining provisions and shall not affect their validity and enforceability.</span>
                <br><br>

                <span class="translateMe">15. Modifications to Terms of Use. USP reserves the right to change these Terms of Use at any time. Updated versions of the Terms of Use will appear on this Application and are effective immediately. You are responsible for regularly reviewing the Terms of Use. Continued use of this Application after any such changes constitutes your consent to such changes.</span>
                </p>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="modal fade" id="privacypolicy">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title"><span class="translateMe">Privacy Policy</span></h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body" style="overflow-y: auto; max-height: 80vh;">
            <div class="row">
              <div class="col-md-12">

                <p style="text-align: justify;">
                <span class="translateMe">USP respects the privacy of customers and visitors accessing this website and other websites maintained by USP, and takes seriously its responsibilities regarding the privacy and security of customer information and visitor information. USP reserves the right to update this Privacy Policy from time to time. Changes to the Privacy Policy shall be announced via this website.</span>
                <br><br>

                <span class="translateMe">USP defines “personal data” to mean any information relating to an identified or identifiable natural person; an “identifiable natural person” is one who can be identified, directly or indirectly, in particular by reference to an identification number or to one or more factors specific to his physical, physiological, mental, economic, cultural or social identity. USP may collect personal data including, but not limited to, your name, title, employer, address, telephone number, and email address. USP collects personal data from you through the use of registration forms, direct personal contact with you, and each time you email USP your details.</span>
                <br><br>

                <span class="translateMe">USP uses your personal data only when and where it is necessary to do so because it is relevant to USP's dealings with you or where USP has your consent to do so. This includes, but is not limited to, invoicing you for products and services you buy from USP, customer service, and network management. Purposes for which USP may use your personal data include direct marketing of USP products and services and customer surveys. USP will not disclose or share your personal data with any other party without your express consent, unless this is necessary to provide the services or products which you requested when providing us with your personal data, or as required by law. Aggregate data which has been de-identified may be used by USP and by third parties to track usage of USP's websites and applications. USP will not sell, trade or rent your personal information to third parties.</span>
                <br><br>

                <span class="translateMe">You understand and agree that perfect security does not exist anywhere, including on the Internet. When you send credit card information to USP over the Internet, USP encrypts this information between your computer and our server. Other information you send to USP, including email messages, will not be encrypted unless USP advises you otherwise. USP may use cookies on its websites. USP has technical and organizational security measures intended to prevent unauthorized or unlawful disclosure or access to, or accidental or unlawful loss of or destruction, or alteration or unauthorized disclosure of or access to, or other damage to your personal data. These measures are intended to ensure an appropriate level of security in relation to the risks inherent in the processing and the nature of the personal data to be protected.</span>
                <br><br>

                USP only keeps your information for as long as we are either required to by law, or as is relevant for the purposes for which it was collected. You may contact USP at any time if you would like to correct, update, supplement or delete personal data. You may also contact USP at any time to opt-out of receiving communications from USP at the point where USP requests information about you. You may send email to <span class="text-primary">privacy@usp.org</span>, or you may send mail to the following postal address:
                <br><br>

                Privacy, USP
                <br>
                12601 Twinbrook Parkway
                <br>
                Rockville, MD 20852
                <br><br>

                If you have any questions about this privacy statement, please email USP at <span class="text-primary">privacy@usp.org</span>
                <br><br>

                <span class="translateMe">USP Cookies Policy</span>
                <br><br>

                <span class="translateMe">To enhance your experience on USP’s websites, many of USP’s web pages use cookies. Cookies are small text files that are placed in your computer’s browser to store your preferences. Cookies, by themselves, do not provide your email address or other personal information unless you choose to provide this information to USP—for example, by registering for e-newsletters or completing a web form. Once you choose to provide personal information to a web page, this information may be linked to the data stored in the cookie. A cookie is like an identification card. It is unique to your computer and can be read only by the server that gave it to you.</span>
                <br><br>

                <span class="translateMe">Analytics cookies store information about what pages people visit, how long they are on the site, how they got there, and what they click on. Information supplied by analytics cookies helps USP analyze the profile of visitors and provide them with a better experience. Analytics cookies do not collect or store personal information (for example, names or addresses), so this information cannot be used to identify individuals. USP does not allow analytics companies to use or share its analytics data.</span>
                <br><br>

                <span class="translateMe">Some areas of USP websites allow users to log in and retrieve personal information and account details.</span> 
                <br>
                <span class="translateMe">Some of these areas use a cookie in order to remember account information.</span>
                <br><br>

                <span class="translateMe">You may also find embedded videos from external sites such as YouTube or Vimeo. Cookies that come from other sites like YouTube but work on usp.org are third-party cookies and are governed by such third-party’s privacy policy. Visitors may learn about third-party cookies policies on the third-party website’s privacy pages.</span>
                <br><br>

                <span class="translateMe">If you want to control which cookies you accept, configure your browser to accept all cookies or to alert you every time a cookie is offered by a website’s server. Most browsers automatically accept cookies. You can set your browser options so that you will not receive cookies and you can delete existing cookies from your browser. You may find that some parts of this site will not function properly if you have refused cookies.</span>
                <br><br>

                <span class="translateMe">Information Regarding the Promoting the Quality of Medicines Plus Program</span>
                <br>
                The information provided on this website is not official United States Government information and does not represent the views or positions of the USP. For more information about USP Privacy and Security Policy, please visit <a href="https://www.usp.org/legal-notices/privacy-policy">https://www.usp.org/legal-notices/privacy-policy</a>.
                <br><br>

                <span class="translateMe">Reference in this website to any specific commercial products, processes, or services, or the use of any trade, firm or corporation name is for the information and convenience of the site’s visitors, and does not constitute endorsement, recommendation, or favoring by the United States Government or USP. The views and opinions of authors expressed herein do not necessarily state or reflect those of the United States Government or USP, and shall not be used for advertising or product endorsement purposes.</span>
                <br><br>

                <span class="translateMe">With respect to the content of this site, neither the United States Government, nor USP, nor their employees and contractors make any warranty, expressed or implied or statutory, including but not limited to the warranties of non-infringement of third party rights, title, and the warranties of merchantability and fitness for a particular purpose with respect to content available from this website or other Internet resources linked from it. Additionally, neither the United States Government nor USP assume any legal liability for the accuracy, completeness, or usefulness of any information, product, or process disclosed herein nor freedom from computer virus, and do not represent that use of such information, product, or process would not infringe on privately owned rights.</span>
                </p>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>


    <?php $recordsEditDocumentation = $myModel->myGet('tbls_documentation', 'BaseTbl.*', 'BaseTbl.id = 1', 'BaseTbl.id', 'desc'); ?>
      <div class="modal fade" id="documentation">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title"><span class="translateMe">Documentation</span></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body" style="overflow-y: auto; max-height: 80vh;">
                <div class="container-fluid">
                  <div class="row">

                        <div class="col-md-12">
                          <div class="form-group">
                            <?php $label = 'Super Admin Manual'; ?>
                            <?php $column = 'saManual'; ?>
                            <label><span class="translateMe"><?php echo $label; ?> Link</span></label>
                            <br>
                            <a <?php if(!@$recordsEditDocumentation[0]->$column){ echo 'disabled';} ?> <?php if(@$recordsEditDocumentation[0]->$column){ echo 'href="'.base_url().'uploads/company/RBI/docs/'.@$recordsEditDocumentation[0]->$column.'"';} ?> target="_blank" class="btn btn-success <?php if(!@$recordsEditDocumentation[0]->$column){ echo 'disabled';} ?>"><i class="fa fa-file"></i></a>
                          </div>
                        </div>

                        <div class="col-md-12">
                          <div class="form-group">
                            <?php $label = 'GMP/GSDP Manual'; ?>
                            <?php $column = 'manual'; ?>
                            <label><span class="translateMe"><?php echo $label; ?> Link</span></label>
                            <br>
                            <a <?php if(!@$recordsEditDocumentation[0]->$column){ echo 'disabled';} ?> <?php if(@$recordsEditDocumentation[0]->$column){ echo 'href="'.base_url().'uploads/company/RBI/docs/'.@$recordsEditDocumentation[0]->$column.'"';} ?> target="_blank" class="btn btn-success <?php if(!@$recordsEditDocumentation[0]->$column){ echo 'disabled';} ?>"><i class="fa fa-file"></i></a>
                          </div>
                        </div>

                        <div class="col-md-12">
                          <div class="form-group">
                            <?php $label = 'Super Admin UAT'; ?>
                            <?php $column = 'saUAT'; ?>
                            <label><span class="translateMe"><?php echo $label; ?> Link</span></label>
                            <br>
                            <a <?php if(!@$recordsEditDocumentation[0]->$column){ echo 'disabled';} ?> <?php if(@$recordsEditDocumentation[0]->$column){ echo 'href="'.base_url().'uploads/company/RBI/docs/'.@$recordsEditDocumentation[0]->$column.'"';} ?> target="_blank" class="btn btn-success <?php if(!@$recordsEditDocumentation[0]->$column){ echo 'disabled';} ?>"><i class="fa fa-file"></i></a>
                          </div>
                        </div>

                        <div class="col-md-6">
                          <div class="form-group">
                            <?php $label = 'GMP module GMP Admin and GMP inspector UAT'; ?>
                            <?php $column = 'gmpUAT'; ?>
                            <label><span class="translateMe"><?php echo $label; ?> Link</span></label>
                            <br>
                            <a <?php if(!@$recordsEditDocumentation[0]->$column){ echo 'disabled';} ?> <?php if(@$recordsEditDocumentation[0]->$column){ echo 'href="'.base_url().'uploads/company/RBI/docs/'.@$recordsEditDocumentation[0]->$column.'"';} ?> target="_blank" class="btn btn-success <?php if(!@$recordsEditDocumentation[0]->$column){ echo 'disabled';} ?>"><i class="fa fa-file"></i></a>
                          </div>
                        </div>

                        <div class="col-md-12">
                          <div class="form-group">
                            <?php $label = 'GSDP module GSDP Admin and GSDP Inspector UAT'; ?>
                            <?php $column = 'gsdpUAT'; ?>
                            <label><span class="translateMe"><?php echo $label; ?> Link</span></label>
                            <br>
                            <a <?php if(!@$recordsEditDocumentation[0]->$column){ echo 'disabled';} ?> <?php if(@$recordsEditDocumentation[0]->$column){ echo 'href="'.base_url().'uploads/company/RBI/docs/'.@$recordsEditDocumentation[0]->$column.'"';} ?> target="_blank" class="btn btn-success <?php if(!@$recordsEditDocumentation[0]->$column){ echo 'disabled';} ?>"><i class="fa fa-file"></i></a>
                          </div>
                        </div>

                        <div class="col-md-12">
                          <div class="form-group">
                            <?php $label = 'Read Me'; ?>
                            <?php $column = 'readMe'; ?>
                            <label><span class="translateMe"><?php echo $label; ?> Link</span></label>
                            <br>
                            <a <?php if(!@$recordsEditDocumentation[0]->$column){ echo 'disabled';} ?> <?php if(@$recordsEditDocumentation[0]->$column){ echo 'href="'.base_url().'uploads/company/RBI/docs/'.@$recordsEditDocumentation[0]->$column.'"';} ?> target="_blank" class="btn btn-success <?php if(!@$recordsEditDocumentation[0]->$column){ echo 'disabled';} ?>"><i class="fa fa-file"></i></a>
                          </div>
                        </div>

                      </div>
                    </div>
                  </div>
              </div>
            </div>
          </div>


    <div class="modal fade" id="registration">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title"><span class="translateMe">Instructions for new company registration</span></h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body" style="overflow-y: auto; max-height: 80vh;">
            <div class="row">
              <div class="col-md-12">

                <p style="text-align: justify;">
                <h3><b><span class="translateMe">Instructions on how to request a new company user account for RBI Tool Subscribers can request a RBI Tool company user account. One company can have only one account to get registered.</span></b></h3>
                <br><br>

                <span class="translateMe">To obtain company user access to the RBI Tool:</span>
                <ol>
                  <li><span class="translateMe">Click the “Register a New account” button to access the form and complete all requested fields</span></li>
                  <li><span class="translateMe">Upon completion of your request, you will be notified via e-mail to the address provided in the form</span></li>
                  <li><span class="translateMe">For any questions or clarifications, please click the “contact us” button on the landing page</span></li>
                </ol>

                <span class="translateMe">USP respects your privacy and takes seriously its responsibilities regarding the privacy and security of your information. Please refer to the bottom of the RBI Tool homepage for detailed information on USP privacy policy.</span>
                </p>

              </div>
            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <a href="<?php echo base_url(); ?>register" class="btn btn-primary text-center"><span class="translateMe">Proceed</span></a>
          </div>
        </div>
      </div>
    </div>
    <!-- <div class="modal fade" id="aboutmedrs">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Medicines Risk-Based Surveillance Tool (MedRS)</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body" style="overflow-y: auto; max-height: 80vh;">
            <div class="row">
              <div class="col-md-12">

                <p style="text-align: justify;">
                
                </p>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div> -->
    <div class="modal fade" id="gmpLogin">
      <div class="modal-dialog modal-lg">
        <div class="modal-content" style="background-image: linear-gradient(#284576, #b7b7b7)">
          <form onsubmit="loginSound();hideModal();" id="myForm" action="<?php echo base_url(); ?>loginMe" enctype="multipart/form-data" method="post" accept-charset="utf-8">
            <div class="modal-header">
              <h4 class="modal-title"><span class="translateMe">RBI-Login</span></h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body" style="overflow-y: auto; max-height: 80vh;">
              <div class="container-fluid">
                <div class="row">

                  <div class="input-group mb-3">
                    <input type="email" name="email" onfocus="$(':submit').removeAttr('disabled');" value="<?php if($session->get('myEmail')){echo $session->get('myEmail');}?>" class="form-control" placeholder="Email" required>
                    <div class="input-group-append">
                      <div class="input-group-text">
                        <span class="fas fa-envelope"></span>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row">

                  <div class="input-group mb-3">
                    <input type="password" name="password" onfocus="$(':submit').removeAttr('disabled');" value="<?php if($session->get('myPassword')){echo $session->get('myPassword');}?>" class="form-control" placeholder="Password" required>
                    <div class="input-group-append">
                      <div class="input-group-text">
                        <span class="fas fa-lock"></span>
                      </div>
                    </div>
                  </div>

                </div>

                <div class="row">
                  
                  <p class="mb-1">
                      <a href="<?php echo base_url(); ?>forgotPassword">I forgot my password</a>
                    </p>

                </div>

              </div>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="submit" class="btn btn-primary" style="background-color: #006ee5; border-color: #006ee5;"><span class="translateMe">Submit</span></button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="modal fade" id="contactus">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <form id="myForm1" action="<?php echo base_url().'contactus/submit'; ?>" enctype="multipart/form-data" method="post" accept-charset="utf-8">
            <div class="modal-header">
              <h4 class="modal-title"><span class="translateMe">Contact Us</span></h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body" style="overflow-y: auto; max-height: 80vh;">
              <div class="container-fluid">
                <div class="row">

                  <div class="col-md-6">
                    <div class="form-group">
                      <?php $label = 'RBI Module'; ?>
                      <label><span class="translateMe"><?php echo $label; ?></span></label>
                      <?php $column = 'module'; ?>
                      <select class="form-control select2 required" id="<?php echo @$column; ?>" name="<?php echo @$column; ?>">
                        <option value="GMP">GMP</option>
                      </select>
                    </div>
                  </div>

                </div>
                <div class="row">

                  <div class="col-md-6">
                    <div class="form-group">
                      <?php $label = 'Name'; ?>
                      <label><span class="translateMe"><?php echo $label; ?></span></label>
                      <?php $column = 'name'; ?>
                      <input type="text" id="<?php echo @$column; ?>" name="<?php echo @$column; ?>" value="" class="form-control required">
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <?php $label = 'Email'; ?>
                      <label><span class="translateMe"><?php echo $label; ?></span></label>
                      <?php $column = 'email'; ?>
                      <input type="text" id="<?php echo @$column; ?>" name="<?php echo @$column; ?>" value="" class="form-control required">
                    </div>
                  </div>

                </div>

                <div class="row">

                  <div class="col-md-12">
                    <div class="form-group">
                      <?php $label = 'Message'; ?>
                      <label><?php echo $label; ?></label>
                      <?php $column = 'message'; ?>
                      <textarea id="<?php echo @$column; ?>" name="<?php echo @$column; ?>" class="form-control required" rows="3"></textarea>
                    </div>
                  </div>

                </div>

                <div class="row">
                  <style type="text/css">
                    .table {
                      font-family: verdana, Helvetica, sans-serif;
                      font-size: 12px;
                      color: #333;
                      background-color: #E4E4E4;
                    }
                    .table td {
                      background-color: #F8F8F8;
                    }
                  </style>
                  <table width="400" border="0" align="center" cellpadding="5" cellspacing="1" class="table">
                    <?php if($session->get('msg')){?>
                    <tr>
                      <td colspan="2" align="center" valign="top"><?php echo $session->get('msg');?></td>
                    </tr>
                    <?php } $session->remove('msg'); ?>
                    <tr>
                      <td align="right" valign="top"> Validation code:</td>
                      <td><img src="<?php echo base_url()?>registerCaptcha?rand=<?php echo rand();?>" id='captchaimg'><br>
                        <label for='message'>Enter the code above here :</label>
                        <br>
                        <input id="captcha_code" name="captcha_code" type="text">
                        <br>
                        Can't read the image? click <a href='javascript: refreshCaptcha();'>here</a> to refresh.</td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="submit" class="btn btn-primary" style="background-color: #006ee5; border-color: #006ee5;"><span class="translateMe">Submit</span></button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="modal fade" id="aboutusp">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">About USP</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body" style="overflow-y: auto; max-height: 80vh;">
            <div class="row">
              <div class="col-md-12">

                <p style="text-align: justify;">
                Since 1992, USP has been funded by USP to support developing countries address public health threats related to medicines quality. The Promoting the Quality of Medicines (PQM) program[pon1] provides technical assistance to build capacity of medicines regulatory authorities, and quality assurance/control systems in countries with emergent health systems. Furthermore PQM helps countries combat substandard and falsified medicines, and ensure access to critical medicines by providing technical assistance to improve GMP compliance of manufacturers committed to producing quality-assured priority medicines for malaria, HIV/AIDS, tuberculosis, neglected tropical diseases, and maternal and child health. For more information
                <br>
                <a href="http://www.usp.org/global-health/promoting-quality-medicines" target="_blank">http://www.usp.org/global-health/promoting-quality-medicines</a>
                </p>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="modal fade" id="dashboardLogin">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header" style="background-color: aliceblue;">
            <h4 class="modal-title">Login</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body" style="overflow-y: auto; max-height: 80vh; background-color: #fff;">
            <div class="row">
              <div class="col-md-12">

                <div class="login-box" style="//box-shadow: 0px 2px 30px #f47635;//border: 8px solid #1c395d;border-radius:2%;//background-color: #344063;background-color: #2f516f; box-shadow: 0px 0px 20px 0px #989898; margin: auto;">
                  <div class="card card-outline card-primary" style="border-top: 3px solid #2f516f; ">
                    <div class="card-header text-center">
                      <a href="#" data-toggle="modal" data-target="#aboutusp" data-backdrop="static"><img style="width: -webkit-fill-available; border-top-left-radius: 1em 1em; border-top-right-radius: 1em 1em; border-bottom-left-radius: 1em 1em; border-bottom-right-radius: 1em 1em;" src="<?php echo base_url(); ?>uploads/company/RBI/logo/logo_allNew.png" class="img-fluid pull-right" alt="Logo"></a>
                      <a href="<?php echo base_url(); ?>" class="h1"><b>RBI</b><sub style="bottom: 0em; font-size: 45%;"> v2</sub></a>
                    </div>
                    <div class="card-body" style="background-color: #e8e8e8;">
                      <p class="login-box-msg"><?php if($mode == 'Maintenance'){echo '<span class="text-danger"><i class="fas fa-tools"></i> Under Maintenance!</span>';}else{echo 'Sign In';} ?></p>

                      <form onsubmit="loginSound();hide();" action="<?php echo base_url(); ?>loginMe" method="post" <?php if($mode == 'Maintenance'){echo 'style="opacity:0;"';} ?>>
                        <div class="input-group mb-3">
                          <input type="email" name="email" onfocus="$(':submit').removeAttr('disabled');" value="<?php if($session->get('myEmail')){echo $session->get('myEmail');}?>" class="form-control" placeholder="Email" required>
                          <div class="input-group-append">
                            <div class="input-group-text">
                              <span class="fas fa-envelope"></span>
                            </div>
                          </div>
                        </div>
                        <div class="input-group mb-3">
                          <input type="password" name="password" onfocus="$(':submit').removeAttr('disabled');" value="<?php if($session->get('myPassword')){echo $session->get('myPassword');}?>" class="form-control" placeholder="Password" required>
                          <div class="input-group-append">
                            <div class="input-group-text">
                              <span class="fas fa-lock"></span>
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-8">
                            <!-- <a href="https://www.usp.org" target="_blank"><img style="width: -webkit-fill-available;" src="<?php echo base_url(); ?>uploads/company/MedRS/logo/logo_allNew.png" class="img-fluid pull-right" alt="Logo"></a> -->
                          </div>

                          <!-- /.col -->
                          <div class="col-4">
                            <button disabled type="submit" class="btn btn-primary btn-block" style="background-color: #006ee5 !important; border-color: #006ee5 !important;">Sign In</button>
                          </div>
                          <!-- /.col -->
                        </div>
                        <div class="row">
                          <div class="col-md-12 col-xs-12">
                            <?php
                                if($session->getFlashdata('error'))
                                {
                            ?>
                            <br>
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <?php echo $session->getFlashdata('error'); ?>                    
                            </div>
                            <?php } ?>
                          </div>
                        </div>
                      </form>

                      <p class="mb-1">
                        <a href="<?php echo base_url(); ?>forgotPassword">I forgot my password</a>
                      </p>
                      <!-- <p class="mb-0">
                        <a href="#" data-toggle="modal" data-target="#registration" data-backdrop="static" class="text-center">Register a new account</a>
                      </p> -->
                    </div>
                    <!-- <br> -->
                    <div style="text-align: center; font-weight: bold; color: white; //font-size: initial; font-size: 80%; background-color: #2f516f;">Neglected Tropical Disease</div>
                    <!-- <br> -->
                    <!-- /.card-body -->
                  </div>
                  <!-- /.card -->
                  <!-- <div class="col-md-12 col-xs-12">
                    <?php
                        if($session->getFlashdata('error'))
                        {
                    ?>
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h4><i class="icon fa fa-warning"></i> Alert!</h4>
                        <?php echo $session->getFlashdata('error'); ?>                    
                    </div>
                    <?php } ?>
                    <?php  
                        if($session->getFlashdata('success'))
                        {
                    ?>
                    <div class="alert alert-success alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h4><i class="icon fa fa-check"></i> Alert!</h4>
                        <?php echo $session->getFlashdata('success'); ?>
                    </div>
                    <?php } ?>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <?php //echo validation_errors('<div class="alert alert-danger alert-dismissable">', ' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>'); ?>
                        </div>
                    </div>
                  </div> -->
                </div>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

<!-- jQuery -->
<script src="<?php echo base_url(); ?>assets/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="<?php echo base_url(); ?>assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url(); ?>assets/dist/js/adminlte.min.js"></script>
<audio  id="loginSound" src="assets/dingtong.mp3"></audio>
<script>
  function loginSound() {
    document.getElementById("loginSound").play();
  }
</script>
<style>
  /* Chrome, Safari, Opera */
  @-webkit-keyframes fade {
      0%   { opacity: 0;top: -100px;}
      100% { opacity: 1;top: 0px;}
  }
  .img-header1{
    opacity: 0;
    -webkit-animation: fade 1.5s ease 0s forwards; /* Chrome, Safari, Opera */
    position: relative;
  
  }

  /*@-webkit-keyframes myPage {
      0%   { -webkit-transform: translateY(100px) scale(1); //height: 10%; border-top-left-radius: 50em; border-top-right-radius: 50em; background-image: linear-gradient(#284576, #b7b7b7);}
      60% { //border-radius: 50em; background-image: linear-gradient(#284576, #b7b7b7);}
      100% { -webkit-transform: translateY(0px) scale(1); //height: 100%; border-top-left-radius: 100em 50em; border-top-right-radius: 100em 50em; background-image: linear-gradient(#284576, #b7b7b7);}
  }*/
  @-webkit-keyframes myPage {
      0%   { -webkit-transform: translateY(100px) scale(1); //height: 10%; border-top-left-radius: 50em; border-top-right-radius: 50em; background-image: linear-gradient(#284576, #284576);}
      60% { //border-radius: 50em; background-image: linear-gradient(#284576, #284576);}
      100% { -webkit-transform: translateY(0px) scale(1); //height: 100%; border-top-left-radius: 100em 50em; border-top-right-radius: 100em 50em; border-bottom-left-radius: 10em; border-bottom-right-radius: 10em; background-image: linear-gradient(#284576, #284576);}
  }
  .myPage0{
    opacity: 1;
    -webkit-animation: myPage 1.5s ease 0s forwards; /* Chrome, Safari, Opera */
    position: relative;
  
  }

  @-webkit-keyframes fades {
    0%   { -webkit-transform: translateY(1000px) scale(3); opacity: 0;top: 0px;}
      100% { -webkit-transform: translateY(0px) scale(1); opacity: 1;top: 0px;}
  }
  #cover{
    opacity: 0;
    -webkit-animation: fades 3s ease 0s forwards; /* Chrome, Safari, Opera */
  }
  @-webkit-keyframes fadess {
    0% {
              opacity: 0;
    }
    100% {
              opacity: 1;
    }
  }
  .login-box1{
    opacity: 0;
    <?php if($session->get('myEmail')){echo 'opacity: 1;';}?>
  }
  .img-header2{
    opacity: 0;
    -webkit-animation: fadess 1s ease 0s forwards; /* Chrome, Safari, Opera */
  }
  .addBg{
    //background: url("assets/dist/img/bg4.jpg");
    background-color: #949191;
  }

  @-webkit-keyframes reversefades {
    0% {
              opacity: 1;top: 0px;
              -webkit-transform: translateY(0) scale(1);
    }
    50% {
              opacity: 0;top: -100px;
              -webkit-transform: translateY(15px) scale(1);
    }
    100% {
              opacity: 0;top: -100px;
              -webkit-transform: translateY(15) scale(1);
    }
  }
  .img-headerhide{
    opacity: 1;
    -webkit-animation: reversefades 1s ease 0s forwards; /* Chrome, Safari, Opera */
    position: relative;
  }

  #cover1 {
    -webkit-animation: bg 6s linear infinite;
    -webkit-backface-visibility: hidden;
    -webkit-transform: translate3d(0,0,0);
    background: #348cb2 url("assets/dist/img/bg4.jpg") bottom left;
    background-size:100% 100%;
    background-repeat: repeat-x;
    height: 100%;
    width: 100%;
    opacity: 1;
    position: fixed;
    top: 0;
  }

  @-webkit-keyframes bg {
    0% {
      -webkit-transform: scale(1);
    }
    50% {
      -webkit-transform: scale(1.1);
    }

    100% {
      -webkit-transform: scale(1);
    }
  }
</style>
<script type="text/javascript">
  function hide(){
  $("#a1").removeClass("img-header");
  $("#a1").addClass("img-headerhide");
  $("#a1Small").removeClass("img-header");
  $("#a1Small").addClass("img-headerhide");
  }
</script>
<script type="text/javascript">
  function hideModal(){
    $("#gmpLogin").modal('hide');
  }
</script>
<style type="text/css">
  .pageLoader{
      background-color: #000;
      opacity: 0.4;
      pointer-events:none;
</style>
<script type="text/javascript">
  $("#accessButton").click(function(){
    $(".login-box1").addClass("img-header2");
    $("#cover2").addClass("addBg");
  });
</script>
<script type='text/javascript'>
  function refreshCaptcha(){
    var img = document.images['captchaimg'];
    img.src = img.src.substring(0,img.src.lastIndexOf("?"))+"?rand="+Math.random()*1000;
  }
</script>
<!-- jquery-validation -->
<script src="<?php echo base_url(); ?>assets/plugins/jquery-validation/jquery.validate.min.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/jquery-validation/additional-methods.min.js"></script>
<script>
$(function () {
  $('#myForm').validate({
    rules: {
      name: {
        required: true,
      },
      email: {
        required: true,
        email: true,
      },
      message: {
        required: true,
        maxlength: 200
      },
    },
    messages: {
      name: {
        required: "Please enter a name",
      },
      email: {
        required: "Please enter a email address",
        email: "Please enter a vaild email address"
      },
      message: {
        required: "Please provide a message",
        maxlength: "Your message must be not exceed 200 characters"
      },
    },
    errorElement: 'span',
    errorPlacement: function (error, element) {
      error.addClass('invalid-feedback');
      element.closest('.form-group').append(error);
    },
    highlight: function (element, errorClass, validClass) {
      $(element).addClass('is-invalid');
    },
    unhighlight: function (element, errorClass, validClass) {
      $(element).removeClass('is-invalid');
    }
  });
});
</script>
<script>
$(function () {
  $('#myForm1').validate({
    rules: {
      name: {
        required: true,
      },
      email: {
        required: true,
        email: true,
      },
      message: {
        required: true,
        maxlength: 200
      },
    },
    messages: {
      name: {
        required: "Please enter a name",
      },
      email: {
        required: "Please enter a email address",
        email: "Please enter a vaild email address"
      },
      message: {
        required: "Please provide a message",
        maxlength: "Your message must be not exceed 200 characters"
      },
    },
    errorElement: 'span',
    errorPlacement: function (error, element) {
      error.addClass('invalid-feedback');
      element.closest('.form-group').append(error);
    },
    highlight: function (element, errorClass, validClass) {
      $(element).addClass('is-invalid');
    },
    unhighlight: function (element, errorClass, validClass) {
      $(element).removeClass('is-invalid');
    }
  });
});
</script>
<script type="text/javascript">
  $('.required').each(function(i, obj) {
      $(this).parent().find('label').prepend('<i class="fa fa-asterisk text-danger"></i> ');
  });
</script>
<script type="text/javascript">
  $("#accessButton").click(function () {
     $("html, body").animate({scrollTop: 0}, 1000);
  });
</script>
<script>
  if(window.innerWidth < 450){
    $('#bigScreen').css('display', 'none');
    $('#smallScreen').css('display', 'block');
  }

  if(window.innerWidth >= 450){
    $('#smallScreen').css('display', 'none');
    $('#bigScreen').css('display', 'block');
  }
</script>

<!-- <script type="text/javascript">
    $(window).on('load', function() {
        $('#disclaimer').modal('show');
    });
</script> -->

<script>
$(document).ready(function(){
    $('#disclaimer').modal({
      backdrop: 'static'
    });
  }); 
</script>
<script type="text/javascript">
  function multilingual(language, searchText){
    var translatedData;
    $.ajax({
      url: '<?php echo base_url(); ?>uploads/company/RBI/templates/languages/'+language+'.html',
      dataType:   'JSON',
      async: false,
      success: function(data){
        $.each(data, function(key, value){
          if(key == searchText.trim()){
            translatedData = value;
            return;
          }
        });
      }
    });
    if(translatedData){
      return translatedData;
    }
    else{
      return '#####';
    }
  }

  //searchText = 'Hello';
  //language = 'french';

  // result = multilingual(language, searchText);
  // alert(result);

  // var textNodes = $('body').contents().filter(function() {
  //   return this.nodeType ==  Node.TEXT_NODE;
  // });

  // str = $('body').html();
  // var ret = str.split(" ");
  // ret.forEach(function(item) {
  //   result = multilingual(language, item);
  //   if(result == 'Bonjour'){
  //     alert(result);
  //   }
  // });
</script>
<?php $myLanguage = $_SESSION['myLanguage'] ?? 'english'; ?>
<script type="text/javascript">
  $(document).ready(function() {
    if('<?php echo $myLanguage; ?>' !== 'english'){
      $(".translateMe").each(function() {
        var text = $(this).text();
        text = text.replace(text, multilingual('<?php echo $myLanguage; ?>', text));
        $(this).text(text);
      });
    }
  });
</script>

<script type="text/javascript">
  $('#confirmTerms').click(function(){
    if($(this).prop("checked") == true){
      $('#submitButton').attr('disabled', false);
    }
    else
    {
      $('#submitButton').attr('disabled', true);
    }
  });
  $(document).ready(function(){
    var value = $('#confirmTerms').val();
      if(value == 'on'){
        $('#submitButton').attr('disabled', true);
      }
  });
</script>

<?php $session->remove('myEmail'); ?>
<?php $session->remove('myPassword'); ?>
</body>
</html>
