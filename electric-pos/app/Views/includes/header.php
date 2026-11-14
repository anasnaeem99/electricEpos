<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo $pageTitle; ?></title>
  <link id="favicon" rel="icon" href="<?php echo base_url(); ?>uploads/company/RBI/logo/logo-mini.png"/>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/daterangepicker/daterangepicker.css">
  <!-- Bootstrap Color Picker -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css">
  <!-- Select2 -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <!-- jsGrid -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/jsgrid/jsgrid.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/jsgrid/jsgrid-theme.min.css">
  <!-- summernote -->
  <!-- <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/summernote/summernote-bs4.min.css"> -->
  <!-- CodeMirror -->
  <!-- <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/codemirror/codemirror.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/codemirror/theme/monokai.css"> -->
  <!-- bootstrap slider -->
  <!-- <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/bootstrap-slider/css/bootstrap-slider.min.css"> -->
  <!-- Ion Slider -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/ion-rangeslider/css/ion.rangeSlider.min.css">
  <!-- fullCalendar -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/fullcalendar/main.css">
  <!-- pace-progress -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/pace-progress/themes/black/pace-theme-flat-top.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/jqvmap/jqvmap.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Bootstrap4 Duallistbox -->
  <!-- <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css"> -->
  <!-- BS Stepper -->
  <!-- <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/bs-stepper/css/bs-stepper.min.css"> -->
  <!-- dropzonejs -->
  <!-- <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/dropzone/min/dropzone.min.css"> -->
  <!-- SimpleMDE -->
  <!-- <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/simplemde/simplemde.min.css"> -->
  <!-- SweetAlert2 -->
  <!-- <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css"> -->
  <!-- Toastr -->
  <!-- <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/toastr/toastr.min.css"> -->
  <!-- flag-icon-css -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/flag-icon-css/css/flag-icon.min.css">
  <!-- Bootstrap Treeview -->
  <!-- <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/vendors/bootstrap-treeview-master/bootstrap-treeview-master/dist/bootstrap-treeview.css"> -->

  <!--############################### UP CSS ######################################### -->

  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/adminlte.min.css">

  <!--############################### CUSTOM CSS ######################################### -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>uploads/company/<?php echo $companyName; ?>/style/css/myStyle.css">
  <!--############################### CUSTOM CSS ######################################### -->

  <style type="text/css">
    table>tbody>tr:hover {
      background-color: #f5f5f5 !important;
    }
    .pageLoaderBackground{
      background-color: #000;
    }
    .pageLoader{
      //background-color: grey;
      opacity: 0.4;
      pointer-events:none;
    }
    .loader {
      width:100%;
      top: 50%;
      position:fixed;
      z-index:9999;
      color: #3c8dbc;
      text-align: center;
    }
        .sidebar-dark-primary .nav-sidebar>.nav-item>.nav-link.active, .sidebar-light-primary .nav-sidebar>.nav-item>.nav-link.active{
        background-color: #006ee5 !important;
    }
    
    .bg-success {
    background-color: #208636!important;
    }
    
    .bg-info {
    background-color: #118294!important;
    }
    
    .bg-primary {
    background-color: #006ee5!important;
    }
    
    .nav-pills .nav-link.active, .nav-pills .show>.nav-link{
        background-color: #006ee5 !important;
    }
    
    .page-item.active .page-link{
        background-color: #006ee5 !important;
    }
    
    [class*=sidebar-dark-] .nav-treeview>.nav-item>.nav-link.active{
        color: #ffffff;
    }
    
    [class*=sidebar-dark-] .nav-treeview>.nav-item>.nav-link.active:hover{
        color: #ffffff;
    }
    
    .page-link{
        color: #006ee5 !important;
    }
    
    .page-item.active .page-link {
        color: #ffffff !important;
    }
    .page-item.disabled .page-link {
    color: #000000 !important;
    }
  </style>

  <!--############################### DOWN JS ######################################### -->

  <!-- jQuery -->
  <script src="<?php echo base_url(); ?>assets/plugins/jquery/jquery.min.js"></script>
  <!-- jQuery UI -->
  <script src="<?php echo base_url(); ?>assets/plugins/jquery-ui/jquery-ui.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="<?php echo base_url(); ?>assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- Tempusdominus Bootstrap 4 -->
  <script src="<?php echo base_url(); ?>assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
  <!-- AdminLTE App -->
  <script src="<?php echo base_url(); ?>assets/dist/js/adminlte.min.js"></script>
  <!-- AdminLTE for demo purposes -->
  <script src="<?php echo base_url(); ?>assets/dist/js/demo.js"></script>
  <!-- Select2 -->
  <script src="<?php echo base_url(); ?>assets/plugins/select2/js/select2.full.min.js"></script>
  <!-- InputMask -->
  <script src="<?php echo base_url(); ?>assets/plugins/moment/moment.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/plugins/inputmask/jquery.inputmask.min.js"></script>
  <!-- date-range-picker -->
  <script src="<?php echo base_url(); ?>assets/plugins/daterangepicker/daterangepicker.js"></script>
  <!-- bootstrap color picker -->
  <script src="<?php echo base_url(); ?>assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
  <!-- Summernote -->
  <!-- <script src="<?php echo base_url(); ?>assets/plugins/summernote/summernote-bs4.min.js"></script> -->
  <!-- CodeMirror -->
  <!-- <script src="<?php echo base_url(); ?>assets/plugins/codemirror/codemirror.js"></script>
  <script src="<?php echo base_url(); ?>assets/plugins/codemirror/mode/css/css.js"></script>
  <script src="<?php echo base_url(); ?>assets/plugins/codemirror/mode/xml/xml.js"></script>
  <script src="<?php echo base_url(); ?>assets/plugins/codemirror/mode/htmlmixed/htmlmixed.js"></script> -->
  <!-- DataTables  & Plugins -->
  <script src="<?php echo base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/plugins/jszip/jszip.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/plugins/pdfmake/pdfmake.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/plugins/pdfmake/vfs_fonts.js"></script>
  <script src="<?php echo base_url(); ?>assets/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/plugins/datatables-buttons/js/buttons.print.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
  <!-- jsGrid -->
  <!-- <script src="<?php echo base_url(); ?>assets/plugins/jsgrid/demos/db.js"></script>
  <script src="<?php echo base_url(); ?>assets/plugins/jsgrid/jsgrid.min.js"></script> -->
  <!-- Bootstrap slider -->
  <!-- <script src="<?php echo base_url(); ?>assets/plugins/bootstrap-slider/bootstrap-slider.min.js"></script> -->
  <!-- fullCalendar 2.2.5 -->
  <script src="<?php echo base_url(); ?>assets/plugins/moment/moment.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/plugins/fullcalendar/main.js"></script>
  <!-- Ion Slider -->
  <script src="<?php echo base_url(); ?>assets/plugins/ion-rangeslider/js/ion.rangeSlider.min.js"></script>
  <!-- pace-progress -->
  <script src="<?php echo base_url(); ?>assets/plugins/pace-progress/pace.min.js"></script>
  <!-- overlayScrollbars -->
  <script src="<?php echo base_url(); ?>assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
  <!-- Bootstrap4 Duallistbox -->
  <!-- <script src="<?php echo base_url(); ?>assets/plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js"></script> -->
  <!-- Bootstrap Switch -->
  <script src="<?php echo base_url(); ?>assets/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
  <!-- BS-Stepper -->
  <!-- <script src="<?php echo base_url(); ?>assets/plugins/bs-stepper/js/bs-stepper.min.js"></script> -->
  <!-- dropzonejs -->
  <!-- <script src="<?php echo base_url(); ?>assets/plugins/dropzone/min/dropzone.min.js"></script> -->
  <!-- jquery-validation -->
  <script src="<?php echo base_url(); ?>assets/plugins/jquery-validation/jquery.validate.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/plugins/jquery-validation/additional-methods.min.js"></script>
  <!-- SweetAlert2 -->
  <!-- <script src="<?php echo base_url(); ?>assets/plugins/sweetalert2/sweetalert2.min.js"></script> -->
  <!-- Toastr -->
  <!-- <script src="<?php echo base_url(); ?>assets/plugins/toastr/toastr.min.js"></script> -->
  <!-- Bootstrap Treeview -->
  <!-- <script src="<?php echo base_url(); ?>assets/dist/vendors/bootstrap-treeview-master/bootstrap-treeview-master/dist/bootstrap-treeview.min.js"></script> -->
  <!-- Webcam -->
  <script src="<?php echo base_url(); ?>assets/dist/vendors/WebcamJS-1.0.25/WebcamJS-1.0.25.js"></script>
  <!-- HTML2Canvas -->
  <script src="<?php echo base_url(); ?>assets/dist/vendors/html2canvas/html2canvas.js"></script>
  <!-- QRCode1 -->
  <script src='<?php echo base_url(); ?>assets/dist/vendors/qrcode1/jquery-qrcode-0.14.0.min.js'></script>
  <script src="<?php echo base_url(); ?>assets/dist/vendors/qrcode1/main.js"></script>

  <!--############################### CUSTOM JS ######################################### -->
  <script src="<?php echo base_url(); ?>uploads/company/<?php echo $companyName; ?>/style/js/myStyle.js"></script>
  <!--############################### CUSTOM JS ######################################### -->

  <script type="text/javascript">
  // To make Pace works on Ajax calls
  $(document).ajaxStart(function () {
    Pace.restart()
  })
  $('.ajax').click(function () {
    $.ajax({
      url: '#', success: function (result) {
        $('.ajax-content').html('<hr>Ajax Request Completed !')
      }
    })
  })

  // ################# LOCK ME #################
  $(document).ready(function(){
    function lockMe(){
      window.location.href = '<?php echo base_url() ?>lockMe';
    }

    $(document).mousemove(function(event) {
        clearInterval(interval);
        clearTimeout(timeout);
        
        timeout = setTimeout(function() {
          interval = startInterval();
        }, 60000);
    });
    $(document).keydown(function(event){
      clearInterval(interval);
        clearTimeout(timeout);
        
        timeout = setTimeout(function() {
          interval = startInterval();
        }, 60000);
    });
    function startInterval() {
      return setInterval(lockMe, 600000);
      }
    var interval = startInterval(), timeout;
  });
  // ################# LOCK ME #################

  // ################# WELCOME MESSAGE #################
  $(function(){
    if ('speechSynthesis' in window) {
      speechSynthesis.onvoiceschanged = function() {
        var $voicelist = $('#voices');

        if($voicelist.find('option').length == 0) {
          speechSynthesis.getVoices().forEach(function(voice, index) {
            var $option = $('<option>')
            .val(index)
            .html(voice.name + (voice.default ? ' (default)' :''));

            $voicelist.append($option);
          });

          $voicelist.material_select();
        }
      }

      $( document ).ready(function() {
        if(<?php echo $session->get('welcomeMessageSound')?> == true){
        var text = 'Good to see you <?php echo $userName; ?>';//'Good to see you <?php echo $userName; ?>';
        }

      
      // $('#submit').click(function(){
      //   var text = 'Amir Rashid';
        var msg = new SpeechSynthesisUtterance();
        var voices = window.speechSynthesis.getVoices();
        msg.voice = voices[10];
        msg.rate = 10 / 10;
        msg.pitch = 8;
        msg.text = text;

        msg.onend = function(e) {
          console.log('Finished in ' + event.elapsedTime + ' seconds.');
        };

        speechSynthesis.speak(msg);
      })
    }
  });
  <?php echo $session->set(array('welcomeMessageSound' => false)); ?>
  // ################# WELCOME MESSAGE #################
  </script>
  <script>
  $(function () {
    $('#myForm').validate({
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
</head>
<script src="https://cdn.jsdelivr.net/gh/gitbrent/pptxgenjs@3.11.0/dist/pptxgen.bundle.js"></script>
<!--
BODY TAG OPTIONS:
=================
Apply one or more of the following classes to get the
desired effect
|---------------------------------------------------------|
| NAVBAR COLORS | navbar-light navbar-white               |
|               | navbar-light                            |
|               | navbar-dark                             |
|               | navbar-dark navbar-danger               |
|               | navbar-dark navbar-primary              |
|               | navbar-dark navbar-info                 |
|               | navbar-dark navbar-success              |
|               | navbar-dark navbar-pink                 |
|               | navbar-dark navbar-teal                 |
|               | navbar-dark navbar-indigo               |
|               | navbar-dark navbar-gray                 |
|               | navbar-light navbar-orange              |
|               | navbar-light navbar-warning             |
|---------------------------------------------------------|
|LAYOUT OPTIONS | sidebar-mini layout-boxed               |
|               | sidebar-mini sidebar-collapse           |
|               | sidebar-mini layout-footer-fixed        |
|               | sidebar-mini layout-fixed               |
|               | sidebar-mini layout-navbar-fixed        |
|               | layout-top-nav                          |
|               | sidebar-collapse layout-top-nav         |
|---------------------------------------------------------|
-->
<?php
  $myAction = '';
  if(count(explode('/', $_SERVER['REQUEST_URI'])) > 3){
    if(explode('/', $_SERVER['REQUEST_URI'])[3] == 'dashboard'){
      $myAction = explode('/', $_SERVER['REQUEST_URI'])[3];
    }
  }
  if(count(explode('/', $_SERVER['REQUEST_URI'])) > 2){
    if(explode('/', $_SERVER['REQUEST_URI'])[2] == 'dashboard'){
      $myAction = explode('/', $_SERVER['REQUEST_URI'])[2];
    }
  }
?>
<body class="hold-transition sidebar-mini layout-navbar-fixed <?php if($myAction == 'dashboard'){echo 'layout-fixed';}else{echo 'layout-fixed sidebar-collapse';} ?> pace-primary">
<a id="fullscreenButton" data-widget="fullscreen"></a>
<div id="loader" style="display: none;"><i class="fas fa-spin fa-spinner fa-4x"></i><!-- <img style="width: 10%; height: auto;" src="<?php echo base_url(); ?>assets/dist/gif/3.gif" class="img-circle" alt="Loader"> --></div>
<div class="wrapper">

  <!-- Navbar -->
  <nav id="myHeader" class="main-header navbar navbar-expand <?php echo $companyTheme; ?>" style="background-color: #284576 !important;">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a tabindex="2" class="nav-link" data-widget="pushmenu" href="#" role="button" aria-label="Expand/Contract Menu"><i class="fas fa-bars" style="color:white;"></i></a>
      </li>
      <!-- <li class="nav-item d-none d-sm-inline-block">
        <a href="index3.html" class="nav-link">Home</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Contact</a>
      </li> -->
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
      <?php 
      if($roleId <> '1'){
        if($mode == 'Maintenance'){
          echo '<style>html{
            background-color: grey;
            opacity: 0.4;
            pointer-events:none;
          }</style>';
        } 
      }
      ?>
      <li class="nav-item col-md-12">
        <a class="nav-link"></a>
      </li>
      <li class="nav-item col-md-12">
        <a class="nav-link"></a>
      </li>
      <li class="nav-item">
        <a class="nav-link"><span <?php if($mode == 'Active'){echo 'style="cursor: default;border-radius: 2ex; background-color: #009688 !important;"';}else{echo 'style="cursor: default;border-radius: 2ex; background-color: #ed1c24 !important;"';} ?> class="label label-warning col-md-1 col-md-push-4 hidden-xs"><i class="fas fa-circle-notch" style="color: white; margin-left: 100%; margin-right: 100%;"></i></span></a>
      </li>
    </ul>

    <!-- SEARCH FORM -->
    <!-- <form class="form-inline ml-3">
      <div class="input-group input-group-sm">
        <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-navbar" type="submit">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </div>
    </form> -->

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <li class="nav-item d-none d-sm-inline-block">
        <a tabindex="3" href="<?php echo base_url(); ?>helpdesk/lookup" title="Help Desk" class="nav-link" aria-label="Help Desk">
          <i class="fa fa-question" style="color: white;"></i>
        </a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a tabindex="4" href="<?php echo base_url(); ?>lockMe" class="nav-link" aria-label="Lock Me">
          <i class="fa fa-lock" style="color: white;"></i>
        </a>
      </li>

      <li class="nav-item dropdown">
        <a tabindex="5" class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-bell" style="color:white;"></i>
          <?php
          $result = $loginModel->alertGet();
          $resultCount = count($result);
          ?>
          <span class="badge badge-warning navbar-badge"><?php echo $resultCount ?></span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <?php
          if(count($result) > 0)
          {
            echo '<span class="dropdown-item dropdown-header" style="cursor:default;"><span class="translateMe">You have</span> '.$resultCount.' <span class="translateMe">notifications</span></span>';
            $count = 0; // Initialize counter
            foreach ($result as $res)
            { 
              if ($count >= 5) break; // Stop loop after 2 iterations
              $dateTime = $res->dateTime;
                $alertName = $res->alertName;
                $description = $res->description;
                echo '<div class="dropdown-divider"></div>
                      <a tabindex="5" class="dropdown-item" style="cursor:default; text-wrap: auto;">
                        <i class="far fa-bell mr-2" style="color:white;"></i> 
                          <div style="display: inline-block; text-align: left; width: 100%;">
                              '.$alertName.'
                          </div>
                          <span class="float-right text-muted text-sm"></span>
                      </a>

                ';
              $count++; // Increment counter
            }
          }
          else
          {
            echo '<div class="dropdown-divider"></div>
                  <a tabindex="5" class="dropdown-item" style="cursor:default;">
                    <i class="far fa-bell mr-2" style="color:white;"></i><span class="translateMe">You have no notifications</span>
                  </a>
                ';
          }
          ?>
          <div class="dropdown-divider"></div>
          <a tabindex="5" href="<?php echo base_url().'alert/lookup'; ?>" class="dropdown-item dropdown-footer"><span class="translateMe">See All Notifications</span></a>
        </div>
      </li>

      <li class="nav-item">
        <a tabindex="6" class="nav-link">
          <i title = "<?php echo $countryName ?>" class="flag-icon flag-icon-<?php echo strtolower($countryFlag); ?>"></i>
        </a>
      </li>

      <li class="nav-item dropdown user-menu">
        <a tabindex="7" href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
          <img src="<?php echo base_url(); ?>uploads/company/<?php echo $companyName; ?>/profilepic/<?php echo $profilepic; ?>" class="user-image img-circle elevation-2" alt="User Image">
          <!-- <span class="d-none d-md-inline"><?php echo $userName; ?></span> -->
        </a>
        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <!-- User image -->
          <li class="user-header bg-gray" style="background-color: #284576 !important;">
            <img src="<?php echo base_url(); ?>uploads/company/<?php echo $companyName; ?>/profilepic/<?php echo $profilepic; ?>" class="img-circle elevation-2" alt="User Image">

            <p>
              <?php echo $userName; ?>
              <small><?php echo $department.' '.$designation; ?></small>
            </p>
          </li>
          <!-- Menu Footer-->
          <li class="user-footer">
            <a tabindex="8" href="<?php echo base_url(); ?>profile/edit" class="btn btn-default btn-flat" style="color: black;"><span class="translateMe">Profile</span></a>
            <a tabindex="9" href="<?php echo base_url(); ?>logout" onclick="logoutSound()" class="btn btn-default btn-flat float-right" style="color: black;"><span class="translateMe">Sign out</span></a>
          </li>
        </ul>
      </li>

      <!-- <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li> -->

      <li class="nav-item">
        <a tabindex="10" href="<?php echo base_url(); ?>setting/edit" class="nav-link" aria-label="Edit Settings"><i class="fas fa-cogs" style="color:white;"></i></a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside id="myAside" class="main-sidebar main-sidebar-custom sidebar-dark-primary elevation-4" style="background: url('<?php echo base_url(); ?>/assets/dist/img/<?php echo $wallpaper ?>'); //background-color: #3a7a8b;">
    <!-- Brand Logo -->
    <a tabindex="1" id="firstElem1" href="<?php echo base_url(); ?>" class="brand-link">
      <img src="<?php echo base_url(); ?>uploads/company/RBI/logo/logo-mini.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8; margin-left: -5px; vertical-align: middle; //padding: 5px; background-color: #fff;">
      <span class="brand-text font-weight-light"><?php echo $companyProject; ?></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="<?php echo base_url(); ?>uploads/company/<?php echo $companyName; ?>/profilepic/<?php echo $profilepic; ?>" class="img-circle elevation-2" alt="User Image" style="height: 33.59px;">
        </div>
        <div class="info">
          <a class="d-block" style="cursor: default; color: #c2c7d0 !important;"><?php echo $userName; ?></a>
        </div>
      </div>

      <!-- SidebarSearch Form -->
      <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search1">
          <input tabindex="11" type="text" id="myInput" autocomplete="off" readonly onfocus="this.removeAttribute('readonly');" onkeyup="search()" class="form-control form-control-sidebar1" placeholder="Search" aria-label="Search" style="background-color: #f2f2f2 !important; border: 1px solid #d9d9d9 !important; color: #1f2d3d !important;">
          <!-- <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div> -->
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul id="myUL" class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
               <?php
                  $myAction = '';
                  if(count(explode('/', $_SERVER['REQUEST_URI'])) > 3){
                    if(explode('/', $_SERVER['REQUEST_URI'])[2] == 'dashboard'){
                      $myAction = explode('/', $_SERVER['REQUEST_URI'])[3];
                    }
                  }
                  if(count(explode('/', $_SERVER['REQUEST_URI'])) > 2){
                    if(explode('/', $_SERVER['REQUEST_URI'])[1] == 'dashboard'){
                      $myAction = explode('/', $_SERVER['REQUEST_URI'])[2];
                    }
                  }
                  $nodes = 0;
                  $currentLevel = 0;
                  $i = 0;
                  $result = $loginModel->rolePageHeaderGet($roleId);
                  echo "<li class='nav-item'>";
                  echo "<a tabindex='12' href='".base_url()."dashboard' class='nav-link' aria-label='Dashboard'>";
                  echo "<i class='nav-icon fas fa-tachometer-alt'></i>";
                  echo "<p><span class='translateMe'>";
                  echo "Dashboard";
                  echo "</span></p>";
                  echo "</a>";
                  echo "</li>";
                  
                  if(count($result) > 0)
                  {
                    foreach ($result as $res)
                    {   
                      $friendlyName = $res->friendlyName;
                      $pageName = $res->pageName;
                      $icon = $res->icon;
                      $url = $res->url;
                      $defaultAction = $res->defaultAction;
                      $parentId = $res->parentId;
                      $children = $res->children;
                      $head = $res->head;
                      $myHead = $res->myHead;
                      if($myAction && $myHead <> $myAction){
                        continue;
                      }
                      if($parentId != 0 && $i > 0 && isset($result[$i - 1]) && $result[$i - 1]->id == $parentId){
                        echo "<ul class='nav nav-treeview' style='background-color: #ced4da38;'>";
                        $nodes = $nodes + 1;
                      }
                      echo "<li class='nav-item'>";
                      if($children == 0){
                        if($url == 'report' || $url == 'structure' || $url == 'permission' || $url == 'user' || $url == 'auditlog' || $url == 'alert' || $url == 'alerts'){
                        echo "<a tabindex='13' href='".base_url()."$url/$defaultAction' class='nav-link' aria-label='$url'>";
                        }
                        else{
                          echo "<a tabindex='13' href='".base_url()."viewLoader/$url/$defaultAction' class='nav-link' aria-label='$url'>";
                        }
                      }
                      if($children > 0){
                        echo "<a tabindex='13' href='#' class='nav-link' aria-label='$friendlyName'>";
                      }
                      echo "<i class='nav-icon $icon'></i>";
                      echo "<p><span class='translateMe'>";
                      echo "$friendlyName";
                      if($children > 0){
                        echo "<i class='right fas fa-angle-left'></i>";
                      }

                      //$totalNew = $loginModel->unseenRecordCountGet($pageName, $roleId);
                      //$totalNew = 0;
                      // if(count($totalNew) > 0)
                      // {
                      //   foreach ($totalNew as $count)
                      //   {
                      //     $unseen = $count->unseen;
                      //   }
                      //   if($unseen > 0)
                      //   {
                      //   echo "<span class='badge badge-warning right'>$unseen</span>";
                      //   }
                      //   else
                      //   {
                      //    echo "";
                      //   }
                      // }
                      echo "<span class='badge badge-warning right'></span>";

                      echo "</p></span>";
                      echo "</a>";
                      if($parentId == 0 && $children == 0){
                        echo "</li>";
                      }
                      if($parentId <> 0 && $children == 0){
                        echo "</li>";
                      }
                      if(count($result) - 1 <> $i){
                        if($parentId <> 0 && $result[$i+1]->parentId < $res->parentId){
                          echo "</ul>";
                          echo "</li>";
                          $nodes = $nodes - 1;
                          $currentLevel = $nodes;
                          for ($x = 1; $x <= $currentLevel; $x++) {
                            if($result[$i+1]->head <> $res->head){
                              echo "</ul>";
                              echo "</li>";
                              $nodes = $nodes - 1;
                            }
                          }
                          $difference = $res->parentId - $result[$i+1]->parentId;
                          if(($result[$i+1]->head == $res->head) && ($result[$i+1]->children > 0) && ($result[$i+1]->parentId < $res->parentId) && ($difference > 1)){
                            echo "</ul>";
                            echo "</li>";
                            $nodes = $nodes - 1;
                          }
                        }
                      }
                      $i++;
                  
                    }
                  }
                  // echo "<li class='nav-item'>";
                  // echo "<a href='".base_url()."#' class='nav-link'>";
                  // echo "<i class='nav-icon fas fa-book'></i>";
                  // echo "<p>";
                  // echo "User Guide";
                  // echo "</p>";
                  // echo "</a>";
                  // echo "</li>";
                  // echo "<li class='nav-item'>";
                  // echo "<a href='".base_url()."helpdesk' class='nav-link'>";
                  // echo "<i class='nav-icon fas fa-question'></i>";
                  // echo "<p>";
                  // echo "Help Desk";
                  // echo "</p>";
                  // echo "</a>";
                  // echo "</li>";
              ?>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
    <div class="sidebar-custom">
      <!-- <a href="<?php echo base_url(); ?>userguide/lookup" class="btn btn-link"><i class="fas fa-book"></i> User Guide</a> -->
      <a  tabindex="14" href="<?php echo base_url(); ?>helpdesk/lookup" class="btn btn-secondary hide-on-collapse" aria-label="Help Desk"><i class="fa fa-question"></i></a>
    </div>
    <!-- /.sidebar-custom -->
  </aside>

  <div class="viewPage"></div>

  <div class="modal modal-primary fade" id="modal-default">
    <!-- modal-dialog -->
    <div class="modal-dialog">
      <!-- modal-content -->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Default Modal</h4>
        </div>
        <div class="modal-body">
          <p>One fine body&hellip;</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" style="background-color: #006ee5; border-color: #006ee5;">Save changes</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>

  <script type="text/javascript">
    $(window).on('beforeunload', function(){
      //Pace.restart();
      $("#loader").addClass("loader");
      $("body").addClass("pageLoaderBackground");
      $(".wrapper").addClass("pageLoader");
      $("#loader").show();
    });
    document.onreadystatechange = function () {
      var state = document.readyState
      if (state == 'interactive') {
           $("#loader").addClass("loader");
           $("body").addClass("pageLoaderBackground");
           $(".wrapper").addClass("pageLoader");
           $("#loader").show();
      } else if (state == 'complete') {
           $("#loader").removeClass("loader");
           $("body").removeClass("pageLoaderBackground");
           $(".wrapper").removeClass("pageLoader");
           $("#loader").hide();
      }
    }
    Pace.stop();
    $(document).ajaxStart(function(){
      Pace.restart();
      $("#loader").addClass("loader");
      $("body").addClass("pageLoaderBackground");
      $(".wrapper").addClass("pageLoader");
      $("#loader").show();
    });
    $(document).ajaxStop(function(){
      $("#loader").removeClass("loader");
      $("body").removeClass("pageLoaderBackground");
      $(".wrapper").removeClass("pageLoader");
      $("#loader").hide();
      Pace.stop();
    });
    function loadPage(page) {
      window.history.pushState('obj', 'newtitle', page);
      $(".viewPage").load(page);
    }
  </script>
  <script type="text/javascript">
    $("#myInput").on('keyup', function(){
      var value = $(this).val().toLowerCase();
      $("#myUL li").each(function () {
          if ($(this).text().toLowerCase().search(value) > -1) {
              $(this).slideDown();
              $(this).addClass('menu-is-opening menu-open');
              //$(this).find('.nav-icon').removeClass('fa-circle');
              //$(this).find('.nav-icon').addClass('fa-dot-circle');
          } else {
              $(this).slideUp();
          }
      });
      $("#myUL ul").each(function () {
          if ($(this).text().toLowerCase().search(value) > -1) {
              $(this).slideDown();
              $(this).addClass('menu-is-opening menu-open');
              //$(this).find('.nav-icon').removeClass('fa-circle');
              //$(this).find('.nav-icon').addClass('fa-dot-circle');
          } else {
              $(this).slideUp();
          }
      });   
    })
    $("#myInput").on('keyup', function(){
        if($("#myInput").val() == ''){
          $("#myUL ul").slideUp();
        }
    });

    $(document).ready(function(){
      $('#myUL li ul').each(function () {
        if ($(this).find('a').hasClass('active')) {
          $(this).parent().addClass('menu-is-opening menu-open');
          //$(this).find('.nav-icon').removeClass('fa-circle');
          //$(this).find('.nav-icon').addClass('fa-dot-circle');
        }
      });
    });
  </script>
  <audio id="signoutSound" src="assets/ding.mp3"></audio>
  <script>
    function logoutSound() {
      document.getElementById("signoutSound").play();
    }
  </script>
  <script type="text/javascript">
    // $(document).ready(function(){
    //    $.ajax({
    //     url:"<?php echo base_url(); ?>login/alertAjaxGet",
    //     method:"POST",
    //     success:function(data)
    //     {
    //       if(data){
    //       var result = data.split('_');
    //       showNotification(result[0], result[1]);
    //       }
    //     }
    //    });
    // });
    // function showNotification(name, data) {
    //   Notification.requestPermission(function(result) {
    //     if (result === 'granted') {
    //       navigator.serviceWorker.ready.then(function(registration) {
    //         registration.showNotification(name, {
    //           body: data,
    //           icon: 'uploads/company/DRAP/logo/logo.png',
    //           vibrate: [200, 100, 200, 100, 200, 100, 200],
    //           tag: 'vibration-sample'
    //         });
    //       });
    //     }
    //   });
    // }
    // function showNotification(name, data) {
    //   navigator.serviceWorker.register('sw.js');
    //   Notification.requestPermission(function(result) {
    //     if (result === 'granted') {
    //       new Notification(name, {
    //         body: data,
    //         icon: 'uploads/company/DRAP/logo/logo.png',
    //         vibrate: [200, 100, 200, 100, 200, 100, 200],
    //         tag: 'vibration-sample'
    //       });
    //     }
    //   });
    // }
    // setInterval(function(){ notification(); }, 120000);
    // function notification(){
    //    $.ajax({
    //     url:"<?php echo base_url(); ?>login/alertAjaxGet",
    //     method:"POST",
    //     success:function(data)
    //     {
    //       if(data){
    //       var result = data.split('_');
    //       showNotification(result[0], result[1]);
    //       }
    //     }
    //    });
    // }
  </script>
  <script type="text/javascript">
    // function safeExit() {
    //   return "Write something clever here...";
    // }
  </script>
  <script type="text/javascript">
    function h2c(){
      html2canvas(document.body).then(function(canvas) {
        document.body.appendChild(canvas);
        var myImage = canvas.toDataURL("img/png");
        //alert(myImage);
        //$('#bla').append(canvas);
        //$('#bla').attr('src', myImage);
      });

      // html2canvas(document.querySelector("#capture")).then(canvas => {
      //   document.body.appendChild(canvas);
      //   $('#bla').append(canvas);
      // });
      
      // html2canvas($("body"), {
      //   onrendered: function(canvas) {
      //     var myImage = canvas.toDataURL("img/png");
      //     window.open(myImage);
      //     //alert(myImage);
      //     }
      // });
    }
  </script>
  <!-- <script type="text/javascript">
    $(document).ready(function(){
      $('#fullscreenButton').trigger('click');
    });
  </script> -->

  <script type="text/javascript">
    function loadMyTable(myTable, ordering, pageLength, isDBSearch){
      $('#'+myTable+' thead tr').clone(true).appendTo( '#'+myTable+' thead' );
      $('#'+myTable+' thead tr:eq(1) th').each( function (i) {
          var title = $(this).text();
          if(title == '' || title == 'S.#' || title == 'Action'){
            $(this).html( '' );
          }
          else{
            $(this).html( '<input type="text" class="form-control d-none" placeholder="Search '+title+'" />' );
            if(isDBSearch == 'Yes'){
              $(this).html('<div class="input-group input-group-sm float-right d-none" style="width: 150px;">&nbsp;<input type="text" name="searchText[]" value="" class="form-control float-right" placeholder="Search"><div class="input-group-append"><button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button></div></div>');
            }
          }
      } );
   
      var table = $('#'+myTable).DataTable({
          'orderCellsTop': true,
          'fixedHeader'  : true,
          'paging'       : true,
          'lengthChange' : false,
          'searching'    : true,
          'ordering'     : ordering,
          'info'         : true,
          'iDisplayLength': pageLength,
          'autoWidth'    : true,
          'search'       : {'caseInsensitive': false},
          //'columnDefs'   : [{ 'type': 'html-input', 'targets': ['_all'] }],
          //'columnDefs'   : [{ 'type': 'html-input', 'targets': [1] }],
          'buttons'      : [{'extend':'copy', 'exportOptions': {'columns': ':visible' }}, {text: 'pptx', 
                          action: function (){
                            let pptx = new PptxGenJS();
                            let slide = pptx.addSlide();
                            let table = document.getElementById('table');
                            let rowData = [];

                          for (let i = 0; i < table.rows.length; i++) {
                          //   // Get the row object
                            let row = table.rows[i];
                            let d = []
                          //   // Loop through the cells of the row
                          for (let j = 0; j < row.cells.length; j++) {
                            let cell = row.cells[j];
                              if (j==4 && i > 0) {
                                d.push({ text:cell.textContent , options: { fontSize:8} })
                          // Get the cell object
                            
                           }
                          else  { d.push(cell.textContent)};
                        }
                          rowData.push(d)
                        }
                        slide.addTable(rowData,{ align:'center',fontSize:11, border:['5pt','5pt','5pt','5pt','#000000'],autoPage:true });
                        pptx.writeFile({ fileName: 'demo.pptx' });
                       }, titleAttr: 'Please use MS Excel for larger data table export'},
                       {text: 'word', action: function(){
                          var htmltable= document.getElementById('table');
                        var html = htmltable.outerHTML;
                        window.open('data:application/msword,' + '\uFEFF' + encodeURIComponent(html));
                        }, titleAttr: 'Please use MS Excel for larger data table export'},
                       {'extend':'csv', 'exportOptions': {'columns': ':visible' }}, {'extend':'excel', 'exportOptions': {'columns': ':visible' }}, {'extend':'pdf', 'exportOptions': {'columns': ':visible' }, titleAttr: 'Please use MS Excel for larger data table export'}, {'extend':'print', 'exportOptions': {'columns': ':visible' }}, {'extend': 'colvis'}],
          initComplete   : function () {
            this.api().columns().every( function () {
              var that = this;
              $( 'input', $('#'+myTable+' thead tr:eq(1) th').eq( this.index() ) ).on( 'keyup change clear', function () {
                  if ( that.search() !== this.value ) {
                      that
                          .search( this.value )
                          .draw();
                  }
              } );
            } );
          }
      }).buttons().container().appendTo('#'+myTable+'_wrapper .col-md-6:eq(0)');
      $( '#'+myTable+" thead th" ).click(function() {
        $( '#'+myTable+" thead th input" ).removeClass('d-none');
        if(isDBSearch == 'Yes'){
          $( '#'+myTable+" thead th div" ).removeClass('d-none');
        }
      });
      $(':submit').on('click', function(){
        $('table').DataTable().search('').columns().search('').draw();
      });
      $('#'+myTable+' tbody').on( 'click', '.trash', function () {
        if($('#'+myTable+' tbody tr').length !== 1){
          // $('#'+myTable).DataTable()
          //     .row( $(this).parents('tr'))
          //     .remove()
          //     .draw();
          $(this).parents().closest('tr').attr('style', 'background-color: #c22f3c !important');
          $(this).parents().closest('tr').find('.deleteRow').val(1);
        }
      });
    }
  </script>
  <script type="text/javascript">
    $('body').css('opacity', '0.4');
    $(document).ready(function(){
       $('body').css('opacity', '1');
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
