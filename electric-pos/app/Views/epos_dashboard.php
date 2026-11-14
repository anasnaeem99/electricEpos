<div class="row">
  <div class="col-12 col-sm-6 col-md-3">
    <div class="info-box shadow-sm">
      <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cash-register"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Today Sales</span>
        <span class="info-box-number text-lg text-primary">Rs. <?= number_format($todaySales, 2) ?></span>
        <small class="text-muted"><?= $todayInvoices ?> Invoices billed</small>
      </div>
    </div>
  </div>

  <div class="col-12 col-sm-6 col-md-3">
    <div class="info-box shadow-sm">
      <span class="info-box-icon bg-success elevation-1"><i class="fas fa-boxes"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Stock Valuation</span>
        <span class="info-box-number text-lg text-success">Rs. <?= number_format($stockValue, 2) ?></span>
        <small class="text-muted"><?= number_format($totalStockQty, 0) ?> Units in stock</small>
      </div>
    </div>
  </div>

  <div class="col-12 col-sm-6 col-md-3">
    <div class="info-box shadow-sm">
      <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-exclamation-triangle text-white"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Low Stock Alert</span>
        <span class="info-box-number text-lg text-warning"><?= $lowStockCount ?></span>
        <small class="text-muted"><a href="<?= base_url('warehouse/stock') ?>?filter=low" class="text-warning font-weight-bold">View Items</a></small>
      </div>
    </div>
  </div>

  <div class="col-12 col-sm-6 col-md-3">
    <div class="info-box shadow-sm">
      <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-dolly"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Pending Transfers</span>
        <span class="info-box-number text-lg text-danger"><?= $pendingTransfers ?></span>
        <small class="text-muted"><a href="<?= base_url('warehouse/transfers') ?>" class="text-danger font-weight-bold">Receive Stock</a></small>
      </div>
    </div>
  </div>
</div>

<!-- Quick Shortcuts -->
<div class="row mb-3">
  <div class="col-12">
    <div class="card card-outline card-primary shadow-sm mb-0">
      <div class="card-body p-2 d-flex flex-wrap align-items-center">
        <span class="mr-3 font-weight-bold text-muted ml-2"><i class="fas fa-bolt text-warning mr-1"></i> Quick POS Launch:</span>
        <a href="<?= base_url('pos/terminal') ?>" class="btn btn-primary btn-sm font-weight-bold mr-2 mb-1">
          <i class="fas fa-desktop mr-1"></i> Open POS Terminal (F1-F12)
        </a>
        <a href="<?= base_url('inventory/products/add') ?>" class="btn btn-outline-success btn-sm font-weight-bold mr-2 mb-1">
          <i class="fas fa-plus mr-1"></i> Add Product
        </a>
        <a href="<?= base_url('purchase/po/create') ?>" class="btn btn-outline-info btn-sm font-weight-bold mr-2 mb-1">
          <i class="fas fa-cart-plus mr-1"></i> New Purchase Order
        </a>
        <a href="<?= base_url('warehouse/transfers/create') ?>" class="btn btn-outline-secondary btn-sm font-weight-bold mr-2 mb-1">
          <i class="fas fa-exchange-alt mr-1"></i> Stock Transfer
        </a>
        <a href="<?= base_url('pos/day-close') ?>" class="btn btn-outline-danger btn-sm font-weight-bold mr-2 mb-1">
          <i class="fas fa-lock mr-1"></i> Daily Day Close
        </a>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <!-- Recent Sales -->
  <div class="col-lg-8">
    <div class="card shadow-sm">
      <div class="card-header bg-white border-bottom-0">
        <h3 class="card-title font-weight-bold"><i class="fas fa-receipt mr-1 text-primary"></i> Recent Store Invoices</h3>
        <div class="card-tools">
          <a href="<?= base_url('reports/sales') ?>" class="btn btn-tool text-primary font-weight-bold">View All Sales</a>
        </div>
      </div>
      <div class="card-body p-0 table-responsive">
        <table class="table table-hover table-striped mb-0">
          <thead>
            <tr>
              <th>Invoice #</th>
              <th>Date/Time</th>
              <th>Customer</th>
              <th>Cashier</th>
              <th>Total</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($recentSales)): ?>
              <?php foreach ($recentSales as $sale): ?>
                <tr>
                  <td class="font-weight-bold text-primary"><?= esc($sale->invoice_no ?? 'INV-'.$sale->id) ?></td>
                  <td><?= date('d M Y, h:i A', strtotime($sale->sale_date)) ?></td>
                  <td><?= esc($sale->customer_name ?? 'Walk-in Customer') ?></td>
                  <td><?= esc($sale->cashier_name ?? 'Staff') ?></td>
                  <td class="font-weight-bold">Rs. <?= number_format($sale->total_amount, 2) ?></td>
                  <td>
                    <?php if ($sale->sale_status == 'completed'): ?>
                      <span class="badge badge-success">Completed</span>
                    <?php elseif ($sale->sale_status == 'returned'): ?>
                      <span class="badge badge-danger">Returned</span>
                    <?php else: ?>
                      <span class="badge badge-warning"><?= ucfirst($sale->sale_status) ?></span>
                    <?php endif; ?>
                  </td>
                  <td>
                    <a href="<?= base_url('pos/receipt/'.$sale->id) ?>" target="_blank" class="btn btn-xs btn-default" title="Print Receipt">
                      <i class="fas fa-print"></i>
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="7" class="text-center py-4 text-muted">
                  <i class="fas fa-inbox fa-2x mb-2 d-block"></i> No sales recorded yet. Click <b>Open POS Terminal</b> to ring up sales!
                </td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Top Products -->
  <div class="col-lg-4">
    <div class="card shadow-sm">
      <div class="card-header bg-white border-bottom-0">
        <h3 class="card-title font-weight-bold"><i class="fas fa-trophy mr-1 text-warning"></i> Fast Moving Items</h3>
      </div>
      <div class="card-body p-0">
        <ul class="products-list product-list-in-card pl-2 pr-2">
          <?php if (!empty($topProducts)): ?>
            <?php foreach ($topProducts as $item): ?>
              <li class="item py-2">
                <div class="product-info ml-2">
                  <a href="javascript:void(0)" class="product-title font-weight-bold"><?= esc($item->product_name) ?>
                    <span class="badge badge-info float-right"><?= number_format($item->total_qty, 1) ?> Sold</span>
                  </a>
                  <span class="product-description text-muted">
                    Revenue: Rs. <?= number_format($item->total_revenue, 2) ?>
                  </span>
                </div>
              </li>
            <?php endforeach; ?>
          <?php else: ?>
            <li class="item text-center py-4 text-muted">
              No product velocity data yet.
            </li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </div>
</div>
