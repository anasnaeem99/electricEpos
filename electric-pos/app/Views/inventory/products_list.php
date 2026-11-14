<div class="row mb-2">
  <div class="col-sm-6">
    <h1 class="m-0 text-dark font-weight-bold"><i class="fas fa-boxes mr-2 text-primary"></i> Product Catalog</h1>
  </div>
  <div class="col-sm-6 text-right">
    <a href="<?= base_url('inventory/products/add') ?>" class="btn btn-primary shadow-sm font-weight-bold">
      <i class="fas fa-plus mr-1"></i> Add New Product
    </a>
    <a href="<?= base_url('inventory/categories') ?>" class="btn btn-outline-secondary shadow-sm ml-1">
      <i class="fas fa-tags mr-1"></i> Categories & Brands
    </a>
  </div>
</div>

<?php if (session()->getFlashdata('success')): ?>
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle mr-1"></i> <?= session()->getFlashdata('success') ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fas fa-exclamation-triangle mr-1"></i> <?= session()->getFlashdata('error') ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
<?php endif; ?>

<!-- Filter Card -->
<div class="card card-outline card-secondary shadow-sm mb-3">
  <div class="card-body p-3">
    <form method="get" action="<?= base_url('inventory/products') ?>" class="row align-items-center">
      <div class="col-md-4 mb-2 mb-md-0">
        <label class="small text-muted font-weight-bold">Filter by Category</label>
        <select name="category_id" class="form-control form-control-sm select2">
          <option value="">-- All Categories --</option>
          <?php foreach ($categories as $cat): ?>
            <option value="<?= $cat->id ?>" <?= ($filters['category_id'] == $cat->id) ? 'selected' : '' ?>><?= esc($cat->name) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="col-md-4 mb-2 mb-md-0">
        <label class="small text-muted font-weight-bold">Filter by Brand</label>
        <select name="brand_id" class="form-control form-control-sm select2">
          <option value="">-- All Brands --</option>
          <?php foreach ($brands as $br): ?>
            <option value="<?= $br->id ?>" <?= ($filters['brand_id'] == $br->id) ? 'selected' : '' ?>><?= esc($br->name) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="col-md-4 pt-md-4">
        <button type="submit" class="btn btn-secondary btn-sm"><i class="fas fa-filter mr-1"></i> Apply Filter</button>
        <a href="<?= base_url('inventory/products') ?>" class="btn btn-link btn-sm text-muted">Reset</a>
      </div>
    </form>
  </div>
</div>

<!-- Products Table -->
<div class="card shadow-sm">
  <div class="card-body p-0 table-responsive">
    <table class="table table-hover table-striped datatable-init mb-0">
      <thead class="thead-light">
        <tr>
          <th width="4%">#</th>
          <th width="8%">Image</th>
          <th>SKU / Code</th>
          <th>Product Name</th>
          <th>Category</th>
          <th>Brand</th>
          <th>Retail Price</th>
          <th>Cost</th>
          <th>Tracking</th>
          <th>Status</th>
          <th width="12%" class="text-center">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($products)): ?>
          <?php $i = 1; foreach ($products as $p): ?>
            <tr>
              <td><?= $i++ ?></td>
              <td>
                <?php if (!empty($p->image)): ?>
                  <img src="<?= base_url($p->image) ?>" alt="img" class="img-thumbnail" style="height: 40px; width: 40px; object-fit: cover;">
                <?php else: ?>
                  <div class="bg-light text-muted text-center rounded border d-flex align-items-center justify-content-center" style="height: 40px; width: 40px;">
                    <i class="fas fa-image"></i>
                  </div>
                <?php endif; ?>
              </td>
              <td>
                <span class="font-weight-bold text-dark"><?= esc($p->sku) ?></span>
                <?php if (!empty($p->internal_code)): ?>
                  <br><small class="text-muted"><?= esc($p->internal_code) ?></small>
                <?php endif; ?>
              </td>
              <td>
                <a href="<?= base_url('inventory/products/view/' . $p->id) ?>" class="font-weight-bold text-primary">
                  <?= esc($p->name) ?>
                </a>
              </td>
              <td><?= esc($p->category_name ?? '-') ?></td>
              <td><?= esc($p->brand_name ?? '-') ?></td>
              <td class="font-weight-bold text-success">Rs. <?= number_format($p->retail_price, 2) ?></td>
              <td class="text-muted">Rs. <?= number_format($p->purchase_price, 2) ?></td>
              <td>
                <?php if ($p->batch_tracking): ?>
                  <span class="badge badge-info mr-1" title="Batch Tracked">Batch</span>
                <?php endif; ?>
                <?php if ($p->expiry_tracking): ?>
                  <span class="badge badge-warning" title="Expiry Tracked">Expiry</span>
                <?php endif; ?>
                <?php if ($p->is_weighing): ?>
                  <span class="badge badge-secondary" title="Scale Weighing">Scale</span>
                <?php endif; ?>
                <?php if (!$p->batch_tracking && !$p->expiry_tracking && !$p->is_weighing): ?>
                  <span class="text-muted small">Standard</span>
                <?php endif; ?>
              </td>
              <td>
                <?php if ($p->is_active): ?>
                  <span class="badge badge-success">Active</span>
                <?php else: ?>
                  <span class="badge badge-danger">Inactive</span>
                <?php endif; ?>
              </td>
              <td class="text-center">
                <a href="<?= base_url('inventory/products/view/' . $p->id) ?>" class="btn btn-xs btn-info" title="View Stock & Details">
                  <i class="fas fa-eye"></i>
                </a>
                <a href="<?= base_url('inventory/products/edit/' . $p->id) ?>" class="btn btn-xs btn-warning" title="Edit">
                  <i class="fas fa-edit"></i>
                </a>
                <button onclick="confirmArchive(<?= $p->id ?>)" class="btn btn-xs btn-danger" title="Archive">
                  <i class="fas fa-trash"></i>
                </button>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<script>
  function confirmArchive(id) {
    Swal.fire({
      title: 'Archive product?',
      text: "This product will become inactive and won't appear in POS search.",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#6c757d',
      confirmButtonText: 'Yes, archive'
    }).then((result) => {
      if (result.isConfirmed) {
        window.location.href = '<?= base_url('inventory/products/delete/') ?>/' + id;
      }
    });
  }
</script>
