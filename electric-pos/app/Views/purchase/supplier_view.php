<div class="row mb-2">
  <div class="col-sm-6">
    <h1 class="m-0 text-dark font-weight-bold">
      <?= esc($supplier->name) ?>
    </h1>
    <span class="badge badge-secondary mr-1">Code: <?= esc($supplier->code) ?></span>
    <span class="badge <?= ($supplier->current_balance > 0) ? 'badge-danger' : 'badge-success' ?>">
      Balance Due: Rs. <?= number_format($supplier->current_balance, 2) ?>
    </span>
  </div>
  <div class="col-sm-6 text-right">
    <button type="button" class="btn btn-success shadow-sm font-weight-bold" data-toggle="modal" data-target="#modal-add-payment">
      <i class="fas fa-money-bill-wave mr-1"></i> Pay Supplier
    </button>
    <a href="<?= base_url('purchase/po/create') ?>" class="btn btn-primary shadow-sm font-weight-bold ml-1">
      <i class="fas fa-cart-plus mr-1"></i> Create PO
    </a>
    <a href="<?= base_url('purchase/suppliers') ?>" class="btn btn-secondary shadow-sm ml-1">
      <i class="fas fa-arrow-left mr-1"></i> Back
    </a>
  </div>
</div>

<?php if (session()->getFlashdata('success')): ?>
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle mr-1"></i> <?= session()->getFlashdata('success') ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
<?php endif; ?>

<div class="row">
  <div class="col-lg-4">
    <div class="card card-outline card-primary shadow-sm">
      <div class="card-header"><h3 class="card-title font-weight-bold">Supplier Info</h3></div>
      <div class="card-body p-0">
        <table class="table table-sm mb-0">
          <tr><th>Contact</th><td><?= esc($supplier->contact_person ?? '-') ?></td></tr>
          <tr><th>Phone</th><td><?= esc($supplier->phone ?? '-') ?></td></tr>
          <tr><th>Email</th><td><?= esc($supplier->email ?? '-') ?></td></tr>
          <tr><th>NTN</th><td><?= esc($supplier->ntn ?? '-') ?></td></tr>
          <tr><th>Terms</th><td><?= esc($supplier->payment_terms ?? '-') ?></td></tr>
          <tr><th>Opening Bal</th><td>Rs. <?= number_format($supplier->opening_balance, 2) ?></td></tr>
          <tr class="table-warning">
            <th>Current Balance</th>
            <td class="font-weight-bold text-danger">Rs. <?= number_format($supplier->current_balance, 2) ?></td>
          </tr>
        </table>
      </div>
    </div>
  </div>

  <div class="col-lg-8">
    <div class="card shadow-sm">
      <div class="card-header bg-white">
        <h3 class="card-title font-weight-bold"><i class="fas fa-book mr-1 text-primary"></i> Running Account Ledger</h3>
      </div>
      <div class="card-body p-0 table-responsive">
        <table class="table table-sm table-hover mb-0">
          <thead class="thead-light">
            <tr>
              <th>Date</th>
              <th>Transaction / Ref</th>
              <th class="text-right">Debit (Paid)</th>
              <th class="text-right">Credit (Billed)</th>
              <th class="text-right">Balance</th>
              <th>Note</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($ledger)): ?>
              <?php foreach ($ledger as $l): ?>
                <tr>
                  <td><?= date('d M Y, h:i A', strtotime($l->created_at)) ?></td>
                  <td>
                    <span class="badge badge-secondary"><?= ucfirst($l->ref_type) ?></span>
                    #<?= $l->ref_id ?>
                  </td>
                  <td class="text-right text-success"><?= ($l->debit > 0) ? 'Rs. '.number_format($l->debit, 2) : '-' ?></td>
                  <td class="text-right text-danger"><?= ($l->credit > 0) ? 'Rs. '.number_format($l->credit, 2) : '-' ?></td>
                  <td class="text-right font-weight-bold">Rs. <?= number_format($l->balance, 2) ?></td>
                  <td class="text-muted small"><?= esc($l->note ?? '') ?></td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr><td colspan="6" class="text-center py-4 text-muted">No ledger transactions yet.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Modal Add Payment -->
<div class="modal fade" id="modal-add-payment" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="post" action="<?= base_url('purchase/suppliers/add-payment') ?>">
        <?= csrf_field() ?>
        <input type="hidden" name="supplier_id" value="<?= $supplier->id ?>">

        <div class="modal-header bg-success text-white">
          <h5 class="modal-title font-weight-bold"><i class="fas fa-money-check-alt mr-1"></i> Record Supplier Payment</h5>
          <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Current Outstanding Balance</label>
            <div class="h4 font-weight-bold text-danger">Rs. <?= number_format($supplier->current_balance, 2) ?></div>
          </div>
          <div class="form-group">
            <label>Payment Amount (Rs.) <span class="text-danger">*</span></label>
            <input type="number" step="0.01" name="amount" class="form-control font-weight-bold text-lg" required max="<?= max(1, $supplier->current_balance) ?>" value="<?= max(0, $supplier->current_balance) ?>">
          </div>
          <div class="form-group">
            <label>Payment Date</label>
            <input type="date" name="payment_date" class="form-control" value="<?= date('Y-m-d') ?>">
          </div>
          <div class="form-group">
            <label>Payment Method</label>
            <select name="method" class="form-control">
              <option value="cash">Cash</option>
              <option value="bank_transfer">Bank Transfer / Cheque</option>
              <option value="online">Online / Card</option>
            </select>
          </div>
          <div class="form-group">
            <label>Cheque / Reference Number</label>
            <input type="text" name="reference" class="form-control" placeholder="Optional ref">
          </div>
          <div class="form-group">
            <label>Note</label>
            <textarea name="note" class="form-control" rows="2" placeholder="Payment remarks"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-success font-weight-bold px-4"><i class="fas fa-check mr-1"></i> Post Payment</button>
        </div>
      </form>
    </div>
  </div>
</div>
