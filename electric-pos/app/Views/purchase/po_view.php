<div class="row mb-2">
  <div class="col-sm-6">
    <h1 class="m-0 text-dark font-weight-bold">
      <?= esc($po->po_no) ?>
    </h1>
    <span class="badge badge-info">Store: <?= esc($po->store_name) ?></span>
    <?php if ($po->status == 'pending'): ?>
      <span class="badge badge-warning">Pending Approval</span>
    <?php elseif ($po->status == 'approved'): ?>
      <span class="badge badge-primary">Approved</span>
    <?php elseif ($po->status == 'received'): ?>
      <span class="badge badge-success">Received via GRN</span>
    <?php endif; ?>
  </div>
  <div class="col-sm-6 text-right">
    <?php if ($po->status == 'pending'): ?>
      <a href="<?= base_url('purchase/po/approve/' . $po->id) ?>" class="btn btn-success shadow-sm font-weight-bold">
        <i class="fas fa-check mr-1"></i> Approve PO
      </a>
    <?php endif; ?>

    <?php if ($po->status == 'approved'): ?>
      <a href="<?= base_url('purchase/grn/create?po_id=' . $po->id) ?>" class="btn btn-primary shadow-sm font-weight-bold">
        <i class="fas fa-truck-loading mr-1"></i> Create GRN (Receive Goods)
      </a>
    <?php endif; ?>

    <a href="<?= base_url('purchase/po') ?>" class="btn btn-secondary shadow-sm ml-1">
      <i class="fas fa-arrow-left mr-1"></i> Back to Orders
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
    <div class="card card-outline card-primary shadow-sm">
      <div class="card-header"><h3 class="card-title font-weight-bold">PO Overview</h3></div>
      <div class="card-body p-0">
        <table class="table table-sm mb-0">
          <tr><th>Supplier</th><td class="font-weight-bold"><?= esc($po->supplier_name) ?></td></tr>
          <tr><th>Phone</th><td><?= esc($po->supplier_phone ?? '-') ?></td></tr>
          <tr><th>PO Date</th><td><?= date('d M Y', strtotime($po->po_date)) ?></td></tr>
          <tr><th>Expected Delivery</th><td><?= !empty($po->expected_date) ? date('d M Y', strtotime($po->expected_date)) : '-' ?></td></tr>
          <tr><th>Created By</th><td><?= esc($po->created_by_name ?? 'Admin') ?></td></tr>
          <tr class="table-info font-weight-bold">
            <th>Total Amount</th>
            <td class="text-primary font-monospace">Rs. <?= number_format($po->total_amount, 2) ?></td>
          </tr>
        </table>
      </div>
    </div>
  </div>

  <div class="col-lg-8">
    <div class="card shadow-sm">
      <div class="card-header bg-white">
        <h3 class="card-title font-weight-bold"><i class="fas fa-boxes mr-1 text-primary"></i> Line Items</h3>
      </div>
      <div class="card-body p-0 table-responsive">
        <table class="table table-hover table-striped mb-0">
          <thead class="thead-light">
            <tr>
              <th>#</th>
              <th>Product Name</th>
              <th>SKU</th>
              <th class="text-right">Ordered Qty</th>
              <th class="text-right">Received Qty</th>
              <th class="text-right">Unit Cost</th>
              <th class="text-right">Line Total</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($po->items)): ?>
              <?php $i=1; foreach ($po->items as $it): ?>
                <tr>
                  <td><?= $i++ ?></td>
                  <td class="font-weight-bold"><?= esc($it->product_name) ?></td>
                  <td><code><?= esc($it->product_sku) ?></code></td>
                  <td class="text-right font-weight-bold"><?= number_format($it->ordered_qty, 3) ?> <?= esc($it->unit_abbr ?? '') ?></td>
                  <td class="text-right text-success font-weight-bold"><?= number_format($it->received_qty, 3) ?></td>
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
