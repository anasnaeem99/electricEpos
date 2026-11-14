<div class="row mb-2">
  <div class="col-sm-6">
    <h1 class="m-0 text-dark font-weight-bold"><i class="fas fa-chart-line mr-2 text-primary"></i> Sales Analytics & Revenue Reports</h1>
  </div>
  <div class="col-sm-6 text-right">
    <button onclick="window.print()" class="btn btn-outline-secondary btn-sm shadow-sm font-weight-bold">
      <i class="fas fa-print mr-1"></i> Print / Export Report
    </button>
  </div>
</div>

<!-- Date Filter -->
<div class="card card-outline card-primary shadow-sm mb-3">
  <div class="card-body p-3">
    <form method="get" action="<?= base_url('reports/sales') ?>" class="row align-items-center">
      <div class="col-md-4">
        <label class="small text-muted font-weight-bold">Date From</label>
        <input type="date" name="date_from" class="form-control form-control-sm" value="<?= $dateFrom ?>">
      </div>
      <div class="col-md-4">
        <label class="small text-muted font-weight-bold">Date To</label>
        <input type="date" name="date_to" class="form-control form-control-sm" value="<?= $dateTo ?>">
      </div>
      <div class="col-md-4 pt-4">
        <button type="submit" class="btn btn-primary btn-sm font-weight-bold"><i class="fas fa-filter mr-1"></i> Generate Report</button>
      </div>
    </form>
  </div>
</div>

<!-- KPI Cards -->
<div class="row">
  <div class="col-md-3">
    <div class="info-box shadow-sm">
      <span class="info-box-icon bg-primary"><i class="fas fa-receipt"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Invoices Generated</span>
        <span class="info-box-number text-lg"><?= number_format($summary->total_invoices ?? 0) ?></span>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="info-box shadow-sm">
      <span class="info-box-icon bg-info"><i class="fas fa-coins"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Gross Sales</span>
        <span class="info-box-number text-lg font-monospace">Rs. <?= number_format($summary->total_gross ?? 0, 2) ?></span>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="info-box shadow-sm">
      <span class="info-box-icon bg-danger"><i class="fas fa-percentage"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Discounts Given</span>
        <span class="info-box-number text-lg font-monospace text-danger">- Rs. <?= number_format($summary->total_discount ?? 0, 2) ?></span>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="info-box shadow-sm">
      <span class="info-box-icon bg-success"><i class="fas fa-dollar-sign"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Net Realized Revenue</span>
        <span class="info-box-number text-lg font-monospace text-success">Rs. <?= number_format($summary->total_net ?? 0, 2) ?></span>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <!-- Top Products Sold -->
  <div class="col-lg-8">
    <div class="card shadow-sm">
      <div class="card-header bg-white">
        <h3 class="card-title font-weight-bold"><i class="fas fa-boxes mr-1 text-primary"></i> Product Sales Breakdown</h3>
      </div>
      <div class="card-body p-0 table-responsive">
        <table class="table table-hover table-striped datatable-init mb-0">
          <thead class="thead-light">
            <tr>
              <th>Product</th>
              <th>SKU</th>
              <th class="text-right">Units Sold</th>
              <th class="text-right">Avg Price</th>
              <th class="text-right">Total Revenue</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($byProduct)): ?>
              <?php foreach ($byProduct as $bp): ?>
                <tr>
                  <td class="font-weight-bold"><?= esc($bp->product_name) ?></td>
                  <td><code><?= esc($bp->product_sku) ?></code></td>
                  <td class="text-right font-weight-bold"><?= number_format($bp->qty_sold, 1) ?> <?= esc($bp->unit_name ?? '') ?></td>
                  <td class="text-right">Rs. <?= number_format($bp->avg_price, 2) ?></td>
                  <td class="text-right font-weight-bold text-success font-monospace">Rs. <?= number_format($bp->total_revenue, 2) ?></td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Cashier Performance -->
  <div class="col-lg-4">
    <div class="card shadow-sm">
      <div class="card-header bg-white">
        <h3 class="card-title font-weight-bold"><i class="fas fa-user-check mr-1 text-info"></i> Cashier-Wise Sales</h3>
      </div>
      <div class="card-body p-0">
        <table class="table table-sm mb-0">
          <thead class="thead-light">
            <tr>
              <th>Cashier</th>
              <th class="text-center">Bills</th>
              <th class="text-right">Total Sales</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($byCashier)): ?>
              <?php foreach ($byCashier as $bc): ?>
                <tr>
                  <td class="font-weight-bold"><?= esc($bc->cashier_name) ?></td>
                  <td class="text-center"><span class="badge badge-light border"><?= $bc->invoices_billed ?></span></td>
                  <td class="text-right font-weight-bold font-monospace text-primary">Rs. <?= number_format($bc->total_sales, 2) ?></td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr><td colspan="3" class="text-center text-muted py-3">No data for selected period.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
