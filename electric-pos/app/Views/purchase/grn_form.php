<div class="row mb-2">
  <div class="col-sm-6">
    <h1 class="m-0 text-dark font-weight-bold">Receive Goods (GRN)</h1>
    <small class="text-muted">Stock balances increase only when GRN is posted.</small>
  </div>
  <div class="col-sm-6 text-right">
    <a href="<?= base_url('purchase/grn') ?>" class="btn btn-secondary shadow-sm font-weight-bold">
      <i class="fas fa-arrow-left mr-1"></i> Back to GRN List
    </a>
  </div>
</div>

<form method="post" action="<?= current_url() ?>">
  <?= csrf_field() ?>
  <?php if (!empty($po)): ?>
    <input type="hidden" name="po_id" value="<?= $po->id ?>">
  <?php endif; ?>

  <div class="card card-outline card-success shadow-sm">
    <div class="card-header">
      <h3 class="card-title font-weight-bold"><i class="fas fa-warehouse mr-1"></i> Receiving Destination</h3>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-4">
          <div class="form-group">
            <label>Supplier <span class="text-danger">*</span></label>
            <select name="supplier_id" class="form-control select2" required>
              <option value="">-- Choose Supplier --</option>
              <?php foreach ($suppliers as $s): ?>
                <option value="<?= $s->id ?>" <?= (!empty($po) && $po->supplier_id == $s->id) ? 'selected' : '' ?>>
                  <?= esc($s->name) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="col-md-4">
          <div class="form-group">
            <label>Receiving Warehouse <span class="text-danger">*</span></label>
            <select name="warehouse_id" id="warehouse-select" class="form-control select2" required>
              <?php foreach ($warehouses as $wh): ?>
                <option value="<?= $wh->id ?>"><?= esc($wh->name) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="col-md-4">
          <div class="form-group">
            <label>GRN Date <span class="text-danger">*</span></label>
            <input type="date" name="grn_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
      <h3 class="card-title font-weight-bold m-0"><i class="fas fa-boxes mr-1 text-primary"></i> Received Items & Stock Locations</h3>
      <?php if (empty($po)): ?>
        <button type="button" class="btn btn-success btn-sm font-weight-bold" onclick="addGrnRow()">
          <i class="fas fa-plus mr-1"></i> Add Line
        </button>
      <?php endif; ?>
    </div>
    <div class="card-body p-0 table-responsive">
      <table class="table table-bordered mb-0">
        <thead class="thead-light">
          <tr>
            <th width="30%">Product</th>
            <th width="20%">Putaway Location (Shelf / Bin) <span class="text-danger">*</span></th>
            <th width="15%">Received Qty <span class="text-danger">*</span></th>
            <th width="15%">Unit Cost (Rs.) <span class="text-danger">*</span></th>
            <th width="15%">Expiry Date (Optional)</th>
            <?php if (empty($po)): ?><th width="5%"></th><?php endif; ?>
          </tr>
        </thead>
        <tbody id="grn-items-body">
          <?php if (!empty($po->items)): ?>
            <?php $r = 0; foreach ($po->items as $it): ?>
              <tr>
                <td>
                  <input type="hidden" name="items[<?= $r ?>][poi_id]" value="<?= $it->id ?>">
                  <input type="hidden" name="items[<?= $r ?>][product_id]" value="<?= $it->product_id ?>">
                  <input type="hidden" name="items[<?= $r ?>][unit_id]" value="<?= $it->unit_id ?>">
                  <span class="font-weight-bold"><?= esc($it->product_name) ?></span>
                  <br><small class="text-muted">Ordered: <?= number_format($it->ordered_qty, 3) ?> | SKU: <?= esc($it->product_sku) ?></small>
                </td>
                <td>
                  <select name="items[<?= $r ?>][location_id]" class="form-control form-control-sm select2" required>
                    <?php foreach ($locations as $loc): ?>
                      <option value="<?= $loc->id ?>"><?= esc($loc->name) ?> (<?= ucfirst($loc->location_type) ?>)</option>
                    <?php endforeach; ?>
                  </select>
                </td>
                <td>
                  <input type="number" step="0.001" name="items[<?= $r ?>][received_qty]" class="form-control form-control-sm font-weight-bold" value="<?= max(0, $it->ordered_qty - $it->received_qty) ?>" required>
                </td>
                <td>
                  <input type="number" step="0.01" name="items[<?= $r ?>][unit_cost]" class="form-control form-control-sm" value="<?= $it->unit_cost ?>" required>
                </td>
                <td>
                  <input type="date" name="items[<?= $r ?>][expiry_date]" class="form-control form-control-sm">
                </td>
              </tr>
            <?php $r++; endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
    <div class="card-footer bg-white">
      <div class="row align-items-center">
        <div class="col-md-6">
          <input type="text" name="note" class="form-control" placeholder="GRN Delivery Remarks / Bill number...">
        </div>
        <div class="col-md-6 text-right">
          <button type="submit" name="action_draft" value="1" class="btn btn-outline-secondary font-weight-bold mr-2">
            <i class="fas fa-save mr-1"></i> Save as Draft
          </button>
          <button type="submit" name="action_post" value="1" class="btn btn-success font-weight-bold shadow-sm px-4">
            <i class="fas fa-check-double mr-1"></i> Post Immediately to Stock
          </button>
        </div>
      </div>
    </div>
  </div>
