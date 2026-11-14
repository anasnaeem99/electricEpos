<div class="row mb-2">
  <div class="col-sm-6">
    <h1 class="m-0 text-dark font-weight-bold">Loyalty Program Configuration</h1>
    <small class="text-muted">Master business rules for point accrual, maturing delay, and expiration.</small>
  </div>
  <div class="col-sm-6 text-right">
    <a href="<?= base_url('loyalty') ?>" class="btn btn-secondary shadow-sm font-weight-bold">
      <i class="fas fa-arrow-left mr-1"></i> Back to Loyalty Dashboard
    </a>
  </div>
</div>

<div class="card card-outline card-warning shadow-sm" style="max-width: 800px;">
  <div class="card-body">
    <form method="post" action="<?= current_url() ?>">
      <?= csrf_field() ?>

      <div class="form-group mb-3">
        <label>Program Name</label>
        <input type="text" name="name" class="form-control" value="<?= esc($program->name ?? 'EPOS Loyalty Program') ?>" required>
      </div>

      <div class="row">
        <div class="col-md-6">
          <div class="form-group mb-3">
            <label>Points Accrual Rate (% of eligible spend) <span class="text-danger">*</span></label>
            <div class="input-group">
              <input type="number" step="0.01" name="earn_rate" class="form-control font-weight-bold" value="<?= $program->earn_rate ?? '1.00' ?>" required>
              <div class="input-group-append"><span class="input-group-text">%</span></div>
            </div>
            <small class="text-muted">Default: 1% (e.g. Rs. 1,000 net spend = 10 points)</small>
          </div>
        </div>

        <div class="col-md-6">
          <div class="form-group mb-3">
            <label>Point Redemption Value (Rs. per 1 Point) <span class="text-danger">*</span></label>
            <div class="input-group">
              <div class="input-group-prepend"><span class="input-group-text">1 Pt = Rs.</span></div>
              <input type="number" step="0.01" name="point_value" class="form-control font-weight-bold" value="<?= $program->point_value ?? '1.00' ?>" required>
            </div>
            <small class="text-muted">Default: 1 point = Rs. 1</small>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-4">
          <div class="form-group mb-3">
            <label>Minimum Spend to Qualify (Rs.) <span class="text-danger">*</span></label>
            <input type="number" step="1" name="min_spend" class="form-control font-weight-bold" value="<?= $program->min_spend ?? '100.00' ?>" required>
            <small class="text-muted">Minimum qualifying shopping Rs. 100</small>
          </div>
        </div>

        <div class="col-md-4">
          <div class="form-group mb-3">
            <label>Credit Delay Period (Hours) <span class="text-danger">*</span></label>
            <input type="number" step="1" name="credit_delay_hrs" class="form-control font-weight-bold" value="<?= $program->credit_delay_hrs ?? '24' ?>" required>
            <small class="text-muted">Points remain pending for 24 hours</small>
          </div>
        </div>

        <div class="col-md-4">
          <div class="form-group mb-3">
            <label>Points Expiry (Calendar Months) <span class="text-danger">*</span></label>
            <input type="number" step="1" name="expiry_months" class="form-control font-weight-bold" value="<?= $program->expiry_months ?? '2' ?>" required>
            <small class="text-muted">Expires 2 months after becoming available</small>
          </div>
        </div>
      </div>

      <div class="alert alert-info py-2 mt-2">
        <i class="fas fa-info-circle mr-1"></i> <b>Store Restriction Policy:</b> Points are strictly restricted to redeem only at the specific store where earned, per EPOS rules.
      </div>

      <button type="submit" class="btn btn-warning px-5 font-weight-bold shadow-sm mt-3">
        <i class="fas fa-save mr-1"></i> Update Loyalty Program Rules
      </button>
    </form>
  </div>
</div>
