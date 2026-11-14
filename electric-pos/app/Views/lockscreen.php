<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>RBI | Lockscreen</title>
  <link id="favicon" rel="icon" href="<?php echo base_url(); ?>uploads/company/RBI/logo/logo-mini.png"/>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/adminlte.min.css">
</head>
<body class="hold-transition lockscreen" style="background: url('assets/dist/img/<?php echo $wallpaper ?>'); background-size:100% 100%; overflow-y: hidden;">
<!-- Automatic element centering -->
<div class="lockscreen-wrapper">
  <div class="lockscreen-logo">
    <a href="<?php echo base_url(); ?>" class="h1"><b><?php echo $companyProject; ?></b></a>
  </div>
  <!-- User name -->
  <div class="lockscreen-name"><?php echo $userName; ?></div>

  <!-- START LOCK SCREEN ITEM -->
  <div class="lockscreen-item">
    <!-- lockscreen image -->
    <div class="lockscreen-image">
      <img src="<?php echo base_url(); ?>uploads/company/<?php echo $companyName; ?>/profilepic/<?php echo $profilepic; ?>" alt="User Image">
    </div>
    <!-- /.lockscreen-image -->

    <!-- lockscreen credentials (contains the form) -->
    <form action="<?php echo base_url(); ?>unlockMe" method="post" class="lockscreen-credentials">
      <div class="input-group">
        <input type="hidden" name="email" autocomplete="off" readonly onfocus="this.removeAttribute('readonly');" value="<?php echo $session->get('userEmail') ?>" class="form-control" placeholder="Email">
        <input type="password" name="password" autocomplete="off" readonly onfocus="this.removeAttribute('readonly');" class="form-control" placeholder="password">

        <div class="input-group-append">
          <button type="submit" class="btn">
            <i class="fas fa-arrow-right text-muted"></i>
          </button>
        </div>
      </div>
    </form>
    <!-- /.lockscreen credentials -->

  </div>
  <!-- /.lockscreen-item -->
  <div class="help-block text-center">
    <span class="translateMe">Enter your password to retrieve your session</span>
  </div>
  <!-- <div class="text-center">
    <a href="<?php echo base_url(); ?>logout">Or sign in as a different user</a>
  </div> -->
  <div class="lockscreen-footer text-center">
    Copyright &copy; <?php echo date('Y'); ?> <b><a href="<?php echo base_url(); ?>" style="color:#3f51b5;" class="text-black"><?php echo $companyAddress; ?></a></b><br>
    All rights reserved
  </div>
</div>
<!-- /.center -->

<!-- jQuery -->
<script src="<?php echo base_url(); ?>assets/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="<?php echo base_url(); ?>assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

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
