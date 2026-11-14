<div class="row mb-2">
  <div class="col-sm-6">
    <h1 class="m-0 text-dark font-weight-bold"><i class="fas fa-truck-loading mr-2 text-primary"></i> Goods Received Notes (GRN)</h1>
  </div>
  <div class="col-sm-6 text-right">
    <a href="<?= base_url('purchase/grn/create') ?>" class="btn btn-primary shadow-sm font-weight-bold">
      <i class="fas fa-plus mr-1"></i> New Goods Receipt
    </a>
  </div>
</div>

<?php if (session()->getFlashdata('success')): ?>
  <div class="alert alert-success alert-dismissible fade show">
    <i class="fas fa-check-circle mr-1"></i> <?= session()->getFlashdata('success') ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
<?php endif; ?>

<div class="card shadow-sm">
  <div class="card-body p-0 table-responsive">
    <table class="table table-hover table-striped datatable-init mb-0">
      <thead class="thead-light">
        <tr>
          <th>GRN Number</th>
          <th>GRN Date</th>
          <th>Supplier</th>
          <th>Destination Warehouse</th>
          <th>Total Amount</th>
          <th>Status</th>
          <th width="12%" class="text-center">Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($grnList)): ?>
          <?php foreach ($grnList as $g): ?>
            <tr>
              <td>
                <a href="<?= base_url('purchase/grn/view/' . $g->id) ?>" class="font-weight-bold text-primary">
                  <?= esc($g->grn_no) ?>
                </a>
              </td>
              <td><?= date('d M Y', strtotime($g->grn_date)) ?></td>
              <td class="font-weight-bold"><?= esc($g->supplier_name) ?></td>
              <td><?= esc($g->warehouse_name) ?></td>
              <td class="font-weight-bold">Rs. <?= number_format($g->total_amount, 2) ?></td>
              <td>
                <?php if ($g->status == 'posted'): ?>
                  <span class="badge badge-success"><i class="fas fa-check-double mr-1"></i> Posted (Stock In)</span>
                <?php else: ?>
                  <span class="badge badge-warning">Draft (Not in stock yet)</span>
                <?php endif; ?>
              </td>
              <td class="text-center">
                <a href="<?= base_url('purchase/grn/view/' . $g->id) ?>" class="btn btn-xs btn-info">
                  <i class="fas fa-eye"></i> View
                </a>
                <?php if ($g->status == 'draft'): ?>
                  <a href="<?= base_url('purchase/grn/post/' . $g->id) ?>" class="btn btn-xs btn-success" onclick="return confirm('Post this GRN? Stock will be updated immediately.')">
                    <i class="fas fa-check"></i> Post
                  </a>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
