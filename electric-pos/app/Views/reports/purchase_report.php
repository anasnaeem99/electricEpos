<div class="row mb-2">
  <div class="col-sm-6">
    <h1 class="m-0 text-dark font-weight-bold"><i class="fas fa-truck mr-2 text-primary"></i> Outstanding Supplier Payables</h1>
  </div>
  <div class="col-sm-6 text-right">
    <button onclick="window.print()" class="btn btn-outline-secondary btn-sm shadow-sm font-weight-bold">
      <i class="fas fa-print mr-1"></i> Print / Export
    </button>
  </div>
</div>

<div class="card shadow-sm">
  <div class="card-body p-0 table-responsive">
    <table class="table table-hover table-striped datatable-init mb-0">
      <thead class="thead-light">
        <tr>
          <th>Supplier Code</th>
          <th>Supplier Name</th>
          <th>Phone</th>
          <th>Payment Terms</th>
          <th class="text-right">Outstanding Due (Payable)</th>
          <th width="12%" class="text-center">Action</th>
        </tr>
      </thead>
      <tbody>
        <?php $totalDue = 0; if (!empty($payables)): ?>
          <?php foreach ($payables as $p): $totalDue += $p->current_balance; ?>
            <tr>
              <td><code><?= esc($p->code) ?></code></td>
              <td><b><?= esc($p->name) ?></b></td>
              <td><?= esc($p->phone ?? '-') ?></td>
              <td><?= esc($p->payment_terms ?? '-') ?></td>
              <td class="text-right font-weight-bold text-danger font-monospace h6 m-0">Rs. <?= number_format($p->current_balance, 2) ?></td>
              <td class="text-center">
                <a href="<?= base_url('purchase/suppliers/view/' . $p->id) ?>" class="btn btn-xs btn-info">
                  <i class="fas fa-file-invoice-dollar"></i> Ledger
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
      <tfoot class="thead-light font-weight-bold">
        <tr>
          <td colspan="4" class="text-right">Total Outstanding Balance:</td>
          <td class="text-right text-danger font-monospace h5 m-0">Rs. <?= number_format($totalDue, 2) ?></td>
          <td></td>
        </tr>
      </tfoot>
    </table>
  </div>
</div>
