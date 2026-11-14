<div class="row mb-2">
  <div class="col-sm-6">
    <h1 class="m-0 text-dark font-weight-bold"><i class="fas fa-file-invoice mr-2 text-primary"></i> Invoices & Receipts</h1>
  </div>
  <div class="col-sm-6 text-right">
    <a href="<?= base_url('pos/terminal') ?>" class="btn btn-primary shadow-sm font-weight-bold">
      <i class="fas fa-cash-register mr-1"></i> POS Checkout
    </a>
  </div>
</div>

<!-- Filters -->
<div class="card card-outline card-secondary shadow-sm mb-3">
  <div class="card-body p-3">
    <form method="get" action="<?= base_url('pos/sales') ?>" class="row align-items-center">
      <div class="col-md-4">
        <label class="small text-muted font-weight-bold">From Date</label>
        <input type="date" name="date_from" class="form-control form-control-sm" value="<?= $filters['date_from'] ?? date('Y-m-d') ?>">
      </div>
      <div class="col-md-4">
        <label class="small text-muted font-weight-bold">To Date</label>
        <input type="date" name="date_to" class="form-control form-control-sm" value="<?= $filters['date_to'] ?? date('Y-m-d') ?>">
      </div>
      <div class="col-md-4 pt-4">
        <button type="submit" class="btn btn-secondary btn-sm"><i class="fas fa-filter mr-1"></i> Filter Invoices</button>
        <a href="<?= base_url('pos/sales') ?>" class="btn btn-link btn-sm text-muted">Reset</a>
      </div>
    </form>
  </div>
</div>

<div class="card shadow-sm">
  <div class="card-body p-0 table-responsive">
    <table class="table table-hover table-striped datatable-init mb-0">
      <thead class="thead-light">
        <tr>
          <th>Invoice #</th>
          <th>Date & Time</th>
          <th>Customer</th>
          <th>Cashier</th>
          <th class="text-right">Subtotal</th>
          <th class="text-right">Discount</th>
          <th class="text-right">Grand Total</th>
          <th>Status</th>
          <th width="12%" class="text-center">Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($sales)): ?>
          <?php foreach ($sales as $s): ?>
            <tr>
              <td>
                <span class="font-weight-bold text-primary font-monospace"><?= esc($s->invoice_no ?? 'INV-'.$s->id) ?></span>
              </td>
              <td><?= date('d M Y, h:i A', strtotime($s->sale_date)) ?></td>
              <td>
                <b><?= esc($s->customer_name ?? 'Walk-in Customer') ?></b>
                <?php if (!empty($s->customer_phone)): ?><br><small class="text-muted"><?= esc($s->customer_phone) ?></small><?php endif; ?>
              </td>
              <td><?= esc($s->cashier_name ?? 'Cashier') ?></td>
              <td class="text-right font-monospace">Rs. <?= number_format($s->subtotal, 2) ?></td>
              <td class="text-right text-danger font-monospace"><?= ($s->discount_amount > 0) ? '- Rs. '.number_format($s->discount_amount, 2) : '-' ?></td>
              <td class="text-right font-weight-bold text-success font-monospace">Rs. <?= number_format($s->total_amount, 2) ?></td>
              <td>
                <?php if ($s->sale_status == 'completed'): ?>
                  <span class="badge badge-success">Paid</span>
                <?php elseif ($s->sale_status == 'returned'): ?>
                  <span class="badge badge-danger">Returned</span>
                <?php else: ?>
                  <span class="badge badge-warning"><?= ucfirst($s->sale_status) ?></span>
                <?php endif; ?>
              </td>
              <td class="text-center">
                <a href="<?= base_url('pos/receipt/' . $s->id) ?>" target="_blank" class="btn btn-xs btn-default" title="Print Receipt">
                  <i class="fas fa-print"></i> Print
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
