<div class="row mb-2">
  <div class="col-sm-6">
    <h1 class="m-0 text-dark font-weight-bold"><i class="fas fa-boxes mr-2 text-primary"></i> Stock Valuation & Inventory Report</h1>
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
          <th>Product</th>
          <th>SKU</th>
          <th>Warehouse / Shelf</th>
          <th>Batch</th>
          <th class="text-right">On-Hand Qty</th>
          <th class="text-right">Unit Cost</th>
          <th class="text-right">Unit Retail</th>
          <th class="text-right">Cost Valuation</th>
          <th class="text-right">Retail Potential</th>
        </tr>
      </thead>
      <tbody>
        <?php $totalCost = 0; $totalRetail = 0; if (!empty($stock)): ?>
          <?php foreach ($stock as $s): 
            $cVal = $s->quantity * $s->purchase_price;
            $rVal = $s->quantity * $s->retail_price;
            $totalCost += $cVal;
            $totalRetail += $rVal;
          ?>
            <tr>
              <td><b><?= esc($s->product_name) ?></b></td>
              <td><code><?= esc($s->product_sku) ?></code></td>
              <td><?= esc($s->warehouse_name) ?> &raquo; <?= esc($s->location_name) ?></td>
              <td><?= esc($s->batch_no ?? '-') ?></td>
              <td class="text-right font-weight-bold font-monospace"><?= number_format($s->quantity, 3) ?></td>
              <td class="text-right">Rs. <?= number_format($s->purchase_price, 2) ?></td>
              <td class="text-right">Rs. <?= number_format($s->retail_price, 2) ?></td>
              <td class="text-right font-weight-bold font-monospace">Rs. <?= number_format($cVal, 2) ?></td>
              <td class="text-right font-weight-bold text-success font-monospace">Rs. <?= number_format($rVal, 2) ?></td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
      <tfoot class="thead-light font-weight-bold">
        <tr>
          <td colspan="7" class="text-right">Totals:</td>
          <td class="text-right text-primary font-monospace">Rs. <?= number_format($totalCost, 2) ?></td>
          <td class="text-right text-success font-monospace">Rs. <?= number_format($totalRetail, 2) ?></td>
        </tr>
      </tfoot>
    </table>
  </div>
</div>
