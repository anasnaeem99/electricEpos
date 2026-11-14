<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>RBI | Registration Page</title>
  <link id="favicon" rel="icon" href="<?php echo base_url(); ?>uploads/company/RBI/logo/logo-mini.png"/>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/adminlte.min.css">
  <!-- Select2 -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
  <style>
    body.register-page {
      overflow-x: hidden;
    }

    .register-wrapper {
      width: 60%;
      max-width: 1100px;
      margin: 0 auto;
    }

    .register-title-row {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 12px;
      width: 100%;
    }

    .register-logo-item {
      flex: 1 1 0;
      display: flex;
      align-items: center;
    }

    .register-logo-item.left {
      justify-content: flex-start;
    }

    .register-logo-item.right {
      justify-content: flex-end;
    }

    .register-logo-item img {
      width: 118px;
      max-width: 100%;
      height: 70px;
      object-fit: contain;
      border-radius: 10px;
      background: #fff;
      padding: 5px;
      box-shadow: 0 1px 8px rgba(0, 0, 0, 0.1);
    }

    .register-title-main {
      flex: 0 0 auto;
      margin: 0;
      font-size: 2rem;
      line-height: 1.2;
    }

    @media (max-width: 768px) {
      .register-wrapper {
        width: 94%;
      }

      .register-title-row {
        gap: 10px;
      }

      .register-logo-item img {
        width: 76px;
        height: 44px;
        padding: 4px;
      }

      .register-title-main {
        font-size: 1.35rem;
      }
    }

    .footer-link {
    color: white;
    text-decoration: none;
    transition: color 0.3s ease;
    }

    .footer-link:hover {
        color: #3F51B5; /* Blue color */
    }

    .product-type-buttons {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      padding: 4px;
      border-radius: 8px;
    }

    .product-type-btn {
      border-radius: 8px;
      padding: 10px 18px;
      font-size: 14px;
      font-weight: 500;
      white-space: normal;
      text-align: center;
      min-width: 130px;
      transition: all 0.2s ease;
      border: 2px solid #006ee5;
      color: #006ee5;
      background-color: #fff;
    }

    .product-type-btn:hover {
      background-color: #e8f1fd;
      color: #0056b3;
    }

    .product-type-btn.active {
      background-color: #006ee5;
      color: #fff;
      border-color: #006ee5;
      box-shadow: 0 2px 8px rgba(0, 110, 229, 0.35);
    }

    .product-type-buttons.is-invalid {
      outline: 2px solid #dc3545;
      outline-offset: 2px;
    }

    .select2-container .select2-selection.is-invalid-select2,
    .select2-selection.is-invalid-select2 {
      border-color: #dc3545 !important;
    }
  </style>
