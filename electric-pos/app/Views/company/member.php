<!--

 * This file is part of VIEw oNLY structure.
 *
 * (c) CYBER ICT TECHNOLOGIES (PRIVATE LIMATED) <admin@cybersoftechnologies.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file at https://cybersoftechnologies.com


      _-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_
     ..          ..       ..     ..     ........     ..           ..           ....       ..  ..     ..          ..    ..     ..
    ..            ..     ..      ..     ..            ..         ..           ......      ... ..     ..           ..  ..       ..
   ..              ..   ..       ..     ........       ..   .   ..           ...  ...     ......     ..             ...         ..
    ..              .. ..        ..     ..              .. ... ..             ......      .. ...     ..             ..         ..
     ..              ...         ..     ........         ... ...               ....       ..  ..     .......       ..         ..
       -_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-


    ****    * *     *     ...     .    ..  .  .    .    .
    *****    *            .  .   ...   . . .  .   ...   .
    ****    *       *     ...   .   .  .  ..  .  .   .  ....

-->

<?php
$myAction = '';
if(explode('/', $_SERVER['REQUEST_URI'])[4] == $this->global['pageTitle'][0]->url){
  $myAction = explode('/', $_SERVER['REQUEST_URI'])[5];
}
if(explode('/', $_SERVER['REQUEST_URI'])[3] == $this->global['pageTitle'][0]->url){
  $myAction = explode('/', $_SERVER['REQUEST_URI'])[4];
}

// if($myAction == 'edit'){
//   if($this->roleId == 1){
//       $this->sessionSet(['error', 'You are not authorized to edit this record.']);
//       header("Location:".$this->base_url().'/Home/'.'VIEwoNLY/'.$this->global['pageTitle'][0]->url.'/lookup');exit();
//   }
// }

/* 
** .......................................................................................................................
**  $variable = $this->myGet('TABLE_NAME', 'SELECT STATEMENT', 'WHERE CLAUSE', 'ORDER_BY_COLUMN', 'ASC/DESC');
** .......................................................................................................................
*/

// Get Queries
$country = $this->myGet('tbls_country', 'BaseTbl.*', 'BaseTbl.status = "Active"', 'BaseTbl.id', 'desc');
// Lookup Query
$records = $this->myGet('tbl_member', 'BaseTbl.*', 'BaseTbl.status = "Active"', 'BaseTbl.id', 'desc');
// Edit / View Query
$recordsEdit = $this->myGet('tbl_member', 'BaseTbl.*', 'BaseTbl.id ="'.$this->formId.'"', 'BaseTbl.id', 'asc');
// Detail Tables Query

