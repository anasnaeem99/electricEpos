<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo $pageTitle ?? 'EPOS'; ?></title>
  <link rel="icon" href="<?php echo base_url(); ?>assets/dist/img/AdminLTELogo.png"/>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/fontawesome-free/css/all.min.css">
  <!-- Select2 -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- AdminLTE Theme -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/adminlte.min.css">
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

  <style>
    .sidebar-dark-primary .nav-sidebar>.nav-item>.nav-link.active,
    .sidebar-light-primary .nav-sidebar>.nav-item>.nav-link.active {
      background-color: #007bff !important;
    }
    .epos-brand {
      font-weight: 700;
      font-size: 1.1rem;
      letter-spacing: 1px;
    }
    .nav-sidebar .nav-item .nav-link {
      transition: background 0.2s;
    }
    .store-badge {
      font-size: 0.75rem;
      background: rgba(255,255,255,0.15);
      padding: 2px 8px;
      border-radius: 10px;
    }
    table>tbody>tr:hover {
      background-color: #f5f5f5 !important;
    }
  </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="<?php echo base_url('dashboard'); ?>" class="nav-link">
          <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Store Name -->
      <li class="nav-item d-none d-sm-inline-block">
        <span class="nav-link">
          <i class="fas fa-store text-primary"></i>
          <span class="font-weight-bold text-primary"><?php echo htmlspecialchars($storeName ?? 'Main Store'); ?></span>
        </span>
      </li>

      <!-- Notifications -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#" id="notif-bell">
          <i class="far fa-bell"></i>
          <span class="badge badge-danger navbar-badge" id="notif-count" style="display:none;">0</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header" id="notif-header">Notifications</span>
          <div class="dropdown-divider"></div>
          <div id="notif-list">
            <span class="dropdown-item text-muted">No new notifications</span>
          </div>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
        </div>
      </li>

      <!-- User Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-user-circle"></i>
          <span class="ml-1 d-none d-sm-inline"><?php echo htmlspecialchars($userName ?? 'User'); ?></span>
        </a>
        <div class="dropdown-menu dropdown-menu-right">
          <span class="dropdown-item dropdown-header">
            <strong><?php echo htmlspecialchars($userName ?? 'User'); ?></strong><br>
            <small class="text-muted"><?php echo htmlspecialchars($roleName ?? ''); ?></small>
          </span>
          <div class="dropdown-divider"></div>
          <a href="<?php echo base_url('profile'); ?>" class="dropdown-item">
            <i class="fas fa-user-cog mr-2"></i> My Profile
          </a>
          <div class="dropdown-divider"></div>
          <a href="<?php echo base_url('logout'); ?>" class="dropdown-item text-danger">
            <i class="fas fa-sign-out-alt mr-2"></i> Logout
          </a>
        </div>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?php echo base_url('dashboard'); ?>" class="brand-link">
      <img src="<?php echo base_url(); ?>assets/dist/img/AdminLTELogo.png" alt="EPOS Logo"
           class="brand-image img-circle elevation-3" style="opacity:.8">
      <span class="brand-text font-weight-bold epos-brand">Electric POS</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="<?php echo base_url(); ?>assets/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User">
        </div>
        <div class="info">
          <a href="<?php echo base_url('profile'); ?>" class="d-block">
            <?php echo htmlspecialchars($userName ?? 'User'); ?>
          </a>
          <small class="store-badge"><?php echo htmlspecialchars($storeName ?? 'Main Store'); ?></small>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

          <!-- Dashboard -->
          <li class="nav-item">
            <a href="<?php echo base_url('dashboard'); ?>" class="nav-link <?php echo (uri_string() == 'dashboard') ? 'active' : ''; ?>">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard</p>
            </a>
          </li>

          <!-- POS Terminal -->
          <li class="nav-item">
            <a href="<?php echo base_url('pos/terminal'); ?>" class="nav-link <?php echo (strpos(uri_string(), 'pos') === 0) ? 'active' : ''; ?>">
              <i class="nav-icon fas fa-cash-register"></i>
              <p>POS Terminal</p>
            </a>
          </li>

          <!-- Products / Inventory -->
          <li class="nav-item <?php echo (strpos(uri_string(), 'inventory') === 0) ? 'menu-open' : ''; ?>">
            <a href="#" class="nav-link <?php echo (strpos(uri_string(), 'inventory') === 0) ? 'active' : ''; ?>">
              <i class="nav-icon fas fa-box"></i>
              <p>
                Products
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?php echo base_url('inventory/products'); ?>" class="nav-link <?php echo (uri_string() == 'inventory/products') ? 'active' : ''; ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>All Products</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo base_url('inventory/categories'); ?>" class="nav-link <?php echo (uri_string() == 'inventory/categories') ? 'active' : ''; ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Categories</p>
                </a>
              </li>
            </ul>
          </li>

          <!-- Purchase / GRN -->
          <li class="nav-item <?php echo (strpos(uri_string(), 'purchase') === 0) ? 'menu-open' : ''; ?>">
            <a href="#" class="nav-link <?php echo (strpos(uri_string(), 'purchase') === 0) ? 'active' : ''; ?>">
              <i class="nav-icon fas fa-shopping-cart"></i>
              <p>
                Purchase / GRN
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?php echo base_url('purchase/suppliers'); ?>" class="nav-link <?php echo (uri_string() == 'purchase/suppliers') ? 'active' : ''; ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Suppliers</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo base_url('purchase/po'); ?>" class="nav-link <?php echo (uri_string() == 'purchase/po') ? 'active' : ''; ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Purchase Orders</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo base_url('purchase/grn'); ?>" class="nav-link <?php echo (uri_string() == 'purchase/grn') ? 'active' : ''; ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Goods Receipt (GRN)</p>
                </a>
              </li>
            </ul>
          </li>

          <!-- Warehouse & Stock -->
          <li class="nav-item <?php echo (strpos(uri_string(), 'warehouse') === 0) ? 'menu-open' : ''; ?>">
            <a href="#" class="nav-link <?php echo (strpos(uri_string(), 'warehouse') === 0) ? 'active' : ''; ?>">
              <i class="nav-icon fas fa-warehouse"></i>
              <p>
                Warehouse &amp; Stock
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?php echo base_url('warehouse/stock'); ?>" class="nav-link <?php echo (uri_string() == 'warehouse/stock') ? 'active' : ''; ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Stock Overview</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo base_url('warehouse/transfers'); ?>" class="nav-link <?php echo (uri_string() == 'warehouse/transfers') ? 'active' : ''; ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Transfers</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo base_url('warehouse/adjustments'); ?>" class="nav-link <?php echo (uri_string() == 'warehouse/adjustments') ? 'active' : ''; ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Adjustments</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo base_url('warehouse/count'); ?>" class="nav-link <?php echo (uri_string() == 'warehouse/count') ? 'active' : ''; ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Stock Count</p>
                </a>
              </li>
            </ul>
          </li>

          <!-- Customers -->
          <li class="nav-item">
            <a href="<?php echo base_url('customers'); ?>" class="nav-link <?php echo (strpos(uri_string(), 'customers') === 0) ? 'active' : ''; ?>">
              <i class="nav-icon fas fa-users"></i>
              <p>Customers</p>
            </a>
          </li>

          <!-- Loyalty -->
          <li class="nav-item">
            <a href="<?php echo base_url('loyalty'); ?>" class="nav-link <?php echo (strpos(uri_string(), 'loyalty') === 0) ? 'active' : ''; ?>">
              <i class="nav-icon fas fa-star"></i>
              <p>Loyalty</p>
            </a>
          </li>

          <!-- Reports -->
          <li class="nav-item">
            <a href="<?php echo base_url('reports'); ?>" class="nav-link <?php echo (strpos(uri_string(), 'reports') === 0) ? 'active' : ''; ?>">
              <i class="nav-icon fas fa-chart-bar"></i>
              <p>Reports</p>
            </a>
          </li>

          <!-- Day Close -->
          <li class="nav-item">
            <a href="<?php echo base_url('pos/day-close'); ?>" class="nav-link <?php echo (uri_string() == 'pos/day-close') ? 'active' : ''; ?>">
              <i class="nav-icon fas fa-lock"></i>
              <p>Day Close</p>
            </a>
          </li>

          <!-- Settings (admin only) -->
          <?php if (isset($roleId) && ($roleId == 1 || $roleId == 2)): ?>
          <li class="nav-item">
            <a href="<?php echo base_url('master/settings'); ?>" class="nav-link <?php echo (strpos(uri_string(), 'master/settings') === 0) ? 'active' : ''; ?>">
              <i class="nav-icon fas fa-cog"></i>
              <p>Settings</p>
            </a>
          </li>
          <?php endif; ?>

        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper -->
  <div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
      <div class="container-fluid">
        <!-- Page content starts below -->