</head>
<body class="hold-transition register-page">
<div class="wrapper register-wrapper">
<div class="register-box1" style="//box-shadow: 0px 2px 30px #f47635;//border: 8px solid #1c395d;border-radius:2%;//background-color: #344063;background-color: #2f516f; box-shadow: 0px 0px 20px 0px #989898; margin: auto;">
<div class="card card-outline card-primary" style="border-top: 3px solid #2f516f; ">
    <div class="card-header text-center">
      <div class="register-title-row">
        <div class="register-logo-item left">
          <img src="<?php echo base_url(); ?>uploads/company/RBI/logo/sahpra.png" alt="sahpra Logo">
        </div>
        <a href="<?php echo base_url(); ?>" class="h1 register-title-main"><b><span class="translateMe">RBI Tool</span></b></a>
        <div class="register-logo-item right">
          <img src="<?php echo base_url(); ?>uploads/company/RBI/logo/logo.png" alt="Right Logo">
        </div>
      </div>
    </div>
    <div class="card-body">
      <p class="login-box-msg"><span class="translateMe" style="font-size:20px;">Company Registration Form</span></p>
    
    <form id="myForm" action="<?php echo base_url(); ?>registerMe" method="post">
        <div class="card-body cardBodyTransaction">
            <div class="row">
              
              <div class="col-md-12">
                <div class="card card-secondary border-secondary card-tabs" style="border: 3px solid;">
                  <div class="card-header p-0 pt-1">
                    <ul class="nav nav-tabs">
                      <li class="nav-item">
                        <a class="nav-link active" data-toggle="pill" href="#tab1">Facility Information</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" data-toggle="pill" href="#tab2">Product Scope</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" data-toggle="pill" href="#tab3">Additional Modules</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" data-toggle="pill" href="#tab4">Contact Information</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" data-toggle="pill" href="#tab5">Output Preview</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" data-toggle="pill" href="#tab6">Register</a>
                      </li>
                    </ul>
                  </div>
                  <div class="card-body">
                    <div class="tab-content">

                      <div class="tab-pane fade show active" id="tab1">
                        <div class="row" style="float: left; width: 100%;">

                          <div class="col-md-6">
                            <div class="form-group">
                              <?php $label = 'Manufacturer Name'; ?>
                              <label><?php echo $label; ?></label>
                              <?php $column = 'companyName'; ?>
                              <input type="text" id="<?php echo @$column; ?>" name="<?php echo @$column; ?>" value="<?php echo @$recordsEdit[0]->$column; ?>" class="form-control required">
                            </div>
                          </div>

                          <div class="col-md-6">
                            <div class="form-group">
                              <?php $label = 'Physical Address'; ?>
                              <label><?php echo $label; ?></label>
                              <?php $column = 'siteAddress'; ?>
                              <input type="text" id="<?php echo @$column; ?>" name="<?php echo @$column; ?>" value="<?php echo @$recordsEdit[0]->$column; ?>" class="form-control">
                            </div>
                          </div>

                          <div class="col-md-6">
                            <div class="form-group">
                              <?php $label = 'Longitude'; ?>
                              <label><?php echo $label; ?></label>
                              <?php $column = 'longitude'; ?>
                              <input type="text" id="<?php echo @$column; ?>" name="<?php echo @$column; ?>" value="<?php echo @$recordsEdit[0]->$column; ?>" class="form-control required">
                            </div>
                          </div>

                          <div class="col-md-6">
                            <div class="form-group">
                              <?php $label = 'Latitude'; ?>
                              <label><?php echo $label; ?></label>
                              <?php $column = 'latitude'; ?>
                              <input type="text" id="<?php echo @$column; ?>" name="<?php echo @$column; ?>" value="<?php echo @$recordsEdit[0]->$column; ?>" class="form-control required">
                            </div>
                          </div>

                          <div class="col-md-6">
                            <div class="form-group">
                              <?php $label = 'City/Town'; ?>
                              <label><?php echo $label; ?></label>
                              <?php $column = 'cityName'; ?>
                                <input  type="text" id="<?php echo @$column; ?>" name="<?php echo @$column; ?>" value="<?php echo @$recordsEdit[0]->$column; ?>" class="form-control required">
                            </div>
                          </div>

                          <div class="col-md-6">
                            <div class="form-group">
                              <?php $label = 'State/Province/District'; ?>
                              <label><?php echo $label; ?></label>
                              <?php $column = 'stateName'; ?>
                                <input  type="text" id="<?php echo @$column; ?>" name="<?php echo @$column; ?>" value="<?php echo @$recordsEdit[0]->$column; ?>" class="form-control required">
                            </div>
                          </div>

                          <div class="col-md-6">
                            <div class="form-group">
                              <?php $label = 'Country'; ?>
                              <label><?php echo $label; ?></label>
                              <?php $column = 'countryId'; ?>
                              <select  class="form-control select2 required" id="<?php echo @$column; ?>" name="<?php echo @$column; ?>">
                                <option value="">Select <?php echo @$label; ?></option>
                                <?php
                                  if(!empty($country))
                                  {
                                    foreach ($country as $record)
                                    {
                                        ?>
                                        <option value="<?php echo $record->id ?>" data-code="<?php echo $record->countryCode; ?>"><?php echo $record->countryName ?></option>
                                        <?php
                                    }
                                  }
                                ?>
                              </select>
                            </div>
                          </div>

                          <div class="col-md-6">
                            <div class="form-group">
                              <?php $label = 'Site Role/ Responsibility'; ?>
                              <label><?php echo $label; ?></label>
                              <?php $column = 'responsibility'; ?>
                              <select class="form-control select2" id="<?php echo @$column; ?>" name="<?php echo @$column; ?>">
                                <option value="" <?php if('' == @$recordsEdit[0]->$column){ echo 'selected'; } ?>>Select <?php echo @$label; ?></option>
                                <option value="Fabrication" <?php if('Fabrication' == @$recordsEdit[0]->$column){ echo 'selected'; } ?>>Fabrication</option>
                                <option value="Packaging" <?php if('Packaging' == @$recordsEdit[0]->$column){ echo 'selected'; } ?>>Packaging</option>
                                <option value="Labeling" <?php if('Labeling' == @$recordsEdit[0]->$column){ echo 'selected'; } ?>>Labeling</option>
                                <option value="Testing" <?php if('Testing' == @$recordsEdit[0]->$column){ echo 'selected'; } ?>>Testing</option>
                                <option value="All Activities" <?php if('All Activities' == @$recordsEdit[0]->$column){ echo 'selected'; } ?>>All Activities</option>
                              </select>
                            </div>
                          </div>

                          <div class="col-md-6">
                            <div class="form-group">
                              <?php $label = 'Company Category'; ?>
                              <label><?php echo $label; ?></label>
                              <?php $column = 'medicineBy'; ?>
                              <select class="form-control select2 required" id="<?php echo @$column; ?>" name="<?php echo @$column; ?>">
                                <option value="" <?php if('' == @$recordsEdit[0]->$column){ echo 'selected'; } ?>>Select <?php echo @$label; ?></option>
                                <option value="FPP Human" <?php if('FPP Human' == @$recordsEdit[0]->$column){ echo 'selected'; } ?>>FPP Human</option>
                                <option value="API Human" <?php if('API Human' == @$recordsEdit[0]->$column){ echo 'selected'; } ?>>API Human</option>
                                <option value="Veterinary" <?php if('Veterinary' == @$recordsEdit[0]->$column){ echo 'selected'; } ?>>Veterinary</option>
                              </select>
                            </div>
                          </div>

                        </div>
                        <div style="clear: both;"></div>
                        <div class="col-md-12 text-right mt-3">
                          <button type="button" class="btn btn-primary" onclick="nextStep()">Next <i class="fas fa-arrow-right"></i></button>
                        </div>
                      </div>

                      <div class="tab-pane fade" id="tab2">
                        <div class="row" style="float: left; width: 100%;">

                          <div class="col-md-6">
                            <div class="form-group">
                              <?php $label = 'Product Scope Model'; ?>
                              <label><?php echo $label; ?></label>
                              <?php $column = 'productScopeModel'; ?>
                              <select class="form-control select2 required" id="<?php echo @$column; ?>" name="<?php echo @$column; ?>">
                                <option value="" <?php if('' == @$recordsEdit[0]->$column){ echo 'selected'; } ?>>Select <?php echo @$label; ?></option>
                                <option value="Single Product" <?php if('Single Product' == @$recordsEdit[0]->$column){ echo 'selected'; } ?>>Single Product</option>
                                <option value="Multi-Product" <?php if('Multi-Product' == @$recordsEdit[0]->$column){ echo 'selected'; } ?>>Multi-Product</option>
                              </select>
                            </div>
                          </div>

                          <div class="col-md-12">
                            <div class="form-group">
                              <?php
                                $label = 'Product Type';
                                $column = 'productTypeId';
                                $selectedProductTypes = [];
                                if (!empty($recordsEdit[0]->$column)) {
                                  $selectedProductTypes = array_filter(array_map('trim', explode(',', $recordsEdit[0]->$column)));
                                }
                              ?>
                              <label id="productTypeLabel"><i class="fa fa-asterisk text-danger"></i> <span class="translateMe"><?php echo $label; ?></span></label>
                              <div id="productTypeButtons" class="product-type-buttons" data-mode="multi">
                                <?php if (!empty($productType)) { ?>
                                  <?php foreach ($productType as $record) { ?>
                                    <button type="button"
                                      class="btn product-type-btn<?php echo in_array((string)$record->id, $selectedProductTypes, true) ? ' active' : ''; ?>"
                                      data-id="<?php echo $record->id; ?>">
                                      <span class="translateMe"><?php echo $record->productType; ?></span>
                                    </button>
                                  <?php } ?>
                                <?php } ?>
                              </div>
                              <small id="productTypeHint" class="text-muted d-block mt-2"><span class="translateMe">Click to select one or more product types.</span></small>
                              <textarea style="display: none;" id="productTypeHidden" name="productTypeId" class="required"><?php echo !empty($recordsEdit[0]->$column) ? $recordsEdit[0]->$column : ''; ?></textarea>
                            </div>
                          </div>

                          <div class="col-md-6" style="display: none;">
                            <div class="form-group">
                              <?php $label = 'Module'; ?>
                              <label><span class="translateMe"><?php echo $label; ?></span></label>
                              <?php $column = 'module'; ?>
                              <input type="text" id="<?php echo @$column; ?>" name="<?php echo @$column; ?>" value="GMP" class="form-control">
                            </div>
                          </div>

                        </div>
                        <div class="col-md-12 d-flex justify-content-between mt-3">
                          <button type="button" class="btn btn-secondary" onclick="prevStep()"><i class="fas fa-arrow-left"></i> Back</button>
                          <button type="button" class="btn btn-primary" onclick="nextStep()">Next <i class="fas fa-arrow-right"></i></button>
                        </div>
                      </div>

                      <div class="tab-pane fade" id="tab3">
                        <div class="row" style="float: left; width: 100%;">

                          <div class="col-md-6">
                            <div class="form-group">
                              <?php $label = 'GxP Computerized System/ e-Records?'; ?>
                              <label><?php echo $label; ?></label>
                              <?php $column = 'computerizedSystem'; ?>
                              <select class="form-control select2" id="<?php echo @$column; ?>" name="<?php echo @$column; ?>">
                                <option value="" <?php if('' == @$recordsEdit[0]->$column){ echo 'selected'; } ?>>Select <?php echo @$label; ?></option>
                                <option value="No" <?php if('No' == @$recordsEdit[0]->$column){ echo 'selected'; } ?>>No</option>
                                <option value="Yes" <?php if('Yes' == @$recordsEdit[0]->$column){ echo 'selected'; } ?>>Yes</option>
                              </select>
                            </div>
                          </div>

                          <div class="col-md-6">
                            <div class="form-group">
                              <?php $label = 'Sterile or Aseptic Process'; ?>
                              <label><?php echo $label; ?></label>
                              <?php $column = 'sterileAsepticProcess'; ?>
                              <select class="form-control select2" id="<?php echo @$column; ?>" name="<?php echo @$column; ?>">
                                <option value="" <?php if('' == @$recordsEdit[0]->$column){ echo 'selected'; } ?>>Select <?php echo @$label; ?></option>
                                <option value="No" <?php if('No' == @$recordsEdit[0]->$column){ echo 'selected'; } ?>>No</option>
                                <option value="Yes" <?php if('Yes' == @$recordsEdit[0]->$column){ echo 'selected'; } ?>>Yes</option> 
                              </select>
                            </div>
                          </div>

                          <div class="col-md-6">
                            <div class="form-group">
                              <?php $label = 'Ionising Radiation Used?'; ?>
                              <label><?php echo $label; ?></label>
                              <?php $column = 'ionisingRadiation'; ?>
                              <select class="form-control select2" id="<?php echo @$column; ?>" name="<?php echo @$column; ?>">
                                <option value="" <?php if('' == @$recordsEdit[0]->$column){ echo 'selected'; } ?>>Select <?php echo @$label; ?></option>
                                <option value="No" <?php if('No' == @$recordsEdit[0]->$column){ echo 'selected'; } ?>>No</option>
                                <option value="Yes" <?php if('Yes' == @$recordsEdit[0]->$column){ echo 'selected'; } ?>>Yes</option> 
                              </select>
                            </div>
                          </div>

                          <div class="col-md-6">
                            <div class="form-group">
                              <?php $label = 'RTRT or Parametric Release?'; ?>
                              <label><?php echo $label; ?></label>
                              <?php $column = 'rtrtParametricRelease'; ?>
                              <select class="form-control select2" id="<?php echo @$column; ?>" name="<?php echo @$column; ?>">
                                <option value="" <?php if('' == @$recordsEdit[0]->$column){ echo 'selected'; } ?>>Select <?php echo @$label; ?></option>
                                <option value="No" <?php if('No' == @$recordsEdit[0]->$column){ echo 'selected'; } ?>>No</option>
                                <option value="Yes" <?php if('Yes' == @$recordsEdit[0]->$column){ echo 'selected'; } ?>>Yes</option> 
                              </select>
                            </div>
                          </div>

                        </div>
                        <div class="col-md-12 d-flex justify-content-between mt-3">
                          <button type="button" class="btn btn-secondary" onclick="prevStep()"><i class="fas fa-arrow-left"></i> Back</button>
                          <button type="button" class="btn btn-primary" onclick="nextStep()">Next <i class="fas fa-arrow-right"></i></button>
                        </div>
                      </div> 

                      <div class="tab-pane fade" id="tab4">
                        <div class="row" style="float: left; width: 100%;">

                          <div class="col-12">
                            <div class="card">
                              <div class="card-header" style="background-color: #a5a9ad;">
                                <h3 class="card-title"><span class="translateMe">Primary Contact Person</span></h3>
                                <div class="card-tools">
                                </div>
                              </div>
                              <!-- /.card-header -->
                              <div class="card-body table-responsive cardBodyTransaction1 parentDiv">
                                <div class="row">

                                  <div class="col-md-6">
                                    <div class="form-group">
                                      <?php $label = 'Contact Person Name'; ?>
                                      <label><?php echo $label; ?></label>
                                      <?php $column = 'contactPersonName'; ?>
                                      <input type="text" id="<?php echo @$column; ?>" name="<?php echo @$column; ?>" value="<?php echo @$recordsEdit[0]->$column; ?>" class="form-control required">
                                    </div>
                                  </div>

                                  <div class="col-md-6">
                                    <div class="form-group">
                                      <?php $label = 'Contact Person Dsignation'; ?>
                                      <label><?php echo $label; ?></label>
                                      <?php $column = 'contactPersonDesignation'; ?>
                                      <input type="text" id="<?php echo @$column; ?>" name="<?php echo @$column; ?>" value="<?php echo @$recordsEdit[0]->$column; ?>" class="form-control required">
                                    </div>
                                  </div>
                                  
                                  <div class="col-md-6">
                                    <div class="form-group">
                                      <?php $label = 'Contact Person Phone'; ?>
                                      <label><?php echo $label; ?></label>
                                      <?php $column = 'contactPersonPhone'; ?>
                                      <div class="input-group">
                                        <div class="input-group-prepend">
                                          <span class="input-group-text" id="countryCode" style="min-width:55px; justify-content:center;">+</span>
                                        </div>
                                        <input type="hidden" name="countryCode" id="countryCodeInput">
                                        <input type="text" id="<?php echo @$column; ?>" name="<?php echo @$column; ?>" value="" class="form-control required">
                                      </div>
                                    </div>
                                  </div>

                                  <div class="col-md-6">
                                    <div class="form-group">
                                      <?php $label = 'Email <b style="color : red;">(will be used to login)</b>'; ?>
                                      <label><?php echo $label; ?></label>
                                      <?php $column = 'email'; ?>
                                      <input type="email" id="<?php echo @$column; ?>" name="<?php echo @$column; ?>" value="" class="form-control required">
                                      <small id="emailError" style="color: red;"></small>
                                    </div>
                                  </div>

                                </div>
                              </div>
                              
                            </div>
                          </div>

                          <div class="col-12">
                            <div class="card">
                              <div class="card-header" style="background-color: #a5a9ad;">
                                <h3 class="card-title"><span class="translateMe">Alternate Contact Person</span></h3>
                                <div class="card-tools">
                                </div>
                              </div>
                              <!-- /.card-header -->
                              <div class="card-body table-responsive cardBodyTransaction1">
                                <div class="row">

                                  <div class="col-md-6">
                                    <div class="form-group">
                                      <?php $label = 'Contact Person Name'; ?>
                                      <label><?php echo $label; ?></label>
                                      <?php $column = 'alternateContactPersonName'; ?>
                                      <input type="text" id="<?php echo @$column; ?>" name="<?php echo @$column; ?>" value="<?php echo @$recordsEdit[0]->$column; ?>" class="form-control">
                                    </div>
                                  </div>

                                  <div class="col-md-6">
                                    <div class="form-group">
                                      <?php $label = 'Contact Person Dsignation'; ?>
                                      <label><?php echo $label; ?></label>
                                      <?php $column = 'alternateContactPersonDesignation'; ?>
                                      <input type="text" id="<?php echo @$column; ?>" name="<?php echo @$column; ?>" value="<?php echo @$recordsEdit[0]->$column; ?>" class="form-control">
                                    </div>
                                  </div>
                                  
                                  <div class="col-md-6">
                                    <div class="form-group">
                                      <?php $label = 'Contact Person Phone'; ?>
                                      <label><?php echo $label; ?></label>
                                      <?php $column = 'alternateContactPersonPhone'; ?>
                                      <div class="input-group">
                                        <div class="input-group-prepend">
                                          <span class="input-group-text" id="alternatecountryCode" style="min-width:55px; justify-content:center;">+</span>
                                        </div>
                                        <input type="hidden" name="alternatecountryCode" id="alternatecountryCodeInput">
                                        <input type="text" id="<?php echo @$column; ?>" name="<?php echo @$column; ?>" value="" class="form-control">
                                      </div>
                                    </div>
                                  </div>

                                  <div class="col-md-6">
                                    <div class="form-group">
                                      <?php $label = 'Email'; ?>
                                      <label><?php echo $label; ?></label>
                                      <?php $column = 'alternateEmail'; ?>
                                      <input type="email" id="<?php echo @$column; ?>" name="<?php echo @$column; ?>" value="" class="form-control">
                                    </div>
                                  </div>

                                </div>
                              </div>
                              
                            </div>
                          </div>        

                          

                        </div>
                        <div class="col-md-12 d-flex justify-content-between mt-3">
                          <button type="button" class="btn btn-secondary" onclick="prevStep()"><i class="fas fa-arrow-left"></i> Back</button>
                          <button type="button" class="btn btn-primary" onclick="nextStep()">Next <i class="fas fa-arrow-right"></i></button>
                        </div>
                      </div>

                      <div class="tab-pane fade" id="tab5">
                        <div class="row" style="float: left; width: 100%;">
                          <div class="col-md-12">
                            <h5 class="mb-3"><span class="translateMe">GMP Systems</span></h5>
                            <div id="outputPreviewLoading" class="text-center py-3" style="display: none;">
                              <i class="fas fa-spinner fa-spin fa-2x text-primary"></i>
                              <p class="mt-2 mb-0"><span class="translateMe">Loading preview...</span></p>
                            </div>
                            <div id="outputPreviewContent">
                              <p class="text-muted"><span class="translateMe">Complete the previous tabs and open this preview to see applicable systems.</span></p>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-12 d-flex justify-content-between mt-3">
                          <button type="button" class="btn btn-secondary" onclick="prevStep()"><i class="fas fa-arrow-left"></i> Back</button>
                          <button type="button" class="btn btn-primary" onclick="nextStep()">Next <i class="fas fa-arrow-right"></i></button>
                        </div>
                      </div>    

                      <div class="tab-pane fade" id="tab6">
                        <div class="row" style="float: left; width: 100%;">

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
                            <table width="400" border="0" align="center" cellpadding="5" cellspacing="1" class="table" style="width: auto;">
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

                            <div class="col-8">
                              <div class="icheck-primary">
                                <input type="checkbox" id="confirmTerms">
                                <label for="confirmTerms">
                                 <span class="translateMe">I agree to the</span> <a href="#" data-toggle="modal" data-target="#termsofuse" data-backdrop="static"><span class="translateMe">terms</span></a>
                                </label>
                              </div>
                            </div>
                            <!-- /.col -->
                            <div class="col-4">
                              <button type="submit" id="submitButton" class="btn btn-primary btn-block" style="background-color: #006ee5 !important; border-color: #006ee5 !important;"><span class="translateMe">Register</span></button>
                            </div>

                        
                        </div>
                        <div style="clear: both;"></div>
                        <div class="col-4">
                          <button type="button" class="btn btn-secondary btn-block" onclick="prevStep()">Back</button>
                        </div>
                      </div>      

                    </div>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </form>  

      <a href="<?php echo base_url(); ?>" class="text-center"><span class="translateMe">I already have an account</span></a>
    </div>
    <!-- /.form-box -->
  </div><!-- /.card -->
  <!-- <br> -->
  <div class="col-md-12" style="color: white; text-align: center; font-size: 18px;">
    Copyright &copy; <?php echo date('Y'); ?> The U.S. Pharmacopeial Convention.
    <span class="translateMe">All rights reserved</span> |
    
    <a href="#" class="footer-link" data-toggle="modal" data-target="#termsofuse" data-backdrop="static">
        <span class="translateMe">Terms of use</span>
    </a> |
    
    <a href="#" class="footer-link" data-toggle="modal" data-target="#privacypolicy" data-backdrop="static">
        <span class="translateMe">Privacy Policy</span>
    </a>
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
            <?php //echo validation_errors('<div class="alert alert-danger alert-dismissable">', ' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>'); ?>
        </div>
    </div>
  </div>
