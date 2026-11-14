<div class="row mb-2">
  <div class="col-sm-6">
    <h1 class="m-0 text-dark font-weight-bold">New Stock Adjustment</h1>
  </div>
  <div class="col-sm-6 text-right">
    <a href="<?= base_url('warehouse/adjustments') ?>" class="btn btn-secondary shadow-sm font-weight-bold">
      <i class="fas fa-arrow-left mr-1"></i> Back to Adjustments
    </a>
  </div>
</div>

<form method="post" action="<?= current_url() ?>">
  <?= csrf_field() ?>

  <div class="card card-outline card-warning shadow-sm">
    <div class="card-header"><h3 class="card-title font-weight-bold">Adjustment Header</h3></div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label>Warehouse <span class="text-danger">*</span></label>
            <select name="warehouse_id" class="form-control select2" required>
              <?php foreach ($warehouses as $wh): ?>
                <option value="<?= $wh->id ?>"><?= esc($wh->name) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="col-md-6">
          <div class="form-group">
            <label>Adjustment Reason <span class="text-danger">*</span></label>
            <select name="reason" class="form-control">
              <option value="Physical Stock Count Variance">Physical Stock Count Variance</option>
              <option value="Damaged / Broken in Store">Damaged / Broken in Store</option>
              <option value="Shelf Expired Goods Written Off">Shelf Expired Goods Written Off</option>
              <option value="Supplier Sample / Demonstration">Supplier Sample / Demonstration</option>
              <option value="Correction of Entry Error">Correction of Entry Error</option>
            </select>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
      <h3 class="card-title font-weight-bold m-0"><i class="fas fa-boxes mr-1 text-primary"></i> Adjustment Lines</h3>
      <button type="button" class="btn btn-success btn-sm font-weight-bold" onclick="addAdjRow()">
        <i class="fas fa-plus mr-1"></i> Add Line
      </button>
    </div>
    <div class="card-body p-0 table-responsive">
      <table class="table table-bordered mb-0">
        <thead class="thead-light">
          <tr>
            <th width="40%">Product</th>
            <th width="25%">Shelf / Location</th>
            <th width="20%">Adjustment Qty (+ to increase, - to decrease) <span class="text-danger">*</span></th>
            <th width="15%" class="text-center"><i class="fas fa-trash"></i></th>
          </tr>
        </thead>
        <tbody id="adj-items-body"></tbody>
      </table>
    </div>
    <div class="card-footer bg-white">
      <button type="submit" name="action_draft" value="1" class="btn btn-outline-secondary font-weight-bold mr-2">
        <i class="fas fa-save mr-1"></i> Save as Draft
      </button>
      <button type="submit" name="action_post" value="1" class="btn btn-warning font-weight-bold shadow-sm px-4">
        <i class="fas fa-check-double mr-1"></i> Post Immediately
      </button>
    </div>
  </div>
</form>

<script>
  var aIndex = 0;
  var locOptions = `<?php foreach ($locations as $loc): ?><option value="<?= $loc->id ?>"><?= esc($loc->name) ?> (<?= ucfirst($loc->location_type) ?>)</option><?php endforeach; ?>`;

  function addAdjRow() {
    var html = `
      <tr id="arow-${aIndex}">
        <td>
          <input type="text" class="form-control form-control-sm adj-search" placeholder="Search product..." data-row="${aIndex}" autocomplete="off">
          <input type="hidden" name="items[${aIndex}][product_id]" id="apid-${aIndex}" required>
          <div class="search-drop shadow border rounded bg-white p-1" id="ares-${aIndex}" style="display:none; position:absolute; z-index:999; width:350px;"></div>
        </td>
        <td>
          <select name="items[${aIndex}][location_id]" class="form-control form-control-sm" required>
            ${locOptions}
          </select>
        </td>
        <td>
          <input type="number" step="0.001" name="items[${aIndex}][qty_adjusted]" class="form-control form-control-sm font-weight-bold text-center" placeholder="-2.000 or +5.000" required>
        </td>
        <td class="text-center">
          <button type="button" class="btn btn-danger btn-xs" onclick="$('#arow-${aIndex}').remove()"><i class="fas fa-times"></i></button>
        </td>
      </tr>
    `;
    $('#adj-items-body').append(html);
    bindAdjSearch(aIndex);
    aIndex++;
  }

  function bindAdjSearch(idx) {
    var inp = $(`#arow-${idx} .adj-search`);
    var res = $(`#ares-${idx}`);
    inp.on('keyup', function() {
      var val = $(this).val().trim();
      if (val.length < 2) { res.hide(); return; }
      $.getJSON('<?= base_url('inventory/products/search') ?>?q=' + encodeURIComponent(val), function(items) {
        var h = '';
        $.each(items, function(i, p) {
          h += `<div class="p-2 border-bottom hover-bg cursor-pointer aitem-sel" data-id="${p.id}" data-name="${p.name}" data-row="${idx}">
            <b>${p.name}</b> <small>(${p.sku})</small>
          </div>`;
        });
        res.html(h).show();
      });
    });

    res.on('click', '.aitem-sel', function() {
      var pId = $(this).data('id');
      var pName = $(this).data('name');
      var r = $(this).data('row');
      $(`#arow-${r} .adj-search`).val(pName);
      $(`#apid-${r}`).val(pId);
      res.hide();
    });
  }

  $(document).ready(function() { addAdjRow(); });
</script>
