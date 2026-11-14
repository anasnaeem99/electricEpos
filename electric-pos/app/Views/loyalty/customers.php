<div class="row mb-2">
  <div class="col-sm-6">
    <h1 class="m-0 text-dark font-weight-bold"><i class="fas fa-users mr-2 text-primary"></i> Loyalty Members</h1>
  </div>
  <div class="col-sm-6 text-right">
    <a href="<?= base_url('loyalty') ?>" class="btn btn-secondary shadow-sm font-weight-bold">
      <i class="fas fa-arrow-left mr-1"></i> Loyalty Dashboard
    </a>
  </div>
</div>

<div class="card shadow-sm">
  <div class="card-body p-0 table-responsive">
    <table class="table table-hover table-striped datatable-init mb-0">
      <thead class="thead-light">
        <tr>
          <th>Customer Name</th>
          <th>Mobile</th>
          <th>Store</th>
          <th class="text-right">Available Points</th>
          <th class="text-right">Pending Points</th>
          <th class="text-right">Lifetime Redeemed</th>
          <th width="15%" class="text-center">Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($accounts)): ?>
          <?php foreach ($accounts as $acc): ?>
            <tr>
              <td>
                <a href="<?= base_url('loyalty/customers/' . $acc->id) ?>" class="font-weight-bold text-primary">
                  <?= esc($acc->customer_name) ?>
                </a>
              </td>
              <td><?= esc($acc->customer_phone) ?></td>
              <td><?= esc($acc->store_name) ?></td>
              <td class="text-right font-weight-bold text-success font-monospace">
                <?= number_format($acc->available_points, 0) ?> (Rs. <?= number_format($acc->available_points, 2) ?>)
              </td>
              <td class="text-right text-warning font-monospace"><?= number_format($acc->pending_points, 0) ?></td>
              <td class="text-right font-monospace">Rs. <?= number_format($acc->lifetime_redeemed, 2) ?></td>
              <td class="text-center">
                <a href="<?= base_url('loyalty/customers/' . $acc->id) ?>" class="btn btn-xs btn-info">
                  <i class="fas fa-list"></i> Lots & History
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