</div>
<!-- /.register-box -->
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

                <span class="translateMe">This Application, The Risk-based Inspection (RBI) Tool, including the platform, all its features and content (the "RBI") is a service made available by The United States Pharmacopeial Convention ("USP") and the Promoting the Quality of Medicines Plus (PQM+) Program. The Application, its content, information, and services provided on or through the Application (“Content”) may be used solely under the following terms and conditions ("Terms of Use").</span>
                <br><br>

                <b><span class="translateMe">Limitations on Use.</span></b>
                <br><br>

                <span class="translateMe">1. Application Limited License. As a user of this Application you are granted a nonexclusive, nontransferable, revocable, limited license to access and use this Application and Content in accordance with these Terms of Use.</span>
                <br><br>

                <span class="translateMe">2. Copyright and Use. The Website and its Contents are protected by copyright pursuant to U.S. and international copyright laws. Except as expressly provided in these Terms of Use, nothing contained herein shall be construed as conferring any license or right, by implication, estoppel or otherwise, under copyright or other intellectual property rights. You agree that the Content and Web site are protected by copyrights, trademarks, service marks, patents or other proprietary rights and laws</span>
                <br><br>

                <span class="translateMe">The Application and content are for your personal use only and not for commercial exploitation. You may not decompile, reverse engineer, disassemble, rent, lease, loan, sell, sublicense, or create derivative works from the Application and Content. You may not use any network monitoring or discovery software to determine the site architecture, or extract information about usage, individual identities or users. You may not use any robot, spider, other automatic software or device, or manual process to monitor or copy our Website or the Content without USP's prior written permission. You may not copy, modify, reproduce, republish, distribute, display, or transmit for commercial, non-profit or public purposes all or any portion of this Application, except to the extent permitted below. You may not use or otherwise export or re-export this Application or any portion thereof, or the Content in violation of the export control laws and regulations of the United States of America. Any unauthorized use of this Application or its Content is prohibited.</span>
                <br><br>

                <span class="translateMe">3. Attribution and citation. Information obtained from this Application and content may be reproduced and modified if credit is given to the US Pharmacopeial Convention (USP) and the Promoting the Quality of Medicines plus (PQM+) Program, Rockville, Maryland, United States.</span>
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
            <span class="translateMe">
            USP respects the privacy of customers and visitors accessing this website and other websites maintained by USP, and takes seriously its responsibilities regarding the privacy and security of customer information and visitor information. USP reserves the right to update this Privacy Policy from time to time. Changes to the Privacy Policy shall be announced via this website.
            </span>
            <br><br>

            <span class="translateMe">
            USP defines “personal data” to mean any information relating to an identified or identifiable natural person; an “identifiable natural person” is one who can be identified, directly or indirectly, in particular by reference to an identification number or to one or more factors specific to his physical, physiological, mental, economic, cultural or social identity. USP may collect personal data including, but not limited to, your name, title, employer, address, telephone number, and email address. USP collects personal data from you through the use of registration forms, direct personal contact with you, and each time you email USP your details.
            </span>
            <br><br>

            <span class="translateMe">
            USP uses your personal data only when and where it is necessary to do so because it is relevant to USP's dealings with you or where USP has your consent to do so. This includes, but is not limited to, invoicing you for products and services you buy from USP, customer service, and network management. Purposes for which USP may use your personal data include direct marketing of USP products and services and customer surveys. USP will not disclose or share your personal data with any other party without your express consent, unless this is necessary to provide the services or products which you requested when providing us with your personal data, or as required by law. Aggregate data which has been de-identified may be used by USP and by third parties to track usage of USP's websites and applications. USP will not sell, trade or rent your personal information to third parties.
            </span>
            <br><br>

            <span class="translateMe">
            You understand and agree that perfect security does not exist anywhere, including on the Internet. When you send credit card information to USP over the Internet, USP encrypts this information between your computer and our server. Other information you send to USP, including email messages, will not be encrypted unless USP advises you otherwise. USP may use cookies on its websites. USP has technical and organizational security measures intended to prevent unauthorized or unlawful disclosure or access to, or accidental or unlawful loss of or destruction, or alteration or unauthorized disclosure of or access to, or other damage to your personal data. These measures are intended to ensure an appropriate level of security in relation to the risks inherent in the processing and the nature of the personal data to be protected.
            </span>
            <br><br>

            <span class="translateMe">
            USP only keeps your information for as long as we are either required to by law, or as is relevant for the purposes for which it was collected. You may contact USP at any time if you would like to correct, update, supplement or delete personal data. You may also contact USP at any time to opt-out of receiving communications from USP at the point where USP requests information about you. You may send email to <span class="text-primary">privacy@usp.org</span>, or you may send mail to the following postal address:
            </span>
            <br><br>

            Privacy, USP
            <br>
            12601 Twinbrook Parkway
            <br>
            Rockville, MD 20852
            <br><br>

            <span class="translateMe">If you have any questions about this privacy statement, please email USP at</span> <span class="text-primary">privacy@usp.org</span>
            <br><br>

            <span class="translateMe">USP Cookies Policy</span>
            <br><br>

            <span class="translateMe">
            To enhance your experience on USP’s websites, many of USP’s web pages use cookies. Cookies are small text files that are placed in your computer’s browser to store your preferences. Cookies, by themselves, do not provide your email address or other personal information unless you choose to provide this information to USP—for example, by registering for e-newsletters or completing a web form. Once you choose to provide personal information to a web page, this information may be linked to the data stored in the cookie. A cookie is like an identification card. It is unique to your computer and can be read only by the server that gave it to you.
            </span>
            <br><br>

            <span class="translateMe">
            Analytics cookies store information about what pages people visit, how long they are on the site, how they got there, and what they click on. Information supplied by analytics cookies helps USP analyze the profile of visitors and provide them with a better experience. Analytics cookies do not collect or store personal information (for example, names or addresses), so this information cannot be used to identify individuals. USP does not allow analytics companies to use or share its analytics data.
            </span>
            <br><br>

            <span class="translateMe">
            Some areas of USP websites allow users to log in and retrieve personal information and account details. 
            </span>
            <br>
            <span class="translateMe">
            Some of these areas use a cookie in order to remember account information.
            </span>
            <br><br>

            <span class="translateMe">
            You may also find embedded videos from external sites such as YouTube or Vimeo. Cookies that come from other sites like YouTube but work on USP.org are third-party cookies and are governed by such third-party’s privacy policy. Visitors may learn about third-party cookies policies on the third-party website’s privacy pages.
            </span>
            <br><br>

            <span class="translateMe">
            If you want to control which cookies you accept, configure your browser to accept all cookies or to alert you every time a cookie is offered by a website’s server. Most browsers automatically accept cookies. You can set your browser options so that you will not receive cookies and you can delete existing cookies from your browser. You may find that some parts of this site will not function properly if you have refused cookies.
            </span>
            <br><br>

            <span class="translateMe">
            Information Regarding the Promoting the Quality of Medicines Plus Program
            </span>
            <br>
            <span class="translateMe">
            The information provided on this website is not official United States Government information and does not represent the views or positions of the United States Agency for USP. For more information about USP Privacy and Security Policy, please visit</span> <a href="https://www.usp.org/legal-notices/privacy-policy">https://www.usp.org/legal-notices/privacy-policy</a>.
            <br><br>

            <span class="translateMe">
            Reference in this website to any specific commercial products, processes, or services, or the use of any trade, firm or corporation name is for the information and convenience of the site’s visitors, and does not constitute endorsement, recommendation, or favoring by the United States Government or USP. The views and opinions of authors expressed herein do not necessarily state or reflect those of the United States Government or USP, and shall not be used for advertising or product endorsement purposes.
            </span>
            <br><br>

            <span class="translateMe">
            With respect to the content of this site, neither the United States Government, nor USP, nor their employees and contractors make any warranty, expressed or implied or statutory, including but not limited to the warranties of non-infringement of third party rights, title, and the warranties of merchantability and fitness for a particular purpose with respect to content available from this website or other Internet resources linked from it. Additionally, neither the United States Government nor USP assume any legal liability for the accuracy, completeness, or usefulness of any information, product, or process disclosed herein nor freedom from computer virus, and do not represent that use of such information, product, or process would not infringe on privately owned rights.
            </span>
            </p>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="aboutmedrs">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><span class="translateMe">Medicines Risk-Based Surveillance Tool (MedRS)</span></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" style="overflow-y: auto; max-height: 80vh;">
        <div class="row">
          <div class="col-md-12">

            <p style="text-align: justify;">
            <span class="translateMe">
            To assist countries combat the burden of falsified and substandard medicines, the PQM Program assists countries in Africa, Asia, Eastern Europe, Latin America, and the Caribbean to develop sustainable Post-marketing Surveillance (PMS) programs.
            </span>
            <br><br>

            <span class="translateMe">
            PQM has developed the <b>Medicines Risk-based Surveillance (MedRS) Tool</b> to assist countries effectively implement risk-based post-marketing surveillance. The MedRS tool automates the science and practice of risk-based post-marketing surveillance into a single platform. The tool evaluates three dimensions of risk—medicines, geographic location, and supply chain—to assist countries identify the most susceptible medicines, determine the number of samples required, and prioritize sampling to the most vulnerable locations. Detailed PMS methodology is described in the user manual of the accompanying PMS tool.
            </span>
            </p>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>

