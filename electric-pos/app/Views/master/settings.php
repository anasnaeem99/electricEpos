<div class="row mb-2">
  <div class="col-sm-6">
    <h1 class="m-0 text-dark font-weight-bold"><i class="fas fa-cog mr-2 text-primary"></i> Master Settings</h1>
  </div>
</div>

<?php if (session()->getFlashdata('success')): ?>
  <div class="alert alert-success alert-dismissible fade show">
    <i class="fas fa-check-circle mr-1"></i> <?= session()->getFlashdata('success') ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
<?php endif; ?>

<div class="row">
  <!-- General Business Settings -->
  <div class="col-lg-6">
    <div class="card card-outline card-primary shadow-sm mb-4">
      <div class="card-header bg-white">
        <h3 class="card-title font-weight-bold"><i class="fas fa-building mr-1 text-primary"></i> Business Profile</h3>
      </div>
      <div class="card-body">
        <form method="post" action="<?= base_url('master/settings/general') ?>">
          <?= csrf_field() ?>

          <div class="form-group mb-3">
            <label>Business / Retail Name</label>
            <input type="text" name="name" class="form-control" value="<?= esc($business->name ?? 'EPOS Retail') ?>" required>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group mb-3">
                <label>Currency Code</label>
                <input type="text" name="currency" class="form-control font-weight-bold" value="<?= esc($business->currency ?? 'PKR') ?>" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group mb-3">
                <label>Timezone</label>
                <input type="text" name="timezone" class="form-control" value="<?= esc($business->timezone ?? 'Asia/Karachi') ?>" required>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group mb-3">
                <label>Phone</label>
                <input type="text" name="phone" class="form-control" value="<?= esc($business->phone ?? '') ?>">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="<?= esc($business->email ?? '') ?>">
              </div>
            </div>
          </div>

          <div class="form-group mb-3">
            <label>Head Office Address</label>
            <textarea name="address" class="form-control" rows="2"><?= esc($business->address ?? '') ?></textarea>
          </div>

          <button type="submit" class="btn btn-primary font-weight-bold px-4 shadow-sm">
            <i class="fas fa-save mr-1"></i> Save Business Settings
          </button>
        </form>
      </div>
    </div>
  </div>

  <!-- Store Branch Configuration -->
  <div class="col-lg-6">
    <div class="card card-outline card-info shadow-sm mb-4">
      <div class="card-header bg-white">
        <h3 class="card-title font-weight-bold"><i class="fas fa-store mr-1 text-info"></i> Active Store Branch Details</h3>
      </div>
      <div class="card-body">
        <form method="post" action="<?= base_url('master/settings/store') ?>">
          <?= csrf_field() ?>
          <input type="hidden" name="store_id" value="<?= $store->id ?>">

          <div class="row">
            <div class="col-md-8">
              <div class="form-group mb-3">
                <label>Store Branch Name</label>
                <input type="text" name="name" class="form-control" value="<?= esc($store->name) ?>" required>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group mb-3">
                <label>Store Code</label>
                <input type="text" name="code" class="form-control font-weight-bold" value="<?= esc($store->code) ?>" required>
              </div>
            </div>
          </div>

          <div class="form-group mb-3">
            <label>Store Phone</label>
            <input type="text" name="phone" class="form-control" value="<?= esc($store->phone ?? '') ?>">
          </div>

          <div class="form-group mb-3">
            <label>Store Branch Address</label>
            <textarea name="address" class="form-control" rows="2"><?= esc($store->address ?? '') ?></textarea>
          </div>

          <div class="form-group mb-3">
            <label>Receipt Invoice Header / Tagline</label>
            <input type="text" name="invoice_header" class="form-control" value="<?= esc($store->invoice_header ?? 'Welcome to EPOS Retail!') ?>">
          </div>

          <button type="submit" class="btn btn-info font-weight-bold px-4 shadow-sm">
            <i class="fas fa-save mr-1"></i> Save Branch Details
          </button>
        </form>
      </div>
    </div>
  </div>
</div>
