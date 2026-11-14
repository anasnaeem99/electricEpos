<div class="row mb-2">
  <div class="col-sm-6">
    <h1 class="m-0 text-dark font-weight-bold">New Purchase Order</h1>
  </div>
  <div class="col-sm-6 text-right">
    <a href="<?= base_url('purchase/po') ?>" class="btn btn-secondary shadow-sm font-weight-bold">
      <i class="fas fa-arrow-left mr-1"></i> Back to PO List
    </a>
  </div>
</div>

<form method="post" action="<?= current_url() ?>" id="po-form">
  <?= csrf_field() ?>

  <div class="card card-outline card-primary shadow-sm">
    <div class="card-header">
      <h3 class="card-title font-weight-bold"><i class="fas fa-file-invoice mr-1"></i> Order Header</h3>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-4">
          <div class="form-group">
            <label>Select Supplier <span class="text-danger">*</span></label>
            <select name="supplier_id" class="form-control select2" required>
              <option value="">-- Choose Supplier --</option>
              <?php foreach ($suppliers as $s): ?>
                <option value="<?= $s->id ?>"><?= esc($s->name) ?> (<?= esc($s->code) ?>)</option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="col-md-3">
          <div class="form-group">
            <label>PO Date <span class="text-danger">*</span></label>
            <input type="date" name="po_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
          </div>
        </div>

        <div class="col-md-3">
          <div class="form-group">
            <label>Expected Delivery Date</label>
            <input type="date" name="expected_date" class="form-control" value="<?= date('Y-m-d', strtotime('+7 days')) ?>">
          </div>
        </div>

        <div class="col-md-2">
          <div class="form-group">
            <label>Order Discount (Rs.)</label>
            <input type="number" step="0.01" name="discount" id="po-discount" class="form-control" value="0.00">
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Items Table -->
  <div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
      <h3 class="card-title font-weight-bold m-0"><i class="fas fa-boxes mr-1 text-primary"></i> Order Items</h3>
      <button type="button" class="btn btn-success btn-sm font-weight-bold" onclick="addPoRow()">
        <i class="fas fa-plus mr-1"></i> Add Item Line
      </button>
    </div>
    <div class="card-body p-0 table-responsive">
      <table class="table table-bordered mb-0" id="table-po-items">
        <thead class="thead-light">
          <tr>
            <th width="35%">Search & Select Product <span class="text-danger">*</span></th>
            <th width="15%">Unit</th>
            <th width="15%">Quantity <span class="text-danger">*</span></th>
            <th width="15%">Unit Cost (Rs.) <span class="text-danger">*</span></th>
            <th width="15%">Line Total (Rs.)</th>
            <th width="5%" class="text-center"><i class="fas fa-trash"></i></th>
          </tr>
        </thead>
        <tbody id="po-items-body">
          <!-- Rows added by JS -->
        </tbody>
        <tfoot>
          <tr class="table-light font-weight-bold">
            <td colspan="4" class="text-right">Grand Total:</td>
            <td class="text-primary font-monospace h5 m-0" id="display-grand-total">Rs. 0.00</td>
            <td></td>
          </tr>
        </tfoot>
      </table>
    </div>
    <div class="card-footer bg-white">
      <div class="form-group mb-3">
        <label>Terms & Notes for Supplier</label>
        <textarea name="note" class="form-control" rows="2" placeholder="Payment terms, delivery instructions, etc."></textarea>
      </div>

      <button type="submit" class="btn btn-primary px-5 font-weight-bold shadow-sm">
        <i class="fas fa-check-circle mr-1"></i> Save Purchase Order
      </button>
    </div>
  </div>
</form>

