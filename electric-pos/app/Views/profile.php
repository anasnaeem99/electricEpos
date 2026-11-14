<?php
// if($userInfo[0]->status == 'Completed'){
//   //if($userId <> 27){
//   header("Location:".base_url().substr($pageTitle[0]->url, 0, strcspn($pageTitle[0]->url, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ')));
//   exit();
//   //}
// }

$myAction = '';
if(explode('/', $_SERVER['REQUEST_URI'])[2] == $pageTitle[0]->url){
  $myAction = explode('/', $_SERVER['REQUEST_URI'])[3];
}
if(explode('/', $_SERVER['REQUEST_URI'])[1] == $pageTitle[0]->url){
  $myAction = explode('/', $_SERVER['REQUEST_URI'])[2];
}
?>
<head>
  
</head>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1><i <?php echo "class='".$pageTitle[0]->icon."'"; ?>></i> <span class="translateMe"><?php echo $pageTitle[0]->friendlyName; ?></span></h1>
        </div>
        <div class="col-sm-6">
          <h1><a href="<?php echo base_url(); ?>" style="background-color: #3a7a8b !important;" <?php if($myAction == 'add' || $myAction == 'edit'){echo 'onclick="return confirm(\'Changes may not be saved.\')"';} ?> class="btn btn-secondary float-right"><i class="fa fa-arrow-left"></i> <span class="translateMe">Go Back</span></a></h1>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-md-8">
      </div>
      <div class="col-md-4 float-right">
        <?php
            if($session->getFlashdata('error'))
            {
        ?>
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h4><i class="icon fa fa-warning"></i> <span class="translateMe">Alert!</span></h4>
            <?php echo $session->getFlashdata('error'); ?>                    
        </div>
        <?php } ?>
        <?php  
            if($session->getFlashdata('success'))
            {
        ?>
        <div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h4><i class="icon fa fa-check"></i> <span class="translateMe">Alert!</span></h4>
            <?php echo $session->getFlashdata('success'); ?>
        </div>
        <?php } ?>
        
        <div class="row">
            <div class="col-md-12">
                <?php //echo validation_errors('<div class="alert alert-danger alert-dismissable">', ' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>'); ?>
            </div>
        </div>
      </div>
    </div>
    <div class="container-fluid">
      <?php if($myAction == 'add' || $myAction == 'edit'){echo '<form id="myForm" action="'.base_url().$pageTitle[0]->url.'/submit" enctype="multipart/form-data" method="post" accept-charset="utf-8">';}?>
        <div class="card card-primary card-outline1">
          <div class="card-header" style="background-color: #006ee5;">
            <h3 class="card-title"><span class="translateMe">Edit Details</span></h3>

            <!-- <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
              <button type="button" class="btn btn-tool" data-card-widget="remove">
                <i class="fas fa-times"></i>
              </button>
            </div> -->
          </div>
          <!-- /.card-header -->
          <div class="card-body cardBodyTransaction">
            <div class="row">

              <div class="col-md-6">
                <div class="form-group">
                  <?php $label = 'User Name'; ?>
                  <label><span class="translateMe"><?php echo $label; ?></span></label>
                  <?php $column = 'userName'; ?>
                  <input type="text" id="<?php echo @$column; ?>" name="<?php echo @$column; ?>" aria-label="<?php echo @$column; ?>" value="<?php echo @$records[0]->$column; ?>" autocomplete="off" readonly onfocus="this.removeAttribute('readonly');" class="form-control required">
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  <?php $label = 'Old Password'; ?>
                  <label><span class="translateMe"><?php echo $label; ?></span></label>
                  <?php $column = 'oldpassword'; ?>
                    <input type="password" id="<?php echo @$column; ?>" name="<?php echo @$column; ?>" aria-label="<?php echo @$column; ?>" value="" autocomplete="off" readonly onfocus="this.removeAttribute('readonly');" class="form-control">
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  <?php $label = 'Profile Picture'; ?>
                  <label><span class="translateMe"><?php echo $label; ?></span> <i>[<font style="color: #d0382d;"><span class="translateMe">File Format</span></font><font style="color: #3f51b5;"> (*.JPEG, *.PNG)</font><font style="color: #d0382d;"> <span class="translateMe">Max. File Size</span></font><font style="color: #3f51b5;"> 5 MB</font>]</i></label>
                  <?php $column = 'profilepic'; ?>
                  <div class="custom-file">
                    <input type="hidden" id="<?php echo @$column; ?>Hidden" name="<?php echo @$column; ?>" aria-label="<?php echo @$column; ?>" value="<?php echo @$records[0]->$column; ?>">
                    <input type="file" id="<?php echo @$column; ?>" name="<?php echo @$column; ?>" aria-label="<?php echo @$column; ?>" value="<?php echo @$records[0]->$column; ?>" class="custom-file-input1">
                    <!-- <label class="custom-file-label" for="<?php echo @$column; ?>"><span class="translateMe">Choose file</span></label> -->
                  </div>
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  <?php $label = 'New Password'; ?>
                  <label><span class="translateMe"><?php echo $label; ?></span></label>
                  <?php $column = 'password'; ?>
                    <input type="password" id="<?php echo @$column; ?>" name="<?php echo @$column; ?>" aria-label="<?php echo @$column; ?>" value="" autocomplete="off" readonly onfocus="this.removeAttribute('readonly');" class="form-control" minlength="4">
                </div>
              </div>

              <div class="col-md-6">
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  <?php $label = 'Confirm New Password'; ?>
                  <label><span class="translateMe"><?php echo $label; ?></span></label>
                  <?php $column = 'cpassword'; ?>
                    <input type="password" id="<?php echo @$column; ?>" name="<?php echo @$column; ?>" aria-label="<?php echo @$column; ?>" value="" autocomplete="off" readonly onfocus="this.removeAttribute('readonly');" class="form-control" equalTo="#password">
                </div>
              </div>

              <div class="col-md-4" style="display:none;">
                <div class="form-group">
                  <?php $label = 'PIN'; ?>
                  <label><?php echo $label; ?></label>
                  <?php $column = 'pin'; ?>
                  <input type="text" id="<?php echo @$column; ?>" name="<?php echo @$column; ?>" aria-label="<?php echo @$column; ?>" value="<?php echo @$records[0]->$column; ?>" autocomplete="off" readonly onfocus="this.removeAttribute('readonly');" class="form-control required" maxlength="4" onpaste="return false;">
                </div>
              </div>
              
              <div class="col-md-6">
                <div class="form-group">
                  <?php $label = 'Email'; ?>
                  <label><span class="translateMe"><?php echo $label; ?></span></label>
                  <?php $column = 'email'; ?>
                  <input readonly type="text" id="<?php echo @$column; ?>" name="<?php echo @$column; ?>" aria-label="<?php echo @$column; ?>" value="<?php echo @$records[0]->$column; ?>" autocomplete="off" class="form-control required">
                </div>
              </div>
              
              <div class="col-md-6">
                <div class="form-group">
                  <?php $label = 'Country'; ?>
                  <label><span class="translateMe"><?php echo $label; ?></span></label>
                  <?php $column = 'countryId'; ?>
                    <select disabled <?php if($myAction == 'view'){echo 'disabled';}?> class="form-control select2 required" id="<?php echo @$column; ?>" name="<?php echo @$column; ?>">
                      <option value="">Select <?php echo @$label; ?></option>
                      <?php
                        if(!empty($country))
                        {
                          foreach ($country as $record)
                          {
                              ?>
                              <option value="<?php echo $record->id ?>" <?php if($record->id == @$records[0]->$column){ echo 'selected'; } ?>><?php echo $record->countryName ?></option>
                              <?php
                          }
                        }
                      ?>
                    </select>
                </div>
              </div>

            </div>
          </div>
          <!-- /.card-body -->
          <div class="card-footer">
            <button type="submit" onclick="return confirm('Are you sure you want to update this settings?')" class="btn btn-primary" style="background-color: #006ee5; border-color: #006ee5;"><span class="translateMe">Submit</span></button>
          </div>
        <!-- /.card -->
        </div>
        <!-- /.row -->
      </form>
    </div>
    <!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</div>

<script type="text/javascript">
  $('#pin').keypress(function (e) {
    var regex = new RegExp("^[0-9]+$");
    var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
    if (regex.test(str)) {
        return true;
    }

    e.preventDefault();
    return false;
  });
</script>