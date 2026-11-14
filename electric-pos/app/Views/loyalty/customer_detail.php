<div class="row mb-2">
  <div class="col-sm-6">
    <h1 class="m-0 text-dark font-weight-bold">
      <?= esc($acc->customer_name) ?>
    </h1>
    <span class="badge badge-light border">Phone: <?= esc($acc->customer_phone) ?></span>
    <span class="badge badge-info">Store: <?= esc($acc->store_name) ?></span>
  </div>
  <div class="col-sm-6 text-right">
    <button class="btn btn-warning shadow-sm font-weight-bold" data-toggle="modal" data-target="#modal-adjust-points">
      <i class="fas fa-sliders-h mr-1"></i> Manual Point Adjustment
    </button>
    <a href="<?= base_url('loyalty/customers') ?>" class="btn btn-secondary shadow-sm ml-1 font-weight-bold">
      <i class="fas fa-arrow-left mr-1"></i> Back
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
  <!-- Balances Card -->
  <div class="col-lg-4">
    <div class="card card-outline card-success shadow-sm">
      <div class="card-header"><h3 class="card-title font-weight-bold">Account Balances</h3></div>
      <div class="card-body p-0">
        <table class="table table-sm mb-0">
          <tr class="table-success">
            <th>Available Points</th>
            <td class="text-right font-weight-bold text-success h5 m-0"><?= number_format($acc->available_points, 0) ?> pts</td>
          </tr>
          <tr>
            <th>Redemption Value</th>
            <td class="text-right font-weight-bold text-dark">Rs. <?= number_format($acc->available_points, 2) ?></td>
          </tr>
          <tr class="table-warning">
            <th>Pending Points (24h)</th>
            <td class="text-right font-weight-bold text-warning"><?= number_format($acc->pending_points, 0) ?> pts</td>
          </tr>
          <tr>
            <th>Lifetime Points Earned</th>
            <td class="text-right font-monospace"><?= number_format($acc->lifetime_earned, 0) ?> pts</td>
          </tr>
          <tr>
            <th>Lifetime Redeemed</th>
            <td class="text-right font-monospace">Rs. <?= number_format($acc->lifetime_redeemed, 2) ?></td>
          </tr>
        </table>
      </div>
    </div>
  </div>

  <div class="col-lg-8">
    <!-- Points Lots Breakdown (FIFO Expiry) -->
    <div class="card card-outline card-primary shadow-sm mb-3">
      <div class="card-header bg-white">
        <h3 class="card-title font-weight-bold"><i class="fas fa-layer-group mr-1 text-primary"></i> Earning Lots & Expiry Tracking (FIFO)</h3>
      </div>
      <div class="card-body p-0 table-responsive">
        <table class="table table-sm table-striped mb-0">
          <thead class="thead-light">
            <tr>
              <th>Sale #</th>
              <th>Earned Pts</th>
              <th>Remaining Pts</th>
              <th>Available Date (24h Delay)</th>
              <th>Expiry Date (2 Months)</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($acc->lots)): ?>
              <?php foreach ($acc->lots as $lot): ?>
                <tr>
                  <td>#<?= $lot->sale_id ?></td>
                  <td class="font-weight-bold"><?= number_format($lot->earned_points, 0) ?></td>
                  <td class="font-weight-bold text-success"><?= number_format($lot->remaining_pts, 0) ?></td>
                  <td><?= date('d M Y, h:i A', strtotime($lot->available_at)) ?></td>
                  <td><?= date('d M Y', strtotime($lot->expires_at)) ?></td>
                  <td>
                    <?php if ($lot->status == 'available'): ?>
                      <span class="badge badge-success">Available</span>
                    <?php elseif ($lot->status == 'pending'): ?>
                      <span class="badge badge-warning">Pending (24h delay)</span>
                    <?php elseif ($lot->status == 'expired'): ?>
                      <span class="badge badge-secondary">Expired</span>
                    <?php else: ?>
                      <span class="badge badge-light border"><?= ucfirst($lot->status) ?></span>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr><td colspan="6" class="text-center py-3 text-muted">No lots recorded yet.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Transaction Journal -->
    <div class="card shadow-sm">
      <div class="card-header bg-white">
        <h3 class="card-title font-weight-bold"><i class="fas fa-history mr-1 text-info"></i> Point Activity Journal</h3>
      </div>
      <div class="card-body p-0 table-responsive">
        <table class="table table-sm mb-0">
          <thead>
            <tr>
              <th>Date</th>
              <th>Activity Type</th>
              <th class="text-right">Points</th>
              <th>Remarks</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($acc->transactions)): ?>
              <?php foreach ($acc->transactions as $tx): ?>
                <tr>
                  <td><?= date('d M Y, h:i A', strtotime($tx->created_at)) ?></td>
                  <td><span class="badge badge-light border"><?= strtoupper($tx->txn_type) ?></span></td>
                  <td class="text-right font-weight-bold <?= ($tx->points > 0) ? 'text-success' : 'text-danger' ?>">
                    <?= ($tx->points > 0 ? '+' : '') . number_format($tx->points, 0) ?>
                  </td>
                  <td><small class="text-muted"><?= esc($tx->note) ?></small></td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr><td colspan="4" class="text-center py-3 text-muted">No activity yet.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Modal: Adjust Points -->
<div class="modal fade" id="modal-adjust-points" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="post" action="<?= base_url('loyalty/adjust') ?>">
        <?= csrf_field() ?>
        <input type="hidden" name="account_id" value="<?= $acc->id ?>">

        <div class="modal-header bg-warning">
          <h5 class="modal-title font-weight-bold"><i class="fas fa-sliders-h mr-1"></i> Manual Loyalty Adjustment</h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Points to Add / Deduct <span class="text-danger">*</span></label>
            <input type="number" step="1" name="points" class="form-control font-weight-bold font-monospace" placeholder="+50 or -20" required>
            <small class="text-muted">Use positive number to credit points, negative number to deduct.</small>
          </div>
          <div class="form-group">
            <label>Mandatory Audit Reason <span class="text-danger">*</span></label>
            <textarea name="reason" class="form-control" rows="2" required placeholder="State exact reason for point correction..."></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-warning font-weight-bold px-4">Save Adjustment</button>
        </div>
      </form>
    </div>
  </div>
</div>
