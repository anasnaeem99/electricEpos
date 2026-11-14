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
          <h1><i <?php echo "class='".$pageTitle[0]->icon."'"; ?>></i> <?php echo $pageTitle[0]->friendlyName; ?></h1>
        </div>
        <div class="col-sm-6">
          <h1><a href="<?php echo base_url(); ?>" style="background-color: #3a7a8b !important;" class="btn btn-secondary float-right"><i class="fa fa-arrow-left"></i> Go Back</a></h1>
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
                <?php echo validation_errors('<div class="alert alert-danger alert-dismissable">', ' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>'); ?>
            </div>
        </div>
      </div>
    </div>
    <div class="row">
        <div class="col-md-12 text-center">
            <div class="form-group">
                <a class="btn btn-success" data-toggle="collapse" data-target="#report" onclick="$(this).find('i').toggleClass('fa-minus').toggleClass('fa-plus');"><i class="fa fa-plus"></i> Select Report Filters</a>
            </div>
        </div>
    </div>
    <?php if($myAction == 'lookup'){echo '';?>
    <div class="container-fluid">
      <form action="<?php echo base_url().$pageTitle[0]->url.'/submit'; ?>" enctype="multipart/form-data" method="post" accept-charset="utf-8" id="report" class="collapse" target="_blank">
        <div class="card card-success card-outline1">
          <div class="card-header">
            <h3 class="card-title"></h3>

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
          <div class="card-body">
            <div class="row">

              <div class="col-md-6">
                <div class="form-group">
                  <?php $label = 'Report Type'; ?>
                  <label><?php echo $label; ?></label>
                  <?php $column = 'reportType'; ?>
                  <select class="form-control select2" id="<?php echo @$column; ?>" name="<?php echo @$column; ?>">
                      <option value="">Select <?php echo @$label; ?></option>
                      <?php
                        if(!empty($reportType))
                        {
                          foreach ($reportType as $record)
                          {
                              ?>
                              <option value="<?php echo $record->reportType ?>"><?php echo $record->reportType ?></option>
                              <?php
                          }
                        }
                      ?>
                    </select>
                </div>
              </div>

            </div>

            <div class="row" id="filters">
            </div>

              <script>
                $(document).ready(function(){
                  $('#reportType').change(function(){
                    var reportType = $('#reportType').val();
                    if(reportType != '0')
                    {
                     $.ajax({
                      url:"<?php echo base_url(); ?>login/reportTypeDetailAjaxGet",
                      method:"POST",
                      data:{reportType:reportType},
                      success:function(data)
                      {
                        myData = data.split('%');
                        myData1 = data.split(' - ');
                        value = '';
                        i = 0;
                        j = 0;
                        for (i = 0; i < myData.length - 1; i++) {
                          myRecord = myData[i].split(' - ');
                          for (j = 0; j < myRecord.length - 1; j++) {
                            if(reportType == 'Department Wise Users'){
                              value += '<div class="col-md-6"><div class="form-group"><label>Head</label><select class="form-control select2" id="headId" name="headId"><option value="">Select Head</option><?php
                            if(!empty($head))
                            {
                              foreach ($head as $record)
                              {
                                  ?><option value="<?php echo $record->id ?>"><?php echo $record->headName ?></option><?php
                              }
                            }
                          ?></select></div></div>';
                            }
                            if(reportType == 'Chart of Accounts1'){
                              value += '<div class="col-md-6"><div class="form-group"><label>Account Name</label><select class="form-control select2" id="coaId" name="coaId"><option value="">Select Account Name</option><?php
                                if(!empty($coa))
                                {
                                  foreach ($coa as $record)
                                  {
                                      ?><option value="<?php echo $record->id ?>"><?php echo $record->accountName ?></option><?php
                                  }
                                }
                              ?></select></div></div>';
                            }
                            else{
                              value += myRecord[1];

                              if(reportType == 'Monthly Summary Report'){
                              value += '<div class="col-md-6"><div class="form-group"><label>Manufacturer</label><select class="form-control select2" id="manufacturerId" name="manufacturerId"><option value="">Select Manufacturer</option><?php
                                if(!empty($manufacturer))
                                {
                                  foreach ($manufacturer as $record)
                                  {
                                      ?><option value="<?php echo $record->id ?>"><?php echo $record->manufacturerName ?></option><?php
                                  }
                                }
                              ?></select></div></div>';
                              }

                              if(reportType == 'Year Wise List'){
                              value += '<div class="col-md-6"><div class="form-group"><label>Country</label><select class="form-control select2" id="countryId" name="countryId"><option value="">Select Country</option><?php
                                if(!empty($country))
                                {
                                  foreach ($country as $record)
                                  {
                                      ?><option value="<?php echo $record->id ?>"><?php echo $record->countryName ?></option><?php
                                  }
                                }
                              ?></select></div></div>';
                              }

                              if(reportType == 'Year Wise List'){
                              value += '<div class="col-md-6"><div class="form-group"><label>Product Category</label><select class="form-control" id="productCategory" name="productCategory"><option value="">Select Product Category</option><option value="CHC">CHC</option><option value="PH">PH</option></select></div></div>';
                              }

                              if(reportType == 'Year Wise List'){
                              value += '<div class="col-md-6"><div class="form-group"><label>Manufacturer</label><select class="form-control select2" id="manufacturerId" name="manufacturerId"><option value="">Select Manufacturer</option><?php
                                if(!empty($manufacturer))
                                {
                                  foreach ($manufacturer as $record)
                                  {
                                      ?><option value="<?php echo $record->id ?>"><?php echo $record->manufacturerName ?></option><?php
                                  }
                                }
                              ?></select></div></div>';
                              }

                              if(reportType == 'Search Molecule'){
                              value += '<div class="col-md-6"><div class="form-group"><label>Generic Name</label><input type="text" id="genericName" name="genericName" value="" class="form-control"></div></div>';
                              }
                            }
                          }
                        }
                        $('#filters').html(value);
                        $('.select2').select2();
                        $('#dateRange').daterangepicker({ timePicker: false, timePickerIncrement: 30, locale: { format: 'DD-MMM-YY' }})
                      }
                     });
                    }
                  });
                });
              </script>

          </div>
          <!-- /.card-body -->
          <div class="card-footer text-right" style="border-top: 3px solid #28a745 !important">
            <input type="submit" class="btn btn-success" value="Submit">
          </div>
        <!-- /.card -->
        </div>
        <!-- /.row -->
      </form>
      <div class="card card-success card-outline1">
        <div class="card-header">
          <h3 class="card-title"></h3>
        </div>
        <!-- /.card-header -->
        <div class="card-footer text-right" style="border-top: 0px !important">
        </div>
      <!-- /.card -->
    </div>
    <!-- /.container-fluid -->
    <?php echo '';}?>
  </section>
  <!-- /.content -->
</div>

<script type="text/javascript">
  
</script>