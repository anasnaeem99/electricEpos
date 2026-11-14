<div class="row mb-2">
  <div class="col-sm-6">
    <h1 class="m-0 text-dark font-weight-bold"><i class="fas fa-tags mr-2 text-primary"></i> Product Taxonomy</h1>
  </div>
  <div class="col-sm-6 text-right">
    <a href="<?= base_url('inventory/products') ?>" class="btn btn-secondary shadow-sm font-weight-bold">
      <i class="fas fa-arrow-left mr-1"></i> Products Catalog
    </a>
  </div>
</div>

<div class="card card-primary card-tabs shadow-sm">
  <div class="card-header p-0 pt-1">
    <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
      <li class="nav-item">
        <a class="nav-link active font-weight-bold" id="tab-cat" data-toggle="pill" href="#content-cat" role="tab">
          <i class="fas fa-folder mr-1"></i> Categories
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link font-weight-bold" id="tab-subcat" data-toggle="pill" href="#content-subcat" role="tab">
          <i class="fas fa-folder-open mr-1"></i> Subcategories
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link font-weight-bold" id="tab-brand" data-toggle="pill" href="#content-brand" role="tab">
          <i class="fas fa-copyright mr-1"></i> Brands
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link font-weight-bold" id="tab-unit" data-toggle="pill" href="#content-unit" role="tab">
          <i class="fas fa-balance-scale mr-1"></i> Units of Measurement
        </a>
      </li>
    </ul>
  </div>
  <div class="card-body">
    <div class="tab-content">
      
      <!-- Categories Tab -->
      <div class="tab-pane fade show active" id="content-cat" role="tabpanel">
        <div class="row">
          <div class="col-md-4">
            <div class="card card-outline card-primary">
              <div class="card-header"><h3 class="card-title font-weight-bold">Add Category</h3></div>
              <div class="card-body">
                <form id="form-add-category">
                  <input type="hidden" name="id" id="cat-id" value="">
                  <div class="form-group">
                    <label>Category Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="cat-name" class="form-control" required placeholder="e.g. Cables & Wires">
                  </div>
                  <button type="submit" class="btn btn-primary btn-block font-weight-bold"><i class="fas fa-save mr-1"></i> Save Category</button>
                  <button type="button" onclick="resetCatForm()" class="btn btn-link btn-block btn-sm text-muted">Clear</button>
                </form>
              </div>
            </div>
          </div>
          <div class="col-md-8">
            <table class="table table-bordered table-striped datatable-init">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Category Name</th>
                  <th>Slug</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php $i=1; foreach ($categories as $c): ?>
                  <tr>
                    <td><?= $i++ ?></td>
                    <td class="font-weight-bold"><?= esc($c->name) ?></td>
                    <td><code><?= esc($c->slug) ?></code></td>
                    <td>
                      <button onclick="editCat(<?= $c->id ?>, '<?= esc($c->name, 'js') ?>')" class="btn btn-xs btn-warning"><i class="fas fa-edit"></i> Edit</button>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Subcategories Tab -->
      <div class="tab-pane fade" id="content-subcat" role="tabpanel">
        <div class="row">
          <div class="col-md-4">
            <div class="card card-outline card-primary">
              <div class="card-header"><h3 class="card-title font-weight-bold">Add Subcategory</h3></div>
              <div class="card-body">
                <form id="form-add-subcat">
                  <input type="hidden" name="id" id="subcat-id" value="">
                  <div class="form-group">
                    <label>Parent Category <span class="text-danger">*</span></label>
                    <select name="category_id" id="subcat-parent" class="form-control select2" required>
                      <option value="">-- Select Category --</option>
                      <?php foreach ($categories as $c): ?>
                        <option value="<?= $c->id ?>"><?= esc($c->name) ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="form-group">
                    <label>Subcategory Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="subcat-name" class="form-control" required placeholder="e.g. Copper Wire">
                  </div>
                  <button type="submit" class="btn btn-primary btn-block font-weight-bold"><i class="fas fa-save mr-1"></i> Save Subcategory</button>
                  <button type="button" onclick="resetSubcatForm()" class="btn btn-link btn-block btn-sm text-muted">Clear</button>
                </form>
              </div>
            </div>
          </div>
          <div class="col-md-8">
            <table class="table table-bordered table-striped datatable-init">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Parent Category</th>
                  <th>Subcategory Name</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php $i=1; foreach ($subcategories as $sc): ?>
                  <tr>
                    <td><?= $i++ ?></td>
                    <td><span class="badge badge-info"><?= esc($sc->category_name) ?></span></td>
                    <td class="font-weight-bold"><?= esc($sc->name) ?></td>
                    <td>
                      <button onclick="editSubcat(<?= $sc->id ?>, <?= $sc->category_id ?>, '<?= esc($sc->name, 'js') ?>')" class="btn btn-xs btn-warning"><i class="fas fa-edit"></i> Edit</button>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Brands Tab -->
      <div class="tab-pane fade" id="content-brand" role="tabpanel">
        <div class="row">
          <div class="col-md-4">
            <div class="card card-outline card-primary">
              <div class="card-header"><h3 class="card-title font-weight-bold">Add Brand</h3></div>
              <div class="card-body">
                <form id="form-add-brand">
                  <input type="hidden" name="id" id="brand-id" value="">
                  <div class="form-group">
                    <label>Brand Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="brand-name" class="form-control" required placeholder="e.g. Philips">
                  </div>
                  <button type="submit" class="btn btn-primary btn-block font-weight-bold"><i class="fas fa-save mr-1"></i> Save Brand</button>
                  <button type="button" onclick="resetBrandForm()" class="btn btn-link btn-block btn-sm text-muted">Clear</button>
                </form>
              </div>
            </div>
          </div>
          <div class="col-md-8">
            <table class="table table-bordered table-striped datatable-init">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Brand Name</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php $i=1; foreach ($brands as $b): ?>
                  <tr>
                    <td><?= $i++ ?></td>
                    <td class="font-weight-bold"><?= esc($b->name) ?></td>
                    <td>
                      <button onclick="editBrand(<?= $b->id ?>, '<?= esc($b->name, 'js') ?>')" class="btn btn-xs btn-warning"><i class="fas fa-edit"></i> Edit</button>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Units Tab -->
      <div class="tab-pane fade" id="content-unit" role="tabpanel">
        <div class="row">
          <div class="col-md-4">
            <div class="card card-outline card-primary">
              <div class="card-header"><h3 class="card-title font-weight-bold">Add Unit</h3></div>
              <div class="card-body">
                <form id="form-add-unit">
                  <input type="hidden" name="id" id="unit-id" value="">
                  <div class="form-group">
                    <label>Unit Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="unit-name" class="form-control" required placeholder="e.g. Metre">
                  </div>
                  <div class="form-group">
                    <label>Abbreviation <span class="text-danger">*</span></label>
                    <input type="text" name="abbreviation" id="unit-abbr" class="form-control" required placeholder="e.g. m">
                  </div>
                  <button type="submit" class="btn btn-primary btn-block font-weight-bold"><i class="fas fa-save mr-1"></i> Save Unit</button>
                  <button type="button" onclick="resetUnitForm()" class="btn btn-link btn-block btn-sm text-muted">Clear</button>
                </form>
              </div>
            </div>
          </div>
          <div class="col-md-8">
            <table class="table table-bordered table-striped datatable-init">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Unit Name</th>
                  <th>Abbreviation</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php $i=1; foreach ($units as $u): ?>
                  <tr>
                    <td><?= $i++ ?></td>
                    <td class="font-weight-bold"><?= esc($u->name) ?></td>
                    <td><span class="badge badge-secondary"><?= esc($u->abbreviation) ?></span></td>
                    <td>
                      <button onclick="editUnit(<?= $u->id ?>, '<?= esc($u->name, 'js') ?>', '<?= esc($u->abbreviation, 'js') ?>')" class="btn btn-xs btn-warning"><i class="fas fa-edit"></i> Edit</button>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

