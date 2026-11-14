<div class="row mb-2">
  <div class="col-sm-6">
    <h1 class="m-0 text-dark font-weight-bold">New Stock Transfer</h1>
  </div>
  <div class="col-sm-6 text-right">
    <a href="<?= base_url('warehouse/transfers') ?>" class="btn btn-secondary shadow-sm font-weight-bold">
      <i class="fas fa-arrow-left mr-1"></i> Back to Transfers
    </a>
  </div>
</div>

<form method="post" action="<?= current_url() ?>">
  <?= csrf_field() ?>

  <div class="card card-outline card-primary shadow-sm">
    <div class="card-header"><h3 class="card-title font-weight-bold">Transfer Route</h3></div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-3">
          <div class="form-group">
            <label>From Store</label>
            <select name="from_store_id" class="form-control" required>
              <?php foreach ($stores as $st): ?>
                <option value="<?= $st->id ?>"><?= esc($st->name) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="col-md-3">
          <div class="form-group">
            <label>From Warehouse</label>
            <select name="from_wh_id" class="form-control" required>
              <?php foreach ($warehouses as $wh): ?>
                <option value="<?= $wh->id ?>"><?= esc($wh->name) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="col-md-3">
          <div class="form-group">
            <label>Destination Store</label>
            <select name="to_store_id" class="form-control" required>
              <?php foreach ($stores as $st): ?>
                <option value="<?= $st->id ?>"><?= esc($st->name) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="col-md-3">
          <div class="form-group">
            <label>Destination Warehouse</label>
            <select name="to_wh_id" class="form-control" required>
              <?php foreach ($warehouses as $wh): ?>
                <option value="<?= $wh->id ?>"><?= esc($wh->name) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
      <h3 class="card-title font-weight-bold m-0"><i class="fas fa-boxes mr-1 text-primary"></i> Transfer Items</h3>
      <button type="button" class="btn btn-success btn-sm font-weight-bold" onclick="addTrfRow()">
        <i class="fas fa-plus mr-1"></i> Add Line
      </button>
    </div>
    <div class="card-body p-0 table-responsive">
      <table class="table table-bordered mb-0">
        <thead class="thead-light">
          <tr>
            <th width="50%">Product</th>
            <th width="20%">Unit</th>
            <th width="20%">Transfer Quantity</th>
            <th width="10%" class="text-center"><i class="fas fa-trash"></i></th>
          </tr>
        </thead>
        <tbody id="trf-items-body"></tbody>
      </table>
    </div>
    <div class="card-footer bg-white">
      <div class="form-group">
        <label>Transfer Notes / Reason</label>
        <textarea name="note" class="form-control" rows="2" placeholder="e.g. Shelf replenishment or branch stock balance transfer"></textarea>
      </div>

      <button type="submit" name="action_draft" value="1" class="btn btn-outline-secondary font-weight-bold">
        <i class="fas fa-save mr-1"></i> Save Draft
      </button>
      <button type="submit" name="action_dispatch" value="1" class="btn btn-primary font-weight-bold shadow-sm ml-2">
        <i class="fas fa-truck mr-1"></i> Dispatch Immediately
      </button>
    </div>
  </div>
</form>

<script>
  var tIndex = 0;
  function addTrfRow() {
    var html = `
      <tr id="trow-${tIndex}">
        <td>
          <input type="text" class="form-control form-control-sm trf-search" placeholder="Type product name or SKU..." data-row="${tIndex}" autocomplete="off">
          <input type="hidden" name="items[${tIndex}][product_id]" id="tpid-${tIndex}" required>
          <div class="search-drop shadow border rounded bg-white p-1" id="tres-${tIndex}" style="display:none; position:absolute; z-index:999; width:350px;"></div>
        </td>
        <td>
          <select name="items[${tIndex}][unit_id]" class="form-control form-control-sm">
            <?php foreach ($units as $u): ?>
              <option value="<?= $u->id ?>"><?= esc($u->name) ?></option>
            <?php endforeach; ?>
          </select>
        </td>
        <td>
          <input type="number" step="0.001" min="0.001" name="items[${tIndex}][qty]" class="form-control form-control-sm font-weight-bold" value="1.000" required>
        </td>
        <td class="text-center">
          <button type="button" class="btn btn-danger btn-xs" onclick="$('#trow-${tIndex}').remove()"><i class="fas fa-times"></i></button>
        </td>
      </tr>
    `;
    $('#trf-items-body').append(html);
    bindTrfSearch(tIndex);
    tIndex++;
  }

  function bindTrfSearch(idx) {
    var inp = $(`#trow-${idx} .trf-search`);
    var res = $(`#tres-${idx}`);
    inp.on('keyup', function() {
      var val = $(this).val().trim();
      if (val.length < 2) { res.hide(); return; }
      $.getJSON('<?= base_url('inventory/products/search') ?>?q=' + encodeURIComponent(val), function(items) {
        var h = '';
        $.each(items, function(i, p) {
          h += `<div class="p-2 border-bottom hover-bg cursor-pointer titem-sel" data-id="${p.id}" data-name="${p.name}" data-row="${idx}">
            <b>${p.name}</b> <small>(${p.sku})</small>
          </div>`;
        });
        res.html(h).show();
      });
    });

    res.on('click', '.titem-sel', function() {
      var pId = $(this).data('id');
      var pName = $(this).data('name');
      var r = $(this).data('row');
      $(`#trow-${r} .trf-search`).val(pName);
      $(`#tpid-${r}`).val(pId);
      res.hide();
    });
  }

  $(document).ready(function() { addTrfRow(); });
</script>
