<div class="row mb-2">
  <div class="col-sm-6">
    <h1 class="m-0 text-dark font-weight-bold"><i class="fas fa-sliders-h mr-2 text-primary"></i> Stock Adjustments</h1>
  </div>
  <div class="col-sm-6 text-right">
    <a href="<?= base_url('warehouse/adjustments/create') ?>" class="btn btn-warning shadow-sm font-weight-bold">
      <i class="fas fa-plus mr-1"></i> New Stock Adjustment
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
          <th>Adj #</th>
          <th>Date</th>
          <th>Warehouse</th>
          <th>Reason</th>
          <th>Created By</th>
          <th>Status</th>
          <th width="10%" class="text-center">Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($adjustments)): ?>
          <?php foreach ($adjustments as $a): ?>
            <tr>
              <td class="font-weight-bold text-dark"><?= esc($a->adj_no) ?></td>
              <td><?= date('d M Y, h:i A', strtotime($a->created_at)) ?></td>
              <td><?= esc($a->warehouse_name) ?></td>
              <td><?= esc($a->reason) ?></td>
              <td><?= esc($a->creator_name ?? 'Admin') ?></td>
              <td>
                <?php if ($a->status == 'posted'): ?>
                  <span class="badge badge-success"><i class="fas fa-check mr-1"></i> Posted</span>
                <?php else: ?>
                  <span class="badge badge-warning">Draft</span>
                <?php endif; ?>
              </td>
              <td class="text-center">
                <?php if ($a->status == 'draft'): ?>
                  <a href="<?= base_url('warehouse/adjustments/post/' . $a->id) ?>" class="btn btn-xs btn-success font-weight-bold" onclick="return confirm('Post this adjustment now?')">
                    Post
                  </a>
                <?php else: ?>
                  <span class="text-muted small">Closed</span>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
