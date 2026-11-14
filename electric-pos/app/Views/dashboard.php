<?php
// if($userInfo[0]->status == 'Completed'){
//   //if($userId <> 27){
//   header("Location:".base_url().substr($pageTitle[0]->url, 0, strcspn($pageTitle[0]->url, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ')));
//   exit();
//   //}
// }

?>
 
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-md-12">
          <h1 class="m-0"><i class="fas fa-tachometer-alt"></i> <span class="translateMe">Dashboard</span> - <?php echo $department.' '.$designation; ?></h1>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <div class="content">
    <div class="container-fluid">
      <br>
      <div id="myDashboardHead" style="box-shadow: -20px -6px 20px 0px #bbbbbb !important;">
      <b id="welcomeText">
        <font color="#000" face1="century gothic"><span class="translateMe">Welcome Back</span> <font style="color: #2d338d;"><?php echo $userName; ?></font></font>
      </b>
      </div>

      <br>
      <div class="row">

        <?php if($roleId == 705 || $roleId == 707  || $roleId == 709){ ?>

        <?php
        if($roleId == 705 || $roleId == 707 || $roleId == 709){
            $url = 'viewLoader/gmpschedulingscheduled/lookup/';
        }
        ?>
        <div class="col-lg-4 col-6">
          <a href="<?php echo base_url().$url ?>">
            <div class="small-box bg-info">
              <div class="inner">
                <h3><?php echo @$countRegistration[0]->resultCount; ?></h3>
                <p style="font-size: larger;"><b><span class="translateMe">Scheduled Inspection</span></b></p>
                <p style="font-size: larger;"><b><?php if ($roleId == 707 || $roleId == 709){echo !empty($countScheduled1[0]->resultCount) ? $countScheduled1[0]->resultCount : "0";} else{echo !empty($countScheduled[0]->resultCount) ? $countScheduled[0]->resultCount : "0";}?></b>
                </p>
              </div>
              <div class="icon">
                <i class="fa fa-bookmark"></i>
              </div>
            </div>
          </a>
        </div>

        <?php }?>

        <?php
        $url = '';
        if($roleId == 705 || $roleId == 707 || $roleId == 709){
            $url = 'viewLoader/gmpschedulinginprocess/lookup/';
        }
        ?>
        
        <div class="col-lg-4 col-6">
          <a href="<?php echo base_url().$url ?>">
            <div class="small-box bg-primary">
              <div class="inner">
                <h3><?php echo @$countRegistration[0]->resultCount; ?></h3>
                <p style="font-size: larger;"><b><span class="translateMe">In Process Inspection</span></b></p>
                <p style="font-size: larger;"><b><?php if($roleId == 707 || $roleId == 708 || $roleId == 709){if(!empty($countInProcess1[0]->resultCount)){echo @$countInProcess1[0]->resultCount;}else{echo "0";}} else{if(!empty($countInProcess[0]->resultCount)){echo @$countInProcess[0]->resultCount; }else{ echo "0";} } ?></b></p>
              </div>
              <div class="icon">
                <i class="fa fa-heartbeat"></i>
              </div>
            </div>
          </a>
        </div>

        <?php
        if($roleId == 705 || $roleId == 707 || $roleId == 709){
            $url = 'viewLoader/gmpschedulingcompleted/lookup/';
        }
        ?>

        <div class="col-lg-4 col-6">
          <a href="<?php echo base_url().$url ?>">
            <div class="small-box bg-maroon">
              <div class="inner">
                <h3><?php echo @$countRegistration[0]->resultCount; ?></h3>
                <p style="font-size: larger;"><b><span class="translateMe">Inspection Completed</span></b></p>
                <p style="font-size: larger;"><b><?php if($roleId == 707 || $roleId == 708 || $roleId == 709){if(!empty($countInspectionCompleted1[0]->resultCount)){echo @$countInspectionCompleted1[0]->resultCount;}else{echo "0";} }else{ if(!empty($countInspectionCompleted[0]->resultCount)){echo @$countInspectionCompleted[0]->resultCount;} else{echo "0";} } ?></b></p>
              </div>
              <div class="icon">
                <i class="fa fa-check"></i>
              </div>
            </div>
          </a>
        </div>

        <?php
        if($roleId == 705 || $roleId == 707 || $roleId == 709){
            $url = 'viewLoader/gmpschedulingundercapa/lookup/';
        }
        ?>

        <div class="col-lg-4 col-6">
          <a href="<?php echo base_url().$url ?>">
            <div class="small-box bg-purple">
              <div class="inner">
                <h3><?php echo @$countRegistration[0]->resultCount; ?></h3>
                <p style="font-size: larger;"><b><span class="translateMe">Under CAPA</span></b></p>
                <p style="font-size: larger;"><b><?php if($roleId == 707 || $roleId == 708 || $roleId == 709){if(!empty($countUnderCapa1[0]->resultCount)){echo @$countUnderCapa1[0]->resultCount;}else{echo "0";}}else{ if(!empty($countUnderCapa[0]->resultCount)){echo @$countUnderCapa[0]->resultCount;} else{echo "0";} } ?></b></p>
              </div>
              <div class="icon">
                <i class="fa fa-exclamation-triangle"></i>
              </div>
            </div>
          </a>
        </div>

        <!--<div class="col-lg-4 col-6">-->
          <!--<a href="<//?php echo base_url().'viewLoader/gmpschedule/lookup/' ?>">-->
        <!--    <div class="small-box bg-danger">-->
        <!--      <div class="inner">-->
        <!--        <h3><//?php echo @$countRegistration[0]->resultCount; ?></h3>-->
        <!--        <p style="font-size: larger;"><b>Inspection Deferred and Closed</b></p>-->
        <!--        <p style="font-size: larger;"><b><//?php echo @$countInspectionClosed[0]->resultCount; ?></b></p>-->
        <!--      </div>-->
        <!--      <div class="icon">-->
        <!--        <i class="fa fa-check"></i>-->
        <!--      </div>-->
        <!--    </div>-->
          <!--</a>-->
        <!--</div>-->

        <?php
        if($roleId == 705 || $roleId == 707 || $roleId == 709){
            $url = 'viewLoader/gmpschedulingapproved/lookup/';
        }
        ?>
        
        <div class="col-lg-4 col-6">
          <a href="<?php echo base_url().$url ?>">
            <div class="small-box bg-success">
              <div class="inner">
                <h3><?php echo @$countRegistration[0]->resultCount; ?></h3>
                <?php if($roleId == 705 || $roleId == 707 || $roleId == 709){ ?>
                <p style="font-size: larger;"><b><span class="translateMe">Site Accepted</span></b></p>
                <?php } ?>
                <p style="font-size: larger;"><b><?php if($roleId == 707 || $roleId == 708 || $roleId == 709){if(!empty($countInspectionApproved1[0]->resultCount)){echo @$countInspectionApproved1[0]->resultCount;} else{echo "0";}}else{ if(!empty($countInspectionApproved[0]->resultCount)){echo @$countInspectionApproved[0]->resultCount;} else{echo "0";}} ?></b></p>
              </div>
              <div class="icon">
                <i class="fa fa-check"></i>
              </div>
            </div>
          </a>
        </div>
        
      </div>
    </div>
  </div>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!-- OPTIONAL SCRIPTS -->
<script src="<?php echo base_url(); ?>assets/plugins/chart.js/Chart.min.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="<?php echo base_url(); ?>assets/dist/js/pages/dashboard3.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="<?php echo base_url(); ?>assets/dist/js/pages/dashboard2.js"></script>

<script type="text/javascript">
  
</script>

<script>
    if (window.history && window.history.pushState) {
        window.history.pushState(null, null, window.location.href);
        window.onpopstate = function () {
            window.history.pushState(null, null, window.location.href);
        };
    }
</script>