</div>
<!-- <script>
  function validateEmail() {
    const emailField = document.getElementById("email");
    const errorField = document.getElementById("emailError");
    const email = emailField.value.trim().toLowerCase();

    const blockedDomains = [
      "@gmail.com", "@hotmail.com", "@outlook.com",
      "@yahoo.com", "@aol.com", "@icloud.com"
    ];

    // Check if email ends with any blocked domain
    for (let domain of blockedDomains) {
      if (email.endsWith(domain)) {
        errorField.textContent = alert("Only official email addresses are allowed.");
        $('#email').val('').trigger('change');
        emailField.classList.add("is-invalid");
        return false;
      }
    }

    // Clear error if valid
    errorField.textContent = "";
    emailField.classList.remove("is-invalid");
    return true;
  }
</script> -->
<!-- jQuery -->
<script src="<?php echo base_url(); ?>assets/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="<?php echo base_url(); ?>assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Select2 (must load after jQuery) -->
<script src="<?php echo base_url(); ?>assets/plugins/select2/js/select2.full.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url(); ?>assets/dist/js/adminlte.min.js"></script>
<script type='text/javascript'>
  function refreshCaptcha(){
    var img = document.images['captchaimg'];
    img.src = img.src.substring(0,img.src.lastIndexOf("?"))+"?rand="+Math.random()*1000;
  }
