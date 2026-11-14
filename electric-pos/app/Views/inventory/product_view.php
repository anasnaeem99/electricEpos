<div class="row mb-2">
  <div class="col-sm-6">
    <h1 class="m-0 text-dark font-weight-bold">
      <?= esc($product->name) ?>
    </h1>
    <span class="badge badge-primary mr-1">SKU: <?= esc($product->sku) ?></span>
    <?php if ($product->is_active): ?>
      <span class="badge badge-success">Active</span>
    <?php else: ?>
      <span class="badge badge-danger">Archived</span>
    <?php endif; ?>
  </div>
  <div class="col-sm-6 text-right">
    <a href="<?= base_url('inventory/products/edit/' . $product->id) ?>" class="btn btn-warning shadow-sm font-weight-bold">
      <i class="fas fa-edit mr-1"></i> Edit Product
    </a>
    <a href="<?= base_url('inventory/products') ?>" class="btn btn-secondary shadow-sm ml-1">
      <i class="fas fa-arrow-left mr-1"></i> Back to List
    </a>
  </div>
</div>

<div class="row">
  <!-- Left Column: Details & Stock by location -->
  <div class="col-lg-8">
    <div class="card card-outline card-primary shadow-sm">
      <div class="card-header">
        <h3 class="card-title font-weight-bold"><i class="fas fa-boxes mr-1"></i> Stock Distribution Across Locations</h3>
      </div>
      <div class="card-body p-0 table-responsive">
        <table class="table table-hover table-striped mb-0">
          <thead class="thead-light">
            <tr>
              <th>Store</th>
              <th>Warehouse</th>
              <th>Location (Shelf/Bin)</th>
              <th>Batch / Lot</th>
              <th>Expiry</th>
              <th class="text-right">Quantity</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($stockOverview)): ?>
              <?php $totalQty = 0; foreach ($stockOverview as $st): $totalQty += $st->quantity; ?>
                <tr>
                  <td><?= esc($st->store_name) ?></td>
                  <td><?= esc($st->warehouse_name) ?></td>
                  <td>
                    <span class="badge badge-secondary"><?= ucfirst($st->location_type) ?></span>
                    <?= esc($st->location_name) ?>
                  </td>
                  <td><?= esc($st->batch_no ?? 'Standard / Default') ?></td>
                  <td><?= !empty($st->expiry_date) ? date('d M Y', strtotime($st->expiry_date)) : '-' ?></td>
                  <td class="text-right font-weight-bold text-success font-monospace">
                    <?= number_format($st->quantity, 3) ?> <?= esc($product->unit_abbr) ?>
                  </td>
                </tr>
              <?php endforeach; ?>
              <tr class="table-info font-weight-bold">
                <td colspan="5" class="text-right">Total On-Hand:</td>
                <td class="text-right text-primary"><?= number_format($totalQty, 3) ?> <?= esc($product->unit_abbr) ?></td>
              </tr>
            <?php else: ?>
              <tr>
                <td colspan="6" class="text-center py-4 text-muted">
                  <i class="fas fa-cubes fa-2x mb-2 d-block"></i> No stock balances recorded. Receive goods via <b>Purchase / GRN</b> to add stock.
                </td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Recent Stock Movements -->
    <div class="card shadow-sm">
      <div class="card-header bg-white">
        <h3 class="card-title font-weight-bold"><i class="fas fa-history mr-1 text-info"></i> Recent Stock Movement Audit Trail</h3>
      </div>
      <div class="card-body p-0 table-responsive">
        <table class="table table-sm table-striped mb-0">
          <thead>
            <tr>
              <th>Date/Time</th>
              <th>Store</th>
              <th>Movement Type</th>
              <th>Qty</th>
              <th>Ref Type / ID</th>
              <th>User</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($stockMovements)): ?>
              <?php foreach ($stockMovements as $sm): ?>
                <tr>
                  <td><?= date('d M Y, h:i A', strtotime($sm->created_at)) ?></td>
                  <td><?= esc($sm->store_name) ?></td>
                  <td>
                    <?php if (strpos($sm->movement_type, '_IN') !== false): ?>
                      <span class="badge badge-success"><?= esc($sm->movement_type) ?></span>
                    <?php else: ?>
                      <span class="badge badge-danger"><?= esc($sm->movement_type) ?></span>
                    <?php endif; ?>
                  </td>
                  <td class="font-weight-bold <?= ($sm->qty > 0) ? 'text-success' : 'text-danger' ?>">
                    <?= ($sm->qty > 0 ? '+' : '') . number_format($sm->qty, 3) ?>
                  </td>
                  <td><?= esc($sm->reference_type ?? '-') ?> #<?= esc($sm->reference_id ?? '-') ?></td>
                  <td><?= esc($sm->user_name ?? 'System') ?></td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="6" class="text-center text-muted py-3">No movements yet.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Right Column: Specs & Barcodes -->
  <div class="col-lg-4">
    <div class="card card-outline card-secondary shadow-sm">
      <div class="card-header">
        <h3 class="card-title font-weight-bold"><i class="fas fa-info mr-1"></i> Specifications & Pricing</h3>
      </div>
      <div class="card-body p-0">
        <table class="table table-sm mb-0">
          <tr>
            <th width="45%">Retail Price</th>
            <td class="font-weight-bold text-success text-right">Rs. <?= number_format($product->retail_price, 2) ?></td>
          </tr>
          <tr>
            <th>Purchase Cost</th>
            <td class="text-muted text-right">Rs. <?= number_format($product->purchase_price, 2) ?></td>
          </tr>
          <tr>
            <th>Wholesale Price</th>
            <td class="text-right">Rs. <?= number_format($product->wholesale_price, 2) ?></td>
          </tr>
          <tr>
            <th>Base Unit</th>
            <td class="text-right"><?= esc($product->unit_name) ?> (<?= esc($product->unit_abbr) ?>)</td>
          </tr>
          <tr>
            <th>Category</th>
            <td class="text-right"><?= esc($product->category_name ?? '-') ?></td>
          </tr>
          <tr>
            <th>Brand</th>
            <td class="text-right"><?= esc($product->brand_name ?? '-') ?></td>
          </tr>
          <tr>
            <th>Tax Rate</th>
            <td class="text-right"><?= number_format($product->tax_rate ?? 0, 1) ?>%</td>
          </tr>
          <tr>
            <th>Min Stock Alert</th>
            <td class="text-right text-warning font-weight-bold"><?= number_format($product->min_stock, 3) ?></td>
          </tr>
        </table>
      </div>
    </div>

    <!-- Barcodes -->
    <div class="card shadow-sm">
      <div class="card-header bg-white">
        <h3 class="card-title font-weight-bold"><i class="fas fa-barcode mr-1 text-primary"></i> Registered Barcodes</h3>
      </div>
      <div class="card-body p-0">
        <ul class="list-group list-group-flush">
          <?php if (!empty($barcodes)): ?>
            <?php foreach ($barcodes as $bc): ?>
              <li class="list-group-item d-flex justify-content-between align-items-center">
                <code><?= esc($bc->barcode) ?></code>
                <span class="badge badge-light border"><?= esc($bc->unit_name ?? 'Unit') ?></span>
              </li>
            <?php endforeach; ?>
          <?php else: ?>
            <li class="list-group-item text-muted text-center">No barcodes linked.</li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </div>
</div>
