<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login | EPOS Multi-Store Retail</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?= base_url('assets/plugins/fontawesome-free/css/all.min.css') ?>">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="<?= base_url('assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css') ?>">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?= base_url('assets/dist/css/adminlte.min.css') ?>">
  
  <style>
    body {
      background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .login-box {
      width: 420px;
    }
    .card {
      border-radius: 12px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.3);
      overflow: hidden;
    }
    .login-logo b {
      color: #fff;
      font-size: 2.2rem;
      letter-spacing: 2px;
    }
    .login-logo span {
      color: #ffc107;
    }
    .login-tagline {
      color: rgba(255,255,255,0.85);
      font-size: 0.95rem;
      text-align: center;
      margin-bottom: 20px;
    }
  </style>
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo mb-1">
    <a href="#"><b>E<span>POS</span></b></a>
  </div>
  <div class="login-tagline">Multi-Store Retail Management System</div>

  <div class="card">
    <div class="card-body login-card-body p-4">
      <p class="login-box-msg font-weight-bold text-dark">Sign in to start your session</p>

      <?php if (!empty($error)): ?>
        <div class="alert alert-danger py-2">
          <i class="fas fa-exclamation-circle mr-1"></i> <?= esc($error) ?>
        </div>
      <?php endif; ?>

      <?php if (!empty($success)): ?>
        <div class="alert alert-success py-2">
          <i class="fas fa-check-circle mr-1"></i> <?= esc($success) ?>
        </div>
      <?php endif; ?>

      <form action="<?= base_url('login') ?>" method="post">
        <?= csrf_field() ?>
        
        <div class="form-group mb-3">
          <label class="text-muted small">Username or Email</label>
          <div class="input-group">
            <input type="text" name="username" class="form-control" placeholder="Enter username (default: admin)" required autofocus>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-user"></span>
              </div>
            </div>
          </div>
        </div>

        <div class="form-group mb-4">
          <label class="text-muted small">Password</label>
          <div class="input-group">
            <input type="password" name="password" class="form-control" placeholder="Enter password (default: admin123)" required>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block font-weight-bold py-2 shadow-sm">
              <i class="fas fa-sign-in-alt mr-1"></i> Login to System
            </button>
          </div>
        </div>
      </form>

      <div class="mt-4 pt-3 border-top text-center text-muted small">
        Default Login: <code>admin</code> / <code>admin123</code>
      </div>
    </div>
  </div>
</div>

<!-- jQuery -->
<script src="<?= base_url('assets/plugins/jquery/jquery.min.js') ?>"></script>
<!-- Bootstrap 4 -->
<script src="<?= base_url('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<!-- AdminLTE App -->
<script src="<?= base_url('assets/dist/js/adminlte.min.js') ?>"></script>
</body>
</html>
