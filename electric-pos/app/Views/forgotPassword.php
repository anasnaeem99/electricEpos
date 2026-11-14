<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>RBI | Forgot Password</title>
  <link id="favicon" rel="icon" href="<?php echo base_url(); ?>uploads/company/RBI/logo/logo-mini.png"/>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">
<div class="wrapper">
<div class="login-box" style="//box-shadow: 0px 2px 30px #f47635;//border: 8px solid #1c395d;border-radius:2%;//background-color: #344063;background-color: #2f516f; box-shadow: 0px 0px 20px 0px #989898; margin: auto;">
  <div class="card card-outline card-primary" style="border-top: 3px solid #284576; ">
    <div class="card-header text-center">
      <a href="<?php echo base_url(); ?>" class="h1"><b><span class="translateMe">RBI Tool</span></b><sub style="bottom: 0em; font-size: 45%;"></sub></a>
    </div>
    <div class="card-body">
      <p class="login-box-msg"><span class="translateMe">You forgot your password? Here you can easily retrieve a new password.</span></p>
      <form id="myForm" action="<?php echo base_url(); ?>resetPassword" method="post">
        <div class="input-group mb-3">
          <input type="email" name="email" autocomplete="off" readonly onfocus="this.removeAttribute('readonly');" value="" class="form-control" placeholder="Email">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
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
                <label for='message'><span class="translateMe">Enter the code above here :</span></label>
                <br>
                <input id="captcha_code" name="captcha_code" type="text">
                <br>
                Can't read the image? click <a href='javascript: refreshCaptcha();'>here</a> to refresh.</td>
            </tr>
          </table>
        </div>
        <div class="row">
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block" style="background-color: #006ee5 !important; border-color: #006ee5 !important;"><span class="translateMe">Request new password</span></button>
          </div>
          <!-- /.col -->
        </div>
      </form>
      <p class="mt-3 mb-1">
        <a href="<?php echo base_url(); ?>"><span class="translateMe">Login</span></a>
      </p>
    </div>
    <!-- /.login-card-body -->
  </div>
  <!-- <br> -->
  <div class="col-md-12 col-xs-12" style="background-color: #284576">
    <br>
  </div>
  <div class="col-md-12 col-xs-12">
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
            <?php if (\Config\Services::validation()->getErrors()): ?>
          <div class="alert alert-danger alert-dismissable">
            <?= \Config\Services::validation()->listErrors() ?>
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
          </div>
        <?php endif; ?>
        </div>
    </div>
  </div>
</div>
<!-- /.login-box -->
<!--<footer>-->
<!--   <a href="http://www.cybersoftechnologies.com" target="_blank"><img style="width: 122px; height: 68px; margin-right: 30px; margin-top: 100px;" src="<?php echo base_url(); ?>uploads/company/RBI/logo/Logo_CyberSoftechnologies.png" class="img-fluid pull-right" alt="Logo"></a>-->
<!--</footer>-->
</div>

<!-- jQuery -->
<script src="<?php echo base_url(); ?>assets/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="<?php echo base_url(); ?>assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url(); ?>assets/dist/js/adminlte.min.js"></script>
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
</body>
</html>
