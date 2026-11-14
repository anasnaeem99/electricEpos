<div class="row mb-2">
  <div class="col-sm-6">
    <h1 class="m-0 text-dark font-weight-bold"><i class="fas fa-star mr-2 text-warning"></i> Store Loyalty Program</h1>
  </div>
  <div class="col-sm-6 text-right">
    <a href="<?= base_url('loyalty/run-credit-job') ?>" class="btn btn-outline-success btn-sm font-weight-bold mr-1" title="Mature pending points older than 24h">
      <i class="fas fa-sync mr-1"></i> Run Credit Job (24h Delay)
    </a>
    <a href="<?= base_url('loyalty/settings') ?>" class="btn btn-secondary shadow-sm btn-sm font-weight-bold">
      <i class="fas fa-cog mr-1"></i> Program Rules
    </a>
  </div>
</div>

<?php if (session()->getFlashdata('success')): ?>
  <div class="alert alert-success alert-dismissible fade show">
    <i class="fas fa-check-circle mr-1"></i> <?= session()->getFlashdata('success') ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
<?php endif; ?>

<div class="row">
  <div class="col-12 col-sm-6 col-md-3">
    <div class="info-box shadow-sm">
      <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-users"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Registered Members</span>
        <span class="info-box-number text-lg"><?= number_format($stats->total_members ?? 0) ?></span>
      </div>
    </div>
  </div>

  <div class="col-12 col-sm-6 col-md-3">
    <div class="info-box shadow-sm">
      <span class="info-box-icon bg-success elevation-1"><i class="fas fa-coins"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Available Points (Rs.)</span>
        <span class="info-box-number text-lg text-success"><?= number_format($stats->total_available ?? 0, 0) ?></span>
        <small class="text-muted">Ready for checkout discount</small>
      </div>
    </div>
  </div>

  <div class="col-12 col-sm-6 col-md-3">
    <div class="info-box shadow-sm">
      <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-clock text-white"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Pending Maturing (24h)</span>
        <span class="info-box-number text-lg text-warning"><?= number_format($stats->total_pending ?? 0, 0) ?></span>
        <small class="text-muted">Uncredited pending sales</small>
      </div>
    </div>
  </div>

  <div class="col-12 col-sm-6 col-md-3">
    <div class="info-box shadow-sm">
      <span class="info-box-icon bg-info elevation-1"><i class="fas fa-gift"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Lifetime Redeemed</span>
        <span class="info-box-number text-lg text-info">Rs. <?= number_format($stats->total_redeemed ?? 0, 2) ?></span>
      </div>
    </div>
  </div>
</div>

<!-- Recent Member Balances -->
<div class="card shadow-sm">
  <div class="card-header bg-white d-flex justify-content-between align-items-center">
    <h3 class="card-title font-weight-bold m-0"><i class="fas fa-award mr-1 text-primary"></i> Customer Loyalty Accounts</h3>
    <a href="<?= base_url('loyalty/customers') ?>" class="btn btn-sm btn-outline-primary font-weight-bold">View All Customers</a>
  </div>
  <div class="card-body p-0 table-responsive">
    <table class="table table-hover table-striped mb-0">
      <thead class="thead-light">
        <tr>
          <th>Customer Name</th>
          <th>Phone Number</th>
          <th>Store Account</th>
          <th class="text-right">Available Points</th>
          <th class="text-right">Pending Points (24h)</th>
          <th class="text-right">Lifetime Earned</th>
          <th width="12%" class="text-center">Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($accounts)): ?>
          <?php foreach ($accounts as $acc): ?>
            <tr>
              <td>
                <a href="<?= base_url('loyalty/customers/' . $acc->id) ?>" class="font-weight-bold text-dark">
                  <?= esc($acc->customer_name) ?>
                </a>
              </td>
              <td><?= esc($acc->customer_phone) ?></td>
              <td><span class="badge badge-light border"><?= esc($acc->store_name) ?></span></td>
              <td class="text-right font-weight-bold text-success font-monospace"><?= number_format($acc->available_points, 0) ?> pts (Rs. <?= number_format($acc->available_points, 2) ?>)</td>
              <td class="text-right text-warning font-monospace"><?= number_format($acc->pending_points, 0) ?> pts</td>
              <td class="text-right font-monospace"><?= number_format($acc->lifetime_earned, 0) ?> pts</td>
              <td class="text-center">
                <a href="<?= base_url('loyalty/customers/' . $acc->id) ?>" class="btn btn-xs btn-info">
                  <i class="fas fa-eye"></i> View History
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="7" class="text-center py-4 text-muted">No loyalty accounts active yet.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
