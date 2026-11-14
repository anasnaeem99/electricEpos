<div class="row mb-2">
  <div class="col-sm-6">
    <h1 class="m-0 text-dark font-weight-bold">
      <?= esc($grn->grn_no) ?>
    </h1>
    <span class="badge badge-info"><?= esc($grn->store_name) ?> &raquo; <?= esc($grn->warehouse_name) ?></span>
    <?php if ($grn->status == 'posted'): ?>
      <span class="badge badge-success"><i class="fas fa-check-double mr-1"></i> Posted to Stock</span>
    <?php else: ?>
      <span class="badge badge-warning">Draft (Pending Stock Entry)</span>
    <?php endif; ?>
  </div>
  <div class="col-sm-6 text-right">
    <?php if ($grn->status == 'draft'): ?>
      <a href="<?= base_url('purchase/grn/post/' . $grn->id) ?>" class="btn btn-success shadow-sm font-weight-bold" onclick="return confirm('Post this GRN to stock balances?')">
        <i class="fas fa-check-double mr-1"></i> Post to Stock Now
      </a>
    <?php endif; ?>

    <a href="<?= base_url('purchase/grn') ?>" class="btn btn-secondary shadow-sm ml-1">
      <i class="fas fa-arrow-left mr-1"></i> Back to GRN List
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
  <div class="col-lg-4">
    <div class="card card-outline card-success shadow-sm">
      <div class="card-header"><h3 class="card-title font-weight-bold">GRN Summary</h3></div>
      <div class="card-body p-0">
        <table class="table table-sm mb-0">
          <tr><th>Supplier</th><td class="font-weight-bold"><?= esc($grn->supplier_name) ?></td></tr>
          <tr><th>Warehouse</th><td><?= esc($grn->warehouse_name) ?></td></tr>
          <tr><th>Date Received</th><td><?= date('d M Y', strtotime($grn->grn_date)) ?></td></tr>
          <tr><th>Received By</th><td><?= esc($grn->created_by_name ?? 'Staff') ?></td></tr>
          <tr><th>Posted At</th><td><?= !empty($grn->posted_at) ? date('d M Y, h:i A', strtotime($grn->posted_at)) : '-' ?></td></tr>
          <tr class="table-success font-weight-bold">
            <th>Total Value</th>
            <td class="text-success font-monospace">Rs. <?= number_format($grn->total_amount, 2) ?></td>
          </tr>
        </table>
      </div>
    </div>
  </div>

  <div class="col-lg-8">
    <div class="card shadow-sm">
      <div class="card-header bg-white">
        <h3 class="card-title font-weight-bold"><i class="fas fa-dolly mr-1 text-primary"></i> Received Line Items & Putaway Locations</h3>
      </div>
      <div class="card-body p-0 table-responsive">
        <table class="table table-hover table-striped mb-0">
          <thead class="thead-light">
            <tr>
              <th>#</th>
              <th>Product</th>
              <th>Putaway Shelf / Location</th>
              <th>Batch / Expiry</th>
              <th class="text-right">Received Qty</th>
              <th class="text-right">Unit Cost</th>
              <th class="text-right">Line Total</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($grn->items)): ?>
              <?php $i=1; foreach ($grn->items as $it): ?>
                <tr>
                  <td><?= $i++ ?></td>
                  <td class="font-weight-bold"><?= esc($it->product_name) ?><br><small class="text-muted"><?= esc($it->product_sku) ?></small></td>
                  <td><span class="badge badge-secondary"><?= esc($it->location_name) ?></span></td>
                  <td><?= !empty($it->expiry_date) ? date('d M Y', strtotime($it->expiry_date)) : '-' ?></td>
                  <td class="text-right font-weight-bold text-success"><?= number_format($it->received_qty, 3) ?> <?= esc($it->unit_name ?? '') ?></td>
                  <td class="text-right">Rs. <?= number_format($it->unit_cost, 2) ?></td>
                  <td class="text-right font-weight-bold">Rs. <?= number_format($it->line_total, 2) ?></td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