<script>
  var rowIndex = 0;

  function addPoRow() {
    var html = `
      <tr id="row-${rowIndex}">
        <td>
          <input type="text" class="form-control form-control-sm product-search-input" placeholder="Type name or SKU..." data-row="${rowIndex}" autocomplete="off">
          <input type="hidden" name="items[${rowIndex}][product_id]" id="pid-${rowIndex}" required>
          <div class="search-results-dropdown shadow border rounded bg-white p-1" id="res-${rowIndex}" style="display:none; position:absolute; z-index:999; max-height:200px; overflow-y:auto; width:350px;"></div>
        </td>
        <td>
          <select name="items[${rowIndex}][unit_id]" class="form-control form-control-sm">
            <?php foreach ($units as $u): ?>
              <option value="<?= $u->id ?>"><?= esc($u->name) ?></option>
            <?php endforeach; ?>
          </select>
        </td>
        <td>
          <input type="number" step="0.001" min="0.001" name="items[${rowIndex}][qty]" id="qty-${rowIndex}" class="form-control form-control-sm qty-input" value="1.000" required oninput="calcLine(${rowIndex})">
        </td>
        <td>
          <input type="number" step="0.01" min="0" name="items[${rowIndex}][unit_cost]" id="cost-${rowIndex}" class="form-control form-control-sm cost-input" value="0.00" required oninput="calcLine(${rowIndex})">
        </td>
        <td class="font-weight-bold font-monospace pt-2" id="line-total-${rowIndex}">
          Rs. 0.00
        </td>
        <td class="text-center">
          <button type="button" class="btn btn-danger btn-xs" onclick="removeRow(${rowIndex})"><i class="fas fa-times"></i></button>
        </td>
      </tr>
    `;
    $('#po-items-body').append(html);
    bindProductSearch(rowIndex);
    rowIndex++;
  }

  function removeRow(idx) {
    $('#row-' + idx).remove();
    recalculateTotal();
  }

  function calcLine(idx) {
    var q = parseFloat($('#qty-' + idx).val()) || 0;
    var c = parseFloat($('#cost-' + idx).val()) || 0;
    var tot = q * c;
    $('#line-total-' + idx).text('Rs. ' + tot.toFixed(2));
    recalculateTotal();
  }

  function recalculateTotal() {
    var grand = 0;
    $('.qty-input').each(function() {
      var r = $(this).closest('tr');
      var q = parseFloat($(this).val()) || 0;
      var c = parseFloat(r.find('.cost-input').val()) || 0;
      grand += (q * c);
    });
    var disc = parseFloat($('#po-discount').val()) || 0;
    grand = Math.max(0, grand - disc);
    $('#display-grand-total').text('Rs. ' + grand.toFixed(2));
  }

  $('#po-discount').on('input', recalculateTotal);

  function bindProductSearch(idx) {
    var input = $(`#row-${idx} .product-search-input`);
    var resBox = $(`#res-${idx}`);

    input.on('keyup', function() {
      var val = $(this).val().trim();
      if (val.length < 2) { resBox.hide(); return; }

      $.getJSON('<?= base_url('inventory/products/search') ?>?q=' + encodeURIComponent(val), function(items) {
        if (!items || items.length === 0) {
          resBox.html('<div class="p-2 text-muted small">No products found.</div>').show();
          return;
        }
        var h = '';
        $.each(items, function(i, p) {
          h += `<div class="p-2 border-bottom hover-bg cursor-pointer item-select" data-id="${p.id}" data-name="${p.name}" data-cost="${p.purchase_price}" data-row="${idx}">
            <b>${p.name}</b> <small class="text-muted">(${p.sku})</small><br>
            <span class="text-success small">Cost: Rs. ${parseFloat(p.purchase_price).toFixed(2)}</span>
          </div>`;
        });
        resBox.html(h).show();
      });
    });

    resBox.on('click', '.item-select', function() {
      var pId = $(this).data('id');
      var pName = $(this).data('name');
      var pCost = $(this).data('cost');
      var r = $(this).data('row');

      $(`#row-${r} .product-search-input`).val(pName);
      $(`#pid-${r}`).val(pId);
      $(`#cost-${r}`).val(pCost);
      calcLine(r);
      resBox.hide();
    });
  }

  $(document).ready(function() {
    addPoRow();
  });
</script>