</form>

<script>
  var rowIndex = <?= !empty($po->items) ? count($po->items) : 0 ?>;

  function addGrnRow() {
    var locOptions = `<?php foreach ($locations as $loc): ?><option value="<?= $loc->id ?>"><?= esc($loc->name) ?></option><?php endforeach; ?>`;
    var html = `
      <tr id="grow-${rowIndex}">
        <td>
          <input type="text" class="form-control form-control-sm grn-prod-search" placeholder="Search product..." data-row="${rowIndex}" autocomplete="off">
          <input type="hidden" name="items[${rowIndex}][product_id]" id="gpid-${rowIndex}" required>
          <div class="search-drop shadow border rounded bg-white p-1" id="gres-${rowIndex}" style="display:none; position:absolute; z-index:999; width:300px;"></div>
        </td>
        <td>
          <select name="items[${rowIndex}][location_id]" class="form-control form-control-sm" required>
            ${locOptions}
          </select>
        </td>
        <td>
          <input type="number" step="0.001" name="items[${rowIndex}][received_qty]" class="form-control form-control-sm" value="1.000" required>
        </td>
        <td>
          <input type="number" step="0.01" name="items[${rowIndex}][unit_cost]" id="gcost-${rowIndex}" class="form-control form-control-sm" value="0.00" required>
        </td>
        <td>
          <input type="date" name="items[${rowIndex}][expiry_date]" class="form-control form-control-sm">
        </td>
        <td>
          <button type="button" class="btn btn-danger btn-xs" onclick="$('#grow-${rowIndex}').remove()"><i class="fas fa-times"></i></button>
        </td>
      </tr>
    `;
    $('#grn-items-body').append(html);
    bindGrnSearch(rowIndex);
    rowIndex++;
  }

  function bindGrnSearch(idx) {
    var inp = $(`#grow-${idx} .grn-prod-search`);
    var res = $(`#gres-${idx}`);
    inp.on('keyup', function() {
      var val = $(this).val().trim();
      if (val.length < 2) { res.hide(); return; }
      $.getJSON('<?= base_url('inventory/products/search') ?>?q=' + encodeURIComponent(val), function(items) {
        var h = '';
        $.each(items, function(i, p) {
          h += `<div class="p-2 border-bottom hover-bg cursor-pointer gitem-sel" data-id="${p.id}" data-name="${p.name}" data-cost="${p.purchase_price}" data-row="${idx}">
            <b>${p.name}</b> <small>(${p.sku})</small>
          </div>`;
        });
        res.html(h).show();
      });
    });

    res.on('click', '.gitem-sel', function() {
      var pId = $(this).data('id');
      var pName = $(this).data('name');
      var pCost = $(this).data('cost');
      var r = $(this).data('row');
      $(`#grow-${r} .grn-prod-search`).val(pName);
      $(`#gpid-${r}`).val(pId);
      $(`#gcost-${r}`).val(pCost);
      res.hide();
    });
  }

  <?php if (empty($po->items)): ?>
    $(document).ready(function() { addGrnRow(); });
  <?php endif; ?>
</script>
