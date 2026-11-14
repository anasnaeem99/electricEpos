<div class="row mb-2">
  <div class="col-sm-6">
    <h1 class="m-0 text-dark font-weight-bold"><i class="fas fa-warehouse mr-2 text-primary"></i> Stock Overview by Shelf & Warehouse</h1>
  </div>
  <div class="col-sm-6 text-right">
    <a href="<?= base_url('warehouse/movements') ?>" class="btn btn-outline-info shadow-sm font-weight-bold mr-1">
      <i class="fas fa-history mr-1"></i> Movement Ledger
    </a>
    <a href="<?= base_url('warehouse/transfers/create') ?>" class="btn btn-primary shadow-sm font-weight-bold">
      <i class="fas fa-exchange-alt mr-1"></i> New Transfer
    </a>
    <a href="<?= base_url('warehouse/adjustments/create') ?>" class="btn btn-warning shadow-sm font-weight-bold ml-1">
      <i class="fas fa-sliders-h mr-1"></i> Adjust Stock
    </a>
  </div>
</div>

<!-- Filters -->
<div class="card card-outline card-secondary shadow-sm mb-3">
  <div class="card-body p-3">
    <form method="get" action="<?= base_url('warehouse/stock') ?>" class="row align-items-center">
      <div class="col-md-4">
        <label class="small text-muted font-weight-bold">Warehouse</label>
        <select name="warehouse_id" class="form-control form-control-sm select2">
          <option value="">-- All Warehouses in Store --</option>
          <?php foreach ($warehouses as $wh): ?>
            <option value="<?= $wh->id ?>" <?= ($selectedWh == $wh->id) ? 'selected' : '' ?>><?= esc($wh->name) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="col-md-4">
        <label class="small text-muted font-weight-bold">Alert Filter</label>
        <select name="filter" class="form-control form-control-sm">
          <option value="">All Balances</option>
          <option value="low" <?= ($lowOnly) ? 'selected' : '' ?>>Low Stock Alert Items Only</option>
        </select>
      </div>

      <div class="col-md-4 pt-4">
        <button type="submit" class="btn btn-secondary btn-sm"><i class="fas fa-filter mr-1"></i> Filter</button>
        <a href="<?= base_url('warehouse/stock') ?>" class="btn btn-link btn-sm text-muted">Reset</a>
      </div>
    </form>
  </div>
</div>

<div class="card shadow-sm">
  <div class="card-body p-0 table-responsive">
    <table class="table table-hover table-striped datatable-init mb-0">
      <thead class="thead-light">
        <tr>
          <th>Product Name</th>
          <th>SKU</th>
          <th>Warehouse</th>
          <th>Stock Location</th>
          <th>Type</th>
          <th>Batch / Expiry</th>
          <th class="text-right">Available Qty</th>
          <th class="text-right">Unit Retail</th>
          <th class="text-right">Total Valuation</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($stock)): ?>
          <?php foreach ($stock as $row): ?>
            <?php $isLow = ($row->quantity <= $row->min_stock); ?>
            <tr class="<?= $isLow ? 'table-warning' : '' ?>">
              <td>
                <a href="<?= base_url('inventory/products/view/' . $row->product_id) ?>" class="font-weight-bold text-dark">
                  <?= esc($row->product_name) ?>
                </a>
                <?php if ($isLow): ?>
                  <span class="badge badge-danger ml-1">Low Stock! (Min: <?= number_format($row->min_stock, 1) ?>)</span>
                <?php endif; ?>
              </td>
              <td><code><?= esc($row->product_sku) ?></code></td>
              <td><?= esc($row->warehouse_name) ?></td>
              <td class="font-weight-bold"><?= esc($row->location_name) ?></td>
              <td><span class="badge badge-light border"><?= ucfirst($row->location_type) ?></span></td>
              <td>
                <?= esc($row->batch_no ?? 'Default') ?>
                <?php if (!empty($row->expiry_date)): ?>
                  <br><small class="text-muted">Exp: <?= date('d M Y', strtotime($row->expiry_date)) ?></small>
                <?php endif; ?>
              </td>
              <td class="text-right font-weight-bold <?= ($row->quantity <= 0) ? 'text-danger' : 'text-success' ?> font-monospace">
                <?= number_format($row->quantity, 3) ?> <?= esc($row->unit_abbr ?? '') ?>
              </td>
              <td class="text-right">Rs. <?= number_format($row->retail_price, 2) ?></td>
              <td class="text-right font-weight-bold">Rs. <?= number_format($row->quantity * $row->purchase_price, 2) ?></td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
