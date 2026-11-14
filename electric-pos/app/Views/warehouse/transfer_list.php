<div class="row mb-2">
  <div class="col-sm-6">
    <h1 class="m-0 text-dark font-weight-bold"><i class="fas fa-exchange-alt mr-2 text-primary"></i> Stock Transfers</h1>
  </div>
  <div class="col-sm-6 text-right">
    <a href="<?= base_url('warehouse/transfers/create') ?>" class="btn btn-primary shadow-sm font-weight-bold">
      <i class="fas fa-plus mr-1"></i> New Transfer
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
          <th>Transfer #</th>
          <th>From Warehouse</th>
          <th>Destination Warehouse</th>
          <th>Dispatched Date</th>
          <th>Received Date</th>
          <th>Status</th>
          <th width="15%" class="text-center">Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($transfers)): ?>
          <?php foreach ($transfers as $t): ?>
            <tr>
              <td class="font-weight-bold text-primary"><?= esc($t->transfer_no) ?></td>
              <td><?= esc($t->from_store_name) ?> &raquo; <?= esc($t->from_wh_name) ?></td>
              <td><?= esc($t->to_store_name) ?> &raquo; <?= esc($t->to_wh_name) ?></td>
              <td><?= !empty($t->dispatch_at) ? date('d M Y, h:i A', strtotime($t->dispatch_at)) : '-' ?></td>
              <td><?= !empty($t->received_at) ? date('d M Y, h:i A', strtotime($t->received_at)) : '-' ?></td>
              <td>
                <?php if ($t->status == 'draft'): ?>
                  <span class="badge badge-secondary">Draft</span>
                <?php elseif ($t->status == 'dispatched'): ?>
                  <span class="badge badge-warning"><i class="fas fa-shipping-fast mr-1"></i> In Transit</span>
                <?php elseif ($t->status == 'received'): ?>
                  <span class="badge badge-success"><i class="fas fa-check mr-1"></i> Received</span>
                <?php endif; ?>
              </td>
              <td class="text-center">
                <?php if ($t->status == 'draft'): ?>
                  <a href="<?= base_url('warehouse/transfers/dispatch/' . $t->id) ?>" class="btn btn-xs btn-warning font-weight-bold" onclick="return confirm('Dispatch transfer now? Stock will be deducted from source.')">
                    <i class="fas fa-truck mr-1"></i> Dispatch
                  </a>
                <?php elseif ($t->status == 'dispatched'): ?>
                  <a href="<?= base_url('warehouse/transfers/receive/' . $t->id) ?>" class="btn btn-xs btn-success font-weight-bold">
                    <i class="fas fa-dolly mr-1"></i> Receive Stock
                  </a>
                <?php else: ?>
                  <span class="text-muted small"><i class="fas fa-check-circle text-success"></i> Completed</span>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