<script>
  function editCat(id, name) { $('#cat-id').val(id); $('#cat-name').val(name); }
  function resetCatForm() { $('#cat-id').val(''); $('#cat-name').val(''); }

  function editSubcat(id, parentId, name) { $('#subcat-id').val(id); $('#subcat-parent').val(parentId).trigger('change'); $('#subcat-name').val(name); }
  function resetSubcatForm() { $('#subcat-id').val(''); $('#subcat-name').val(''); }

  function editBrand(id, name) { $('#brand-id').val(id); $('#brand-name').val(name); }
  function resetBrandForm() { $('#brand-id').val(''); $('#brand-name').val(''); }

  function editUnit(id, name, abbr) { $('#unit-id').val(id); $('#unit-name').val(name); $('#unit-abbr').val(abbr); }
  function resetUnitForm() { $('#unit-id').val(''); $('#unit-name').val(''); $('#unit-abbr').val(''); }

  $('#form-add-category').on('submit', function(e) {
    e.preventDefault();
    $.post('<?= base_url('inventory/categories/save-category') ?>', $(this).serialize(), function(res) {
      if (res.status === 'success') { location.reload(); } else { alert(res.message); }
    });
  });

  $('#form-add-subcat').on('submit', function(e) {
    e.preventDefault();
    $.post('<?= base_url('inventory/categories/save-subcategory') ?>', $(this).serialize(), function(res) {
      if (res.status === 'success') { location.reload(); } else { alert(res.message); }
    });
  });

  $('#form-add-brand').on('submit', function(e) {
    e.preventDefault();
    $.post('<?= base_url('inventory/categories/save-brand') ?>', $(this).serialize(), function(res) {
      if (res.status === 'success') { location.reload(); } else { alert(res.message); }
    });
  });

  $('#form-add-unit').on('submit', function(e) {
    e.preventDefault();
    $.post('<?= base_url('inventory/categories/save-unit') ?>', $(this).serialize(), function(res) {
      if (res.status === 'success') { location.reload(); } else { alert(res.message); }
    });
  });
</script>
