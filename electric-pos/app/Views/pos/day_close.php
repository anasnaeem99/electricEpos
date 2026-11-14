<div class="row mb-2">
  <div class="col-sm-6">
    <h1 class="m-0 text-dark font-weight-bold"><i class="fas fa-lock mr-2 text-primary"></i> Daily Day Close Reconciliation</h1>
    <small class="text-muted">Register and cashier shift balance check for <?= date('d M Y') ?></small>
  </div>
  <div class="col-sm-6 text-right">
    <a href="<?= base_url('dashboard') ?>" class="btn btn-secondary shadow-sm font-weight-bold">
      <i class="fas fa-arrow-left mr-1"></i> Dashboard
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
  <!-- Left Side: Calculation & Summary Breakdown -->
  <div class="col-lg-7">
    <div class="card card-outline card-primary shadow-sm">
      <div class="card-header bg-white">
        <h3 class="card-title font-weight-bold"><i class="fas fa-calculator mr-1"></i> Shift Totals & Cash Calculation</h3>
      </div>
      <div class="card-body p-0">
        <table class="table table-bordered mb-0">
          <tbody>
            <tr>
              <th width="60%">Total Invoices Billed Today</th>
              <td class="text-right font-weight-bold"><?= $summary->total_bills ?? 0 ?></td>
            </tr>
            <tr>
              <th>Total Gross Revenue</th>
              <td class="text-right font-weight-bold text-primary">Rs. <?= number_format($summary->total_sales ?? 0, 2) ?></td>
            </tr>
            <tr>
              <th>Total Discounts Given</th>
              <td class="text-right text-danger">- Rs. <?= number_format($summary->total_disc ?? 0, 2) ?></td>
            </tr>
            <tr>
              <th>Total Sales Tax / GST</th>
              <td class="text-right">+ Rs. <?= number_format($summary->total_tax ?? 0, 2) ?></td>
            </tr>
            <tr class="table-info font-weight-bold">
              <th>Opening Cash Float in Register</th>
              <td class="text-right">Rs. <?= number_format($activeShift->opening_cash ?? 0, 2) ?></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Payment Methods Breakdown -->
    <div class="card shadow-sm">
      <div class="card-header bg-white">
        <h3 class="card-title font-weight-bold"><i class="fas fa-wallet mr-1 text-info"></i> Breakdown by Payment Tender</h3>
      </div>
      <div class="card-body p-0">
        <table class="table table-hover mb-0">
          <thead class="thead-light">
            <tr>
              <th>Payment Method</th>
              <th class="text-right">Total Tendered</th>
            </tr>
          </thead>
          <tbody>
            <?php $totalPay = 0; if (!empty($paymentBreakdown)): ?>
              <?php foreach ($paymentBreakdown as $pb): $totalPay += $pb->method_total; ?>
                <tr>
                  <td><b><?= esc($pb->method_name) ?></b></td>
                  <td class="text-right font-weight-bold font-monospace">Rs. <?= number_format($pb->method_total, 2) ?></td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr><td colspan="2" class="text-center py-3 text-muted">No sales yet today.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Right Side: Physical Cash Counting & Day Close Commit -->
  <div class="col-lg-5">
    <div class="card card-outline card-success shadow-sm">
      <div class="card-header bg-success text-white">
        <h3 class="card-title font-weight-bold"><i class="fas fa-money-bill-wave mr-1"></i> Cash Drawer Balancing</h3>
      </div>
      <div class="card-body">
        <form method="post" action="<?= current_url() ?>">
          <?= csrf_field() ?>
          <input type="hidden" name="shift_id" value="<?= $activeShift->id ?? 1 ?>">

          <?php 
            $cashSales = 0;
            if (!empty($paymentBreakdown)) {
              foreach ($paymentBreakdown as $pb) {
                if ($pb->method_name == 'Cash') $cashSales = $pb->method_total;
              }
            }
            $openingCash = $activeShift->opening_cash ?? 0;
            $expectedCash = $openingCash + $cashSales;
          ?>

          <div class="form-group">
            <label class="text-muted small uppercase">Expected Drawer Cash</label>
            <div class="h3 font-weight-bold text-dark font-monospace" id="calc-expected">
              Rs. <?= number_format($expectedCash, 2) ?>
            </div>
            <small class="text-muted">Opening Float (Rs. <?= number_format($openingCash, 2) ?>) + Cash Sales (Rs. <?= number_format($cashSales, 2) ?>)</small>
          </div>

          <hr>

          <div class="form-group">
            <label class="font-weight-bold text-success">Physical Cash Counted (Rs.) <span class="text-danger">*</span></label>
            <div class="input-group input-group-lg">
              <div class="input-group-prepend"><span class="input-group-text font-weight-bold">Rs.</span></div>
              <input type="number" step="0.01" name="actual_cash" id="actual-cash-input" class="form-control font-weight-bold font-monospace" value="<?= $expectedCash ?>" required oninput="calcDifference(<?= $expectedCash ?>)">
            </div>
            <small class="text-muted">Enter total cash physically present in register drawer.</small>
          </div>

          <!-- Difference -->
          <div class="p-3 bg-light border rounded text-center mb-4">
            <span class="text-muted small font-weight-bold uppercase">Variance / Difference</span>
            <div class="h3 font-weight-bold text-success font-monospace m-0" id="disp-variance">Rs. 0.00</div>
            <small class="text-muted" id="variance-label">Perfect Match</small>
          </div>

          <button type="submit" class="btn btn-success btn-block btn-lg font-weight-bold shadow" onclick="return confirm('Confirm Day Close? This will lock today\'s session records.')">
            <i class="fas fa-lock mr-1"></i> Lock & Close Business Day
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  function calcDifference(expected) {
    var actual = parseFloat($('#actual-cash-input').val()) || 0;
    var diff = actual - expected;

    var disp = $('#disp-variance');
    var label = $('#variance-label');

    if (diff === 0) {
      disp.removeClass('text-danger text-warning').addClass('text-success').text('Rs. 0.00');
      label.text('Balanced (No discrepancy)');
    } else if (diff < 0) {
      disp.removeClass('text-success text-warning').addClass('text-danger').text('- Rs. ' + Math.abs(diff).toFixed(2));
      label.text('Shortage (Missing Cash)');
    } else {
      disp.removeClass('text-success text-danger').addClass('text-warning').text('+ Rs. ' + diff.toFixed(2));
      label.text('Overage (Excess Cash)');
    }
  }
</script>
