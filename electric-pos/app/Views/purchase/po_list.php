<div class="row mb-2">
  <div class="col-sm-6">
    <h1 class="m-0 text-dark font-weight-bold"><i class="fas fa-shopping-cart mr-2 text-primary"></i> Purchase Orders</h1>
  </div>
  <div class="col-sm-6 text-right">
    <a href="<?= base_url('purchase/po/create') ?>" class="btn btn-primary shadow-sm font-weight-bold">
      <i class="fas fa-plus mr-1"></i> New Purchase Order
    </a>
    <a href="<?= base_url('purchase/grn') ?>" class="btn btn-success shadow-sm ml-1 font-weight-bold">
      <i class="fas fa-truck-loading mr-1"></i> Goods Receipt (GRN)
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
          <th>PO Number</th>
          <th>PO Date</th>
          <th>Supplier</th>
          <th>Destination Store</th>
          <th>Total Amount</th>
          <th>Status</th>
          <th width="14%" class="text-center">Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($purchaseOrders)): ?>
          <?php foreach ($purchaseOrders as $po): ?>
            <tr>
              <td>
                <a href="<?= base_url('purchase/po/view/' . $po->id) ?>" class="font-weight-bold text-primary">
                  <?= esc($po->po_no) ?>
                </a>
              </td>
              <td><?= date('d M Y', strtotime($po->po_date)) ?></td>
              <td class="font-weight-bold"><?= esc($po->supplier_name) ?></td>
              <td><?= esc($po->store_name) ?></td>
              <td class="font-weight-bold">Rs. <?= number_format($po->total_amount, 2) ?></td>
              <td>
                <?php if ($po->status == 'pending'): ?>
                  <span class="badge badge-warning">Pending Approval</span>
                <?php elseif ($po->status == 'approved'): ?>
                  <span class="badge badge-info">Approved</span>
                <?php elseif ($po->status == 'received'): ?>
                  <span class="badge badge-success">Received (GRN Posted)</span>
                <?php else: ?>
                  <span class="badge badge-secondary"><?= ucfirst($po->status) ?></span>
                <?php endif; ?>
              </td>
              <td class="text-center">
                <a href="<?= base_url('purchase/po/view/' . $po->id) ?>" class="btn btn-xs btn-info" title="View Detail">
                  <i class="fas fa-eye"></i> View
                </a>
                <?php if ($po->status == 'approved'): ?>
                  <a href="<?= base_url('purchase/grn/create?po_id=' . $po->id) ?>" class="btn btn-xs btn-success" title="Receive Goods">
                    <i class="fas fa-dolly"></i> Receive
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
