<div class="row mb-2">
  <div class="col-sm-6">
    <h1 class="m-0 text-dark font-weight-bold">
      <?= isset($product) ? 'Edit Product' : 'Add New Product' ?>
    </h1>
  </div>
  <div class="col-sm-6 text-right">
    <a href="<?= base_url('inventory/products') ?>" class="btn btn-secondary shadow-sm font-weight-bold">
      <i class="fas fa-arrow-left mr-1"></i> Back to Catalog
    </a>
  </div>
</div>

<form method="post" action="<?= current_url() ?>" enctype="multipart/form-data">
  <?= csrf_field() ?>

  <div class="row">
    <!-- Basic Information -->
    <div class="col-lg-8">
      <div class="card card-outline card-primary shadow-sm">
        <div class="card-header">
          <h3 class="card-title font-weight-bold"><i class="fas fa-info-circle mr-1"></i> Product Identification & Classification</h3>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-8">
              <div class="form-group">
                <label>Product Name <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" value="<?= old('name', $product['name'] ?? '') ?>" required placeholder="e.g. Philips LED Bulb 12W Warm White">
              </div>
            </div>

            <div class="col-md-4">
              <div class="form-group">
                <label>SKU / Item Code <span class="text-danger">*</span></label>
                <input type="text" name="sku" class="form-control font-weight-bold" value="<?= old('sku', $product['sku'] ?? 'SKU-'.strtoupper(substr(uniqid(), -6))) ?>" required>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>Primary Barcode</label>
                <input type="text" name="barcode" class="form-control" value="<?= old('barcode', $barcodes[0]->barcode ?? '') ?>" placeholder="Scan or enter barcode">
              </div>
            </div>

            <div class="col-md-4">
              <div class="form-group">
                <label>Internal / Ref Code</label>
                <input type="text" name="internal_code" class="form-control" value="<?= old('internal_code', $product['internal_code'] ?? '') ?>" placeholder="Optional ref">
              </div>
            </div>

            <div class="col-md-4">
              <div class="form-group">
                <label>Base Unit <span class="text-danger">*</span></label>
                <select name="base_unit_id" class="form-control select2" required>
                  <?php foreach ($units as $u): ?>
                    <option value="<?= $u->id ?>" <?= (isset($product) && $product['base_unit_id'] == $u->id) ? 'selected' : '' ?>>
                      <?= esc($u->name) ?> (<?= esc($u->abbreviation) ?>)
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>Category</label>
                <select name="category_id" id="category_select" class="form-control select2">
                  <option value="">-- None / General --</option>
                  <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat->id ?>" <?= (isset($product) && $product['category_id'] == $cat->id) ? 'selected' : '' ?>>
                      <?= esc($cat->name) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>

            <div class="col-md-4">
              <div class="form-group">
                <label>Subcategory</label>
                <select name="subcategory_id" id="subcategory_select" class="form-control select2">
                  <option value="">-- None --</option>
                  <?php if (!empty($subcategories)): ?>
                    <?php foreach ($subcategories as $sc): ?>
                      <option value="<?= $sc->id ?>" <?= (isset($product) && $product['subcategory_id'] == $sc->id) ? 'selected' : '' ?>>
                        <?= esc($sc->name) ?>
                      </option>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </select>
              </div>
            </div>

            <div class="col-md-4">
              <div class="form-group">
                <label>Brand</label>
                <select name="brand_id" class="form-control select2">
                  <option value="">-- None --</option>
                  <?php foreach ($brands as $br): ?>
                    <option value="<?= $br->id ?>" <?= (isset($product) && $product['brand_id'] == $br->id) ? 'selected' : '' ?>>
                      <?= esc($br->name) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>

          <div class="form-group">
            <label>Product Description / Specifications</label>
            <textarea name="description" class="form-control" rows="3" placeholder="Technical specs, warranty notes, etc."><?= old('description', $product['description'] ?? '') ?></textarea>
          </div>
        </div>
      </div>

      <!-- Pricing & Tax Card -->
      <div class="card card-outline card-success shadow-sm">
        <div class="card-header">
          <h3 class="card-title font-weight-bold"><i class="fas fa-tag mr-1"></i> Pricing, Cost & Tax</h3>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <label>Purchase Cost (Rs.)</label>
                <input type="number" step="0.01" name="purchase_price" class="form-control" value="<?= old('purchase_price', $product['purchase_price'] ?? '0.00') ?>" required>
              </div>
            </div>

            <div class="col-md-3">
              <div class="form-group">
                <label class="text-primary font-weight-bold">Retail Price (Rs.) <span class="text-danger">*</span></label>
                <input type="number" step="0.01" name="retail_price" class="form-control font-weight-bold" value="<?= old('retail_price', $product['retail_price'] ?? '0.00') ?>" required>
              </div>
            </div>

            <div class="col-md-3">
              <div class="form-group">
                <label>Wholesale Price (Rs.)</label>
                <input type="number" step="0.01" name="wholesale_price" class="form-control" value="<?= old('wholesale_price', $product['wholesale_price'] ?? '0.00') ?>">
              </div>
            </div>

            <div class="col-md-3">
              <div class="form-group">
                <label>Tax Rate</label>
                <select name="tax_id" class="form-control select2">
                  <option value="">No Tax (0%)</option>
                  <?php foreach ($taxRates as $tr): ?>
                    <option value="<?= $tr->id ?>" <?= (isset($product) && $product['tax_id'] == $tr->id) ? 'selected' : '' ?>>
                      <?= esc($tr->name) ?> (<?= number_format($tr->rate, 1) ?>%)
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Side Settings: Inventory, Weighing, Image -->
    <div class="col-lg-4">
      <div class="card card-outline card-warning shadow-sm">
        <div class="card-header">
          <h3 class="card-title font-weight-bold"><i class="fas fa-sliders-h mr-1"></i> Inventory Controls</h3>
        </div>
        <div class="card-body">
          <div class="form-group">
            <label>Minimum Stock Alert Level</label>
            <input type="number" step="0.001" name="min_stock" class="form-control" value="<?= old('min_stock', $product['min_stock'] ?? '5.000') ?>">
            <small class="text-muted">Triggers low stock alert when balance falls below this.</small>
          </div>

          <div class="form-group">
            <label>Reorder Quantity</label>
            <input type="number" step="0.001" name="reorder_level" class="form-control" value="<?= old('reorder_level', $product['reorder_level'] ?? '10.000') ?>">
          </div>

          <hr>

          <div class="custom-control custom-checkbox mb-2">
            <input type="checkbox" class="custom-control-input" id="batch_tracking" name="batch_tracking" value="1" <?= (!empty($product['batch_tracking'])) ? 'checked' : '' ?>>
            <label class="custom-control-label font-weight-bold" for="batch_tracking">Enable Batch Tracking</label>
          </div>

          <div class="custom-control custom-checkbox mb-2">
            <input type="checkbox" class="custom-control-input" id="expiry_tracking" name="expiry_tracking" value="1" <?= (!empty($product['expiry_tracking'])) ? 'checked' : '' ?>>
            <label class="custom-control-label font-weight-bold" for="expiry_tracking">Enable Expiry Date Tracking</label>
          </div>

          <div class="custom-control custom-checkbox mb-3">
            <input type="checkbox" class="custom-control-input" id="is_weighing" name="is_weighing" value="1" <?= (!empty($product['is_weighing'])) ? 'checked' : '' ?>>
            <label class="custom-control-label font-weight-bold" for="is_weighing">Sold by Weight / Weighing Scale</label>
          </div>

          <div id="scale_section" style="display: <?= (!empty($product['is_weighing'])) ? 'block' : 'none' ?>;">
            <div class="form-group">
              <label>Scale PLU Code</label>
              <input type="text" name="scale_plu" class="form-control form-control-sm" value="<?= old('scale_plu', $product['scale_plu'] ?? '') ?>" placeholder="e.g. 101">
            </div>
            <div class="form-group">
              <label>Weight Unit</label>
              <input type="text" name="weight_unit" class="form-control form-control-sm" value="<?= old('weight_unit', $product['weight_unit'] ?? 'kg') ?>">
            </div>
          </div>

          <?php if (isset($product)): ?>
            <div class="form-group border-top pt-2">
              <label>Product Status</label>
              <select name="is_active" class="form-control form-control-sm">
                <option value="1" <?= $product['is_active'] ? 'selected' : '' ?>>Active (Visible in POS)</option>
                <option value="0" <?= !$product['is_active'] ? 'selected' : '' ?>>Archived / Disabled</option>
              </select>
            </div>
          <?php endif; ?>
        </div>
      </div>

      <div class="card card-outline card-secondary shadow-sm">
        <div class="card-header">
          <h3 class="card-title font-weight-bold"><i class="fas fa-image mr-1"></i> Product Image</h3>
        </div>
        <div class="card-body text-center">
          <?php if (!empty($product['image'])): ?>
            <img src="<?= base_url($product['image']) ?>" alt="Preview" class="img-fluid rounded mb-2 border" style="max-height: 150px;">
          <?php endif; ?>
          <input type="file" name="image" class="form-control-file" accept="image/*">
          <small class="text-muted d-block mt-1">JPEG, PNG, WebP up to 2MB</small>
        </div>
      </div>

      <div class="card shadow-sm">
        <div class="card-body">
          <button type="submit" class="btn btn-primary btn-block font-weight-bold py-2 shadow-sm">
            <i class="fas fa-save mr-1"></i> Save Product
          </button>
          <a href="<?= base_url('inventory/products') ?>" class="btn btn-outline-secondary btn-block">Cancel</a>
        </div>
      </div>
    </div>
  </div>
</form>

<script>
  document.getElementById('is_weighing').addEventListener('change', function() {
    document.getElementById('scale_section').style.display = this.checked ? 'block' : 'none';
  });

  // Dynamic Subcategory load
  $('#category_select').on('change', function() {
    var catId = $(this).val();
    if (!catId) {
      $('#subcategory_select').html('<option value="">-- None --</option>');
      return;
    }
    $.getJSON('<?= base_url('inventory/products/get-subcategories') ?>/' + catId, function(data) {
      var options = '<option value="">-- None --</option>';
      $.each(data, function(idx, item) {
        options += '<option value="' + item.id + '">' + item.name + '</option>';
      });
      $('#subcategory_select').html(options);
    });
  });
</script>
