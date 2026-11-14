<div class="row mb-2">
  <div class="col-sm-6">
    <h1 class="m-0 text-dark font-weight-bold">
      <?= isset($supplier) ? 'Edit Supplier' : 'Add New Supplier' ?>
    </h1>
  </div>
  <div class="col-sm-6 text-right">
    <a href="<?= base_url('purchase/suppliers') ?>" class="btn btn-secondary shadow-sm font-weight-bold">
      <i class="fas fa-arrow-left mr-1"></i> Back to Suppliers
    </a>
  </div>
</div>

<div class="card card-outline card-primary shadow-sm" style="max-width: 800px;">
  <div class="card-body">
    <form method="post" action="<?= current_url() ?>">
      <?= csrf_field() ?>

      <div class="row">
        <div class="col-md-8">
          <div class="form-group">
            <label>Supplier / Company Name <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" value="<?= old('name', $supplier['name'] ?? '') ?>" required placeholder="e.g. Siemens Pakistan">
          </div>
        </div>

        <div class="col-md-4">
          <div class="form-group">
            <label>Supplier Code</label>
            <input type="text" name="code" class="form-control" value="<?= old('code', $supplier['code'] ?? '') ?>" placeholder="Auto-generated if blank">
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label>Contact Person</label>
            <input type="text" name="contact_person" class="form-control" value="<?= old('contact_person', $supplier['contact_person'] ?? '') ?>">
          </div>
        </div>

        <div class="col-md-6">
          <div class="form-group">
            <label>Phone Number</label>
            <input type="text" name="phone" class="form-control" value="<?= old('phone', $supplier['phone'] ?? '') ?>">
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label>Email Address</label>
            <input type="email" name="email" class="form-control" value="<?= old('email', $supplier['email'] ?? '') ?>">
          </div>
        </div>

        <div class="col-md-6">
          <div class="form-group">
            <label>NTN / Tax Registration Number</label>
            <input type="text" name="ntn" class="form-control" value="<?= old('ntn', $supplier['ntn'] ?? '') ?>">
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label>Payment Terms</label>
            <input type="text" name="payment_terms" class="form-control" value="<?= old('payment_terms', $supplier['payment_terms'] ?? '30 Days Net') ?>" placeholder="e.g. 15 Days, Cash on delivery">
          </div>
        </div>

        <?php if (!isset($supplier)): ?>
          <div class="col-md-6">
            <div class="form-group">
              <label>Opening Payable Balance (Rs.)</label>
              <input type="number" step="0.01" name="opening_balance" class="form-control" value="0.00">
              <small class="text-muted">Existing amount owed to supplier before starting EPOS.</small>
            </div>
          </div>
        <?php endif; ?>
      </div>

      <div class="mt-3">
        <button type="submit" class="btn btn-primary font-weight-bold px-4 shadow-sm"><i class="fas fa-save mr-1"></i> Save Supplier</button>
        <a href="<?= base_url('purchase/suppliers') ?>" class="btn btn-outline-secondary ml-2">Cancel</a>
      </div>
    </form>
  </div>
</div>