</script>
<script type="text/javascript">
  $(document).on('click', '.product-type-btn', function(){
    var scopeModel = $('#productScopeModel').val();
    var $btn = $(this);

    if(scopeModel === 'Single Product'){
      $('.product-type-btn').removeClass('active');
      $btn.addClass('active');
    } else {
      $btn.toggleClass('active');
    }

    syncProductTypeHidden();
    $('#productTypeButtons').removeClass('is-invalid');
    $('#productTypeHidden').removeClass('is-invalid');
  });

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
  $(function () {
    $('.select2').select2({
      theme: 'bootstrap4',
      width: '100%'
    });

    // Dropdown open hote hi search box me cursor auto-focus ho
    $(document).on('select2:open', function () {
      setTimeout(function () {
        var $searchField = $('.select2-container--open .select2-search__field');
        if ($searchField.length) {
          $searchField[0].focus();
        }
      }, 0);
    });
  });
</script>
<script type="text/javascript">
  $('.required').not('#productTypeHidden').each(function(i, obj) {
      $(this).parent().find('label').prepend('<i class="fa fa-asterisk text-danger"></i> ');
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
<script type="text/javascript">
  var tabsOrder = ['#tab1', '#tab2', '#tab3', '#tab4', '#tab5', '#tab6'];

  function getActiveTabIndex(){
    var activeHref = $('.nav-tabs .nav-link.active').attr('href');
    return tabsOrder.indexOf(activeHref);
  }

  function goToStep(index){
    if(index < 0 || index >= tabsOrder.length) return;
    $('.nav-tabs a[href="' + tabsOrder[index] + '"]').tab('show');
    $('html, body').animate({ scrollTop: $('.card-tabs').offset().top - 20 }, 200);
  }

  function validateCurrentTab(){
    var isValid = true;
    var currentTab = tabsOrder[getActiveTabIndex()];
    var $pane = $(currentTab);

    // Har required field ko check karo jo is tab ke andar hai
    $pane.find('.required').each(function(){
      var $field = $(this);
      var val = $field.val();
      var isEmpty = (val === null || val === '' || (Array.isArray(val) && val.length === 0));

      if($field.attr('id') === 'productTypeHidden'){
        isEmpty = isProductTypeEmpty();
        if(isEmpty){
          isValid = false;
          $('#productTypeButtons').addClass('is-invalid');
          $field.addClass('is-invalid');
        } else {
          $('#productTypeButtons').removeClass('is-invalid');
          $field.removeClass('is-invalid');
        }
        return;
      }

      if(isEmpty){
        isValid = false;
        $field.addClass('is-invalid');
        $field.next('.select2-container').find('.select2-selection').addClass('is-invalid-select2');
      } else {
        $field.removeClass('is-invalid');
        $field.next('.select2-container').find('.select2-selection').removeClass('is-invalid-select2');
      }
    });

    if(!isValid){
      // pehle invalid field tak scroll kar do
      var $firstInvalid = $pane.find('.is-invalid').first();
      if($firstInvalid.length){
        $('html, body').animate({ scrollTop: $firstInvalid.offset().top - 100 }, 200);
      }
    }

    return isValid;
  }

  function syncProductTypeHidden(){
    var ids = [];
    $('.product-type-btn.active').each(function(){
      ids.push($(this).data('id'));
    });
    $('#productTypeHidden').val(ids.length ? ids.join(',') + ',' : '');
  }

  function getProductTypeSelection(){
    var ids = [];
    $('.product-type-btn.active').each(function(){
      ids.push(String($(this).data('id')));
    });
    return ids;
  }

  function isProductTypeEmpty(){
    return getProductTypeSelection().length === 0;
  }

  function loadOutputPreview(){
    syncProductTypeHidden();

    $('#outputPreviewLoading').show();
    $('#outputPreviewContent').html('');

    $.ajax({
      url: '<?php echo base_url(); ?>registerOutputPreview',
      type: 'POST',
      dataType: 'json',
      data: {
        productTypeId: $('#productTypeHidden').val(),
        medicineBy: $('#medicineBy').val(),
        computerizedSystem: $('#computerizedSystem').val(),
        sterileAsepticProcess: $('#sterileAsepticProcess').val(),
        ionisingRadiation: $('#ionisingRadiation').val(),
        rtrtParametricRelease: $('#rtrtParametricRelease').val()
      },
      success: function(response){
        $('#outputPreviewLoading').hide();
        if(!response || !response.success){
          $('#outputPreviewContent').html('<div class="alert alert-warning mb-0"><span class="translateMe">Unable to load preview. Please try again.</span></div>');
          return;
        }
        if(!response.systems || response.systems.length === 0){
          $('#outputPreviewContent').html('<div class="alert alert-info mb-0"><span class="translateMe">GMP systems found for the selected scope.</span></div>');
          return;
        }
        var html = '<ul class="list-group">';
        $.each(response.systems, function(i, sys){
          html += '<li class="list-group-item"><i class="fas fa-check-circle text-success mr-2"></i>' + $('<div>').text(sys.name).html() + '</li>';
        });
        html += '</ul>';
        $('#outputPreviewContent').html(html);
      },
      error: function(){
        $('#outputPreviewLoading').hide();
        $('#outputPreviewContent').html('<div class="alert alert-danger mb-0"><span class="translateMe">Unable to load preview. Please try again.</span></div>');
      }
    });
  }


function toggleProductTypeMode(){
  var scopeModel = $('#productScopeModel').val();
  var $buttons = $('#productTypeButtons');
  var activeIds = getProductTypeSelection();

  if(scopeModel === 'Single Product'){
    $buttons.attr('data-mode', 'single');
    $('#productTypeHint span').text('Click to select one product type.');

    if(activeIds.length > 1){
      $('.product-type-btn').removeClass('active');
      $('.product-type-btn[data-id="' + activeIds[0] + '"]').addClass('active');
    }
  } else if(scopeModel === 'Multi-Product'){
    $buttons.attr('data-mode', 'multi');
    $('#productTypeHint span').text('Click to select one or more product types.');
  } else {
    $buttons.attr('data-mode', 'multi');
    $('#productTypeHint span').text('Select Product Scope Model first, then choose product type(s).');
  }

  syncProductTypeHidden();
}

$('#productScopeModel').on('change', function(){
  toggleProductTypeMode();
});

$(document).ready(function(){
  syncProductTypeHidden();
  toggleProductTypeMode();
});


  $('a[href="#tab5"]').on('shown.bs.tab', function(){
    loadOutputPreview();
  });

  function nextStep(){
    var nextIndex = getActiveTabIndex() + 1;
    goToStep(nextIndex);
    if(tabsOrder[nextIndex] === '#tab5'){
      loadOutputPreview();
    }
  }

  $('.nav-tabs a[data-toggle="pill"]').on('show.bs.tab', function(e){
    var clickedHref = $(e.target).attr('href');

    // Sirf jab user Register tab (tab6) pr click/ya Next se aaye
    if(clickedHref === '#tab6'){
      var clickedIndex = tabsOrder.indexOf(clickedHref);
      var incompleteIndex = findFirstIncompleteTab(clickedIndex);

      if(incompleteIndex !== -1){
        e.preventDefault(); // Register tab khulne se rok do

        setTimeout(function(){
          goToStep(incompleteIndex);
          alert('Please fill all mandatory fields before proceeding.');
        }, 50);

        return false;
      }
    }
  });

  function validateTabByIndex(index){
    var isValid = true;
    var $pane = $(tabsOrder[index]);

    $pane.find('.required').each(function(){
      var $field = $(this);
      var val = $field.val();
      var isEmpty = (val === null || val === '' || (Array.isArray(val) && val.length === 0));

      if($field.attr('id') === 'productTypeHidden'){
        isEmpty = isProductTypeEmpty();
        if(isEmpty){
          isValid = false;
          $('#productTypeButtons').addClass('is-invalid');
          $field.addClass('is-invalid');
        } else {
          $('#productTypeButtons').removeClass('is-invalid');
          $field.removeClass('is-invalid');
        }
        return;
      }

      if(isEmpty){
        isValid = false;
        $field.addClass('is-invalid');
        $field.next('.select2-container').find('.select2-selection').addClass('is-invalid-select2');
      } else {
        $field.removeClass('is-invalid');
        $field.next('.select2-container').find('.select2-selection').removeClass('is-invalid-select2');
      }
    });

    return isValid;
  }

  function findFirstIncompleteTab(uptoIndex){
    for(var i = 0; i < uptoIndex; i++){
      if(!validateTabByIndex(i)){
        return i;
      }
    }
    return -1;
  }

  function prevStep(){
    goToStep(getActiveTabIndex() - 1); // back pr validation nahi lagani
  }


  $('#myForm').on('submit', function(e){
  var captchaVal = $('#captcha_code').val().trim();

  if(captchaVal === ''){
    e.preventDefault(); // form submit hone se rok do
    alert('Please fill the captcha code.');
    $('#captcha_code').addClass('is-invalid').focus();
    return false;
  } else {
    $('#captcha_code').removeClass('is-invalid');
  }
});

$('#countryId').on('change', function () {

    let selectedOption = this.options[this.selectedIndex];
    let code = selectedOption.getAttribute('data-code');

    if (!code) return;

    // UI
    document.getElementById('countryCode').innerText = "+" + code;
    document.getElementById('alternatecountryCode').innerText = "+" + code;

    // Hidden field (IMPORTANT)
    document.getElementById('countryCodeInput').value = "+" + code;
    document.getElementById('alternatecountryCodeInput').value = "+" + code;
});
</script>
</body>
</html>