// Colors = ['warning', 'info', 'primary', 'success', 'danger', 'secondary', 'default', 'lime', 'maroon', 'brown', 'pink', 'purple', 'yellow', 'black'];
$stages = ['Active' => 'success', 'Inactive' => 'warning'];
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
          <h1><i <?php echo "class='".$this->global['pageTitle'][0]->icon."'"; ?>></i> <?php echo $this->global['pageTitle'][0]->friendlyName; ?></h1>
        </div>
        <div class="col-sm-6">
          <h1><a href="<?php if($myAction == 'lookup'){echo $this->base_url().'/';}else{echo $this->base_url().'/Home/VIEwoNLY/'.$this->global['pageTitle'][0]->url.'/lookup';} ?>" style="background-color: #3a7a8b !important;" <?php if($myAction == 'edit'){echo 'onclick="return confirm(\'Changes may not be saved.\')"';} ?> class="btn btn-secondary float-right"><i class="fa fa-arrow-left"></i> Go Back</a></h1>
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
            if($_SESSION['session']->error)
            {
        ?>
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h4><i class="icon fa fa-warning"></i> Alert!</h4>
            <?php echo $_SESSION['session']->error; $_SESSION['session']->error = ''; ?>                    
        </div>
        <?php } ?>
        <?php  
            if($_SESSION['session']->success)
            {
        ?>
        <div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h4><i class="icon fa fa-check"></i> Alert!</h4>
            <?php echo $_SESSION['session']->success; $_SESSION['session']->success = ''; ?>
        </div>
        <?php } ?>
        
        <div class="row">
            <div class="col-md-12">
                <?php //echo validation_errors('<div class="alert alert-danger alert-dismissable">', ' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>'); ?>
            </div>
        </div>
      </div>
    </div>
    <?php if($myAction == 'lookup'){ ?>
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card card-primary card-outline">
            <div class="card-header">
              <h3 class="card-title">Lookup</h3>
              <div class="card-tools">
                <?php if ($this->roleId == 2) { ?>
                   echo '<a href="'.$this->base_url().'/Home/VIEwoNLY/'.$this->global['pageTitle'][0]->url.'/add'.'" onclick="return confirm(\'Are you sure you want to add new record?\')" class="btn btn-primary" style="background-color: #006ee5; border-color: #006ee5;"><i class="fa fa-plus"></i> New Member</a>';
                  <?php } ?>
                
              </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive cardBodyTransaction">
              <!-- <form action="<?php echo $this->base_url().'/'.$this->global['pageTitle'][0]->url.'/lookup/' ?>" method="POST">
                <div class="input-group input-group-sm float-right" style="width: 150px;">
                  &nbsp;<input type="text" name="searchText" value="<?php echo $searchText; ?>" class="form-control float-right" placeholder="Search">

                  <div class="input-group-append">
                    <button type="submit" class="btn btn-default">
                      <i class="fas fa-search"></i>
                    </button>
                  </div>
                </div>
              </form> -->
              <table id="table" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>S.#</th>
                  <th>Member Name</th>
                  <th>CNIC</th>
                  <th>Father/Husband Type</th>
                  <th>Father/Husband Name</th>
                  <th>Country</th>
                  <th>City</th>
                  <th>Date of Birth</th>
                  <th>Contact No.</th>
                  <th>Token</th>
                  <th>Token Date</th>
                  <th>Remarks</th>
                  <th class="text-center">Status</th>
                  <th class="text-center">Action</th>
                </tr>
                </thead>
                <tbody>
                  <?php $sn=1; ?>
                  <?php
                  if(!empty($records))
                  {
                      foreach($records as $record)
                      {
                        $seenBy = explode(",",$record->seenBy);


                       
                  ?>
                  <tr <?php //if(!(in_array($_SESSION['session']->userId, $seenBy))){echo "style='background-color: #92a1ad2e !important; font-weight:bold;'";} ?>>
                    <td><?=$sn?>.</td>
                    <td><?php echo $record->memberName; ?></td>
                    <td><?php echo $record->cnicNo; ?></td>
                    <td><?php echo $record->fatherHusbandType; ?></td>
                    <td><?php echo $record->fatherHusbandName; ?></td>
                    <td><?php if(!empty($country)){foreach ($country as $detail){if($detail->id == $record->countryId){ echo $detail->countryName; }}}?></td>
                    <td><?php echo $record->city; ?></td>
                    <td><?php echo $record->dob; ?></td>
                    <td><?php echo $record->phone; ?></td>
                    <td><?php echo $record->token; ?></td>
                    <td><?php echo $record->tokenDate; ?></td>
                    <td><?php echo $record->remarks; ?></td>
                    <td class="text-center">
                      <b><h4><span <?php foreach($stages as $stage => $color){if($record->status == $stage){echo "class='badge bg-$color'";}} ?>><?php echo $record->status; ?></span></h4></b>
                    </td>
                    <td class="text-center widthMaxContent">
                      <div class="btn-group">

                        <!-- <div class="btn-group-prepend">
                          <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false" aria-label="Dropdown Toggle">
                            <span class="caret"></span>
                          </button>
                          <ul class="dropdown-menu" style="">
                            <li class="dropdown-item"><a href="#">Dropdown link</a></li>
                            <li class="dropdown-item"><a href="#">Dropdown link</a></li>
                            <li class="dropdown-divider"></li>
                            <li class="dropdown-item"><a href="#">Separated link</a></li>
                          </ul>
                        </div> -->

                        <a href="<?php echo $this->base_url().'/Home/VIEwoNLY/'.$this->global['pageTitle'][0]->url.'/view/'.$record->id; ?>" class="btn btn-success" aria-label="View"><i class="fa fa-eye"></i></a>
                        <?php if ($this->roleId == 2) { ?>
                        <a href="<?php echo $this->base_url().'/Home/VIEwoNLY/'.$this->global['pageTitle'][0]->url.'/edit/'.$record->id; ?>" class="btn btn-primary" style="background-color: #006ee5; border-color: #006ee5;" aria-label="Edit" onclick="return confirm('Are you sure you want to edit this record?')"><i class="fa fa-pencil-alt"></i></a>
                        <a href="<?php echo $this->base_url().'/Home/VIEwoNLY/'.$this->global['pageTitle'][0]->url.'/delete/'.$record->id; ?>" class="btn btn-danger" aria-label="Delete" onclick="return confirm('Are you sure you want to delete this record?')"><i class="fa fa-trash"></i></a>
                        <?php } ?>
                       
                      </div>
                    </td>
                  </tr>
                  <?php $sn++ ?>
                  <?php
                      }
                  }
                  ?>
                </tbody>
                <!-- <tfoot>
                <tr>
                  <th>Rendering engine</th>
                  <th>Browser</th>
                  <th>Platform(s)</th>
                  <th>Engine version</th>
                  <th>CSS grade</th>
                </tr>
                </tfoot> -->
              </table>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
      </div>
      <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
    <?php } ?>

    <?php if($myAction == 'edit' || $myAction == 'view'){ ?>
    <div class="container-fluid">
      <?php if($myAction == 'edit'){echo '<form id="myForm" action="'.$this->base_url().'/Home/VIEwoNLY/'.$this->global['pageTitle'][0]->url.'/submit" enctype="multipart/form-data" method="post" accept-charset="utf-8">';}?>
        <div class="card card-primary card-outline1">
          <div class="card-header">
            <h3 class="card-title"><?php if($myAction == 'edit'){echo 'Edit';} if($myAction == 'view'){echo 'View';} ?> Details</h3>

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
                <div class="form-group" >
                  <?php $label = 'Member Name'; ?>
                  <label><?php echo $label; ?></label>
                  <?php $column = 'memberName'; ?>
                    <input <?php if($myAction == 'view'){echo 'disabled';}?> type="text" id="<?php echo @$column; ?>" name="<?php echo @$column; ?>" value="<?php echo @$recordsEdit[0]->$column; ?>" class="form-control required" oninput="this.value = this.value.toUpperCase()">
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  <?php $label = 'CNIC'; ?>
                  <label><?php echo $label; ?></label>
                  <?php $column = 'cnicNo'; ?>
                    <input <?php if($myAction == 'view'){echo 'disabled';}?> type="number" id="<?php echo @$column; ?>" name="<?php echo @$column; ?>" value="<?php echo @$recordsEdit[0]->$column; ?>" class="form-control required" style="text-align: right; direction: ltr;" step="any" min="0">
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  <?php $label = 'CNIC Pic'; ?>
                  <label><?php echo $label; ?> <i>[<font style="color: #F44336;">File Format</font><font style="color: #3f51b5;"> (*.JPEG, *.PNG, *.PDF)</font><font style="color: #F44336;"> Max. File Size</font><font style="color: #3f51b5;"> 5 MB</font>]</i></label>
                  <?php $column = 'cnicPic'; ?>
                  <div class="custom-file">
                    <input type="hidden" id="<?php echo @$column; ?>Hidden" name="<?php echo @$column; ?>" value="<?php echo @$recordsEdit[0]->$column; ?>">
                    <input <?php if($myAction == 'view'){echo 'disabled';}?> type="file" id="<?php echo @$column; ?>" name="<?php echo @$column; ?>" value="<?php echo @$recordsEdit[0]->$column; ?>" class="custom-file-input <?php if(!@$recordsEdit[0]->$column){echo 'required';}?>">
                    <label class="custom-file-label" for="<?php echo @$column; ?>">Choose file</label>
                  </div>
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  <?php $label = 'CNIC Pic'; ?>
                  <?php $column = 'cnicPic'; ?>
                  <label><?php echo $label; ?> Link</label>
                  <br>
                  <a <?php if(!@$recordsEdit[0]->$column){ echo 'disabled';} ?> <?php if(@$recordsEdit[0]->$column){ echo 'href="'.$this->base_url().'/uploads/docs/'.@$recordsEdit[0]->$column.'"';} ?> target="_blank" class="btn btn-success <?php if(!@$recordsEdit[0]->$column){ echo 'disabled';} ?>"><i class="fa fa-file"></i></a>
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  <?php $label = 'Father/Husband Type'; ?>
                  <label><?php echo $label; ?></label>
                  <?php $column = 'fatherHusbandType'; ?>
                  <select <?php if($myAction == 'view'){echo 'disabled';}?> class="form-control select2" id="<?php echo @$column; ?>" name="<?php echo @$column; ?>">
                    <option value="">Select <?php echo @$label; ?></option>
                    <option value="Father" <?php if('Father' == @$recordsEdit[0]->$column){ echo 'selected'; } ?>>Father</option>
                    <option value="Husband" <?php if('Husband' == @$recordsEdit[0]->$column){ echo 'selected'; } ?>>Husband</option>
                    </select>
                </div>
              </div>

              
              <div class="col-md-6">
                <div class="form-group">
                  <?php $label = 'Father/Husband Name'; ?>
                  <label><?php echo $label; ?></label>
                  <?php $column = 'fatherHusbandName'; ?>
                    <input <?php if($myAction == 'view'){echo 'disabled';}?> type="text" id="<?php echo @$column; ?>" name="<?php echo @$column; ?>" value="<?php echo @$recordsEdit[0]->$column; ?>" class="form-control required">
                </div>
              </div>            

              <div class="col-md-6">
                <div class="form-group">
                  <?php $label = 'Country'; ?>
                  <label><?php echo $label; ?></label>
                  <?php $column = 'countryId'; ?>
                  <select <?php if($myAction == 'view'){echo 'disabled';}?> class="form-control select2 required" id="<?php echo @$column; ?>" name="<?php echo @$column; ?>">
                    <option value="">Select <?php echo @$label; ?></option>
                    <?php
                      if(!empty($country))
                      {
                        foreach ($country as $record)
                        {
                            ?>
                            <option value="<?php echo $record->id ?>" <?php if($record->id == @$recordsEdit[0]->$column){ echo 'selected'; } ?>><?php echo $record->countryName ?></option>
                            <?php
                        }
                      }
                    ?>
                  </select>
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  <?php $label = 'City'; ?>
                  <label><?php echo $label; ?></label>
                  <?php $column = 'city'; ?>
                    <input <?php if($myAction == 'view'){echo 'disabled';}?> type="text" id="<?php echo @$column; ?>" name="<?php echo @$column; ?>" value="<?php echo @$recordsEdit[0]->$column; ?>" class="form-control required">
                </div>
              </div>              

              <div class="col-md-6">
                <div class="form-group">
                  <?php $label = 'Member Pic'; ?>
                  <label><?php echo $label; ?> <i>[<font style="color: #F44336;">File Format</font><font style="color: #3f51b5;"> (*.JPEG, *.PNG, *.PDF)</font><font style="color: #F44336;"> Max. File Size</font><font style="color: #3f51b5;"> 5 MB</font>]</i></label>
                  <?php $column = 'memberPic'; ?>
                  <div class="custom-file">
                    <input type="hidden" id="<?php echo @$column; ?>Hidden" name="<?php echo @$column; ?>" value="<?php echo @$recordsEdit[0]->$column; ?>">
                    <input <?php if($myAction == 'view'){echo 'disabled';}?> type="file" id="<?php echo @$column; ?>" name="<?php echo @$column; ?>" value="<?php echo @$recordsEdit[0]->$column; ?>" class="custom-file-input <?php if(!@$recordsEdit[0]->$column){echo 'required';}?>">
                    <label class="custom-file-label" for="<?php echo @$column; ?>">Choose file</label>
                  </div>
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  <?php $label = 'Member Pic'; ?>
                  <?php $column = 'memberPic'; ?>
                  <label><?php echo $label; ?> Link</label>
                  <br>
                  <a <?php if(!@$recordsEdit[0]->$column){ echo 'disabled';} ?> <?php if(@$recordsEdit[0]->$column){ echo 'href="'.$this->base_url().'/uploads/docs/'.@$recordsEdit[0]->$column.'"';} ?> target="_blank" class="btn btn-success <?php if(!@$recordsEdit[0]->$column){ echo 'disabled';} ?>"><i class="fa fa-file"></i></a>
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  <?php $label = 'Contact No.'; ?>
                  <label><?php echo $label; ?></label>
                  <?php $column = 'phone'; ?>
                    <input <?php if($myAction == 'view'){echo 'disabled';}?> type="text" id="<?php echo @$column; ?>" name="<?php echo @$column; ?>" value="<?php echo @$recordsEdit[0]->$column; ?>" class="form-control required">
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  <?php $label = 'Contact No. 2'; ?>
                  <label><?php echo $label; ?></label>
                  <?php $column = 'phone2'; ?>
                    <input <?php if($myAction == 'view'){echo 'disabled';}?> type="text" id="<?php echo @$column; ?>" name="<?php echo @$column; ?>" value="<?php echo @$recordsEdit[0]->$column; ?>" class="form-control">
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  <?php $label = 'Contact No. 3'; ?>
                  <label><?php echo $label; ?></label>
                  <?php $column = 'phone3'; ?>
                    <input <?php if($myAction == 'view'){echo 'disabled';}?> type="text" id="<?php echo @$column; ?>" name="<?php echo @$column; ?>" value="<?php echo @$recordsEdit[0]->$column; ?>" class="form-control">
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  <?php $label = 'Date of Birth'; ?>
                  <label><?php echo $label; ?></label>
                  <?php $column = 'dob'; ?>
                    <input <?php if($myAction == 'view'){echo 'disabled';}?> type="date" id="<?php echo @$column; ?>" name="<?php echo @$column; ?>" value="<?php echo @$recordsEdit[0]->$column; ?>" class="form-control required">
                </div>
              </div>             

              <div class="col-md-6">
                <div class="form-group">
                  <?php $label = 'Email'; ?>
                  <label><?php echo $label; ?></label>
                  <?php $column = 'email'; ?>
                    <input <?php if($myAction == 'view'){echo 'disabled';}?> type="text" id="<?php echo @$column; ?>" name="<?php echo @$column; ?>" value="<?php echo @$recordsEdit[0]->$column; ?>" class="form-control">
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  <?php $label = 'Token'; ?>
                  <label><?php echo $label; ?></label>
                  <?php $column = 'token'; ?>
                    <input <?php if($myAction == 'view'){echo 'disabled';}?> type="number" id="<?php echo @$column; ?>" name="<?php echo @$column; ?>" value="<?php echo @$recordsEdit[0]->$column; ?>" class="form-control required">
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  <?php $label = 'Token Date'; ?>
                  <label><?php echo $label; ?></label>
                  <?php $column = 'tokenDate'; ?>
                    <input <?php if($myAction == 'view'){echo 'disabled';}?> type="date" id="<?php echo @$column; ?>" name="<?php echo @$column; ?>" value="<?php echo @$recordsEdit[0]->$column; ?>" class="form-control required">
                </div>
              </div>

              <div class="col-md-12">
                <div class="form-group">
                  <?php $label = 'Current Address'; ?>
                  <label><?php echo $label; ?></label>
                  <?php $column = 'currentAddress'; ?>
                  <textarea <?php if($myAction == 'view'){echo 'disabled';}?> id="<?php echo @$column; ?>" name="<?php echo @$column; ?>" class="form-control required" rows="3"><?php echo @$recordsEdit[0]->$column;?></textarea>
                </div>
              </div>

              <div class="col-md-12">
                <div class="form-group">
                  <?php $label = 'Permanent Address'; ?>
                  <label><?php echo $label; ?></label>
                  <?php $column = 'permanentAddress'; ?>
                  <textarea <?php if($myAction == 'view'){echo 'disabled';}?> id="<?php echo @$column; ?>" name="<?php echo @$column; ?>" class="form-control" rows="3"><?php echo @$recordsEdit[0]->$column; ?></textarea>
                </div>
              </div>               

              <div class="col-md-12">
                <div class="form-group">
                  <?php $label = 'Remarks'; ?>
                  <label><?php echo $label; ?></label>
                  <?php $column = 'remarks'; ?>
                  <textarea <?php if($myAction == 'view'){echo 'disabled';}?> id="<?php echo @$column; ?>" name="<?php echo @$column; ?>" class="form-control" rows="3"><?php echo @$recordsEdit[0]->$column; ?></textarea>
                </div>
              </div>



              <div class="col-md-6">
                <div class="form-group">
                  <?php $label = 'Status'; ?>
                  <label><?php echo $label; ?></label>
                  <?php $column = 'status'; ?>
                  <select <?php if($myAction == 'view'){echo 'disabled';}?> class="form-control select2" id="<?php echo @$column; ?>" name="<?php echo @$column; ?>">
                    <option value="Active" <?php if('Active' == @$recordsEdit[0]->$column){ echo 'selected'; } ?>>Active</option>
                    <option value="Inactive" <?php if('Inactive' == @$recordsEdit[0]->$column){ echo 'selected'; } ?>>Inactive</option>
                  </select>
                </div>
              </div>

            </div>
          </div>
          <!-- /.card-body -->
          <div class="card-footer">
            <?php if($myAction == 'edit'){echo '<input type="submit" onclick="return confirm(\'Are you sure you want to submit this record?\')" class="btn btn-primary" style="background-color: #006ee5; border-color: #006ee5;" value="Submit">';}?>
          </div>
        <!-- /.card -->
        </div>
        <!-- /.row -->
      <?php if($myAction == 'edit'){echo '</form>';}?>
    </div>
    <!-- /.container-fluid -->
    <?php } ?>
  </section>
  <!-- /.content -->
</div>

<script type="text/javascript">
  loadMyTable('table', true, 15);
</script>