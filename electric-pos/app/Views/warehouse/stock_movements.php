<div class="row mb-2">
  <div class="col-sm-6">
    <h1 class="m-0 text-dark font-weight-bold"><i class="fas fa-history mr-2 text-primary"></i> Stock Movement Ledger</h1>
    <small class="text-muted">Immutable history of every IN and OUT movement across shelves and warehouses.</small>
  </div>
  <div class="col-sm-6 text-right">
    <a href="<?= base_url('warehouse/stock') ?>" class="btn btn-secondary shadow-sm font-weight-bold">
      <i class="fas fa-arrow-left mr-1"></i> Current Balances
    </a>
  </div>
</div>

<div class="card shadow-sm">
  <div class="card-body p-0 table-responsive">
    <table class="table table-hover table-striped datatable-init mb-0">
      <thead class="thead-light">
        <tr>
          <th>Date & Time</th>
          <th>Product</th>
          <th>Location</th>
          <th>Movement Type</th>
          <th class="text-right">Quantity</th>
          <th>Reference</th>
          <th>Remarks / Note</th>
          <th>Authorized User</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($movements)): ?>
          <?php foreach ($movements as $m): ?>
            <tr>
              <td><?= date('d M Y, h:i A', strtotime($m->created_at)) ?></td>
              <td>
                <span class="font-weight-bold"><?= esc($m->product_name) ?></span>
                <br><small class="text-muted"><code><?= esc($m->product_sku) ?></code></small>
              </td>
              <td>
                <?= esc($m->warehouse_name ?? '-') ?> &raquo; <?= esc($m->location_name ?? 'Shelf') ?>
              </td>
              <td>
                <?php if (strpos($m->movement_type, '_IN') !== false): ?>
                  <span class="badge badge-success"><?= esc($m->movement_type) ?></span>
                <?php elseif (strpos($m->movement_type, '_OUT') !== false): ?>
                  <span class="badge badge-danger"><?= esc($m->movement_type) ?></span>
                <?php else: ?>
                  <span class="badge badge-secondary"><?= esc($m->movement_type) ?></span>
                <?php endif; ?>
              </td>
              <td class="text-right font-weight-bold font-monospace <?= ($m->qty > 0) ? 'text-success' : 'text-danger' ?>">
                <?= ($m->qty > 0 ? '+' : '') . number_format($m->qty, 3) ?> <?= esc($m->unit_abbr ?? '') ?>
              </td>
              <td><?= esc($m->reference_type ?? '-') ?> #<?= esc($m->reference_id ?? '-') ?></td>
              <td><small class="text-muted"><?= esc($m->note ?? '') ?></small></td>
              <td><?= esc($m->user_name ?? 'Staff') ?></td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
