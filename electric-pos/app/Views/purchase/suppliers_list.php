<div class="row mb-2">
  <div class="col-sm-6">
    <h1 class="m-0 text-dark font-weight-bold"><i class="fas fa-truck mr-2 text-primary"></i> Suppliers Management</h1>
  </div>
  <div class="col-sm-6 text-right">
    <a href="<?= base_url('purchase/suppliers/add') ?>" class="btn btn-primary shadow-sm font-weight-bold">
      <i class="fas fa-plus mr-1"></i> Add Supplier
    </a>
  </div>
</div>

<?php if (session()->getFlashdata('success')): ?>
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle mr-1"></i> <?= session()->getFlashdata('success') ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
<?php endif; ?>

<div class="card shadow-sm">
  <div class="card-body p-0 table-responsive">
    <table class="table table-hover table-striped datatable-init mb-0">
      <thead class="thead-light">
        <tr>
          <th>Code</th>
          <th>Supplier Name</th>
          <th>Contact Person</th>
          <th>Phone</th>
          <th>NTN / Tax ID</th>
          <th>Outstanding Payable</th>
          <th width="12%" class="text-center">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($suppliers)): ?>
          <?php foreach ($suppliers as $s): ?>
            <tr>
              <td><code><?= esc($s->code) ?></code></td>
              <td>
                <a href="<?= base_url('purchase/suppliers/view/' . $s->id) ?>" class="font-weight-bold text-primary">
                  <?= esc($s->name) ?>
                </a>
              </td>
              <td><?= esc($s->contact_person ?? '-') ?></td>
              <td><?= esc($s->phone ?? '-') ?></td>
              <td><?= esc($s->ntn ?? '-') ?></td>
              <td class="font-weight-bold <?= ($s->current_balance > 0) ? 'text-danger' : 'text-success' ?>">
                Rs. <?= number_format($s->current_balance, 2) ?>
              </td>
              <td class="text-center">
                <a href="<?= base_url('purchase/suppliers/view/' . $s->id) ?>" class="btn btn-xs btn-info" title="View Ledger">
                  <i class="fas fa-file-invoice-dollar"></i> Ledger
                </a>
                <a href="<?= base_url('purchase/suppliers/edit/' . $s->id) ?>" class="btn btn-xs btn-warning" title="Edit">
                  <i class="fas fa-edit"></i>
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
