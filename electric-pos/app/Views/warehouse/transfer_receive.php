<div class="row mb-2">
  <div class="col-sm-6">
    <h1 class="m-0 text-dark font-weight-bold">
      Receive Transfer: <?= esc($trf->transfer_no) ?>
    </h1>
    <span class="badge badge-warning">From: <?= esc($trf->from_store_name) ?> &raquo; <?= esc($trf->from_wh_name) ?></span>
    <span class="badge badge-success">To: <?= esc($trf->to_store_name) ?> &raquo; <?= esc($trf->to_wh_name) ?></span>
  </div>
  <div class="col-sm-6 text-right">
    <a href="<?= base_url('warehouse/transfers') ?>" class="btn btn-secondary shadow-sm font-weight-bold">
      <i class="fas fa-arrow-left mr-1"></i> Back to List
    </a>
  </div>
</div>

<form method="post" action="<?= current_url() ?>">
  <?= csrf_field() ?>

  <div class="card card-outline card-success shadow-sm">
    <div class="card-header">
      <h3 class="card-title font-weight-bold"><i class="fas fa-dolly mr-1"></i> Verify Received Stock & Putaway Shelf</h3>
    </div>
    <div class="card-body p-0 table-responsive">
      <table class="table table-bordered mb-0">
        <thead class="thead-light">
          <tr>
            <th>Product Name</th>
            <th class="text-right">Dispatched Qty</th>
            <th width="25%">Putaway Location (Shelf / Bin) <span class="text-danger">*</span></th>
            <th width="15%" class="text-right">Good Received Qty <span class="text-danger">*</span></th>
            <th width="15%" class="text-right">Damaged Qty</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($trf->items as $it): ?>
            <tr>
              <td>
                <span class="font-weight-bold"><?= esc($it->product_name) ?></span>
                <br><small class="text-muted"><code><?= esc($it->product_sku) ?></code></small>
              </td>
              <td class="text-right font-weight-bold"><?= number_format($it->dispatched_qty, 3) ?> <?= esc($it->unit_name ?? '') ?></td>
              <td>
                <select name="items[<?= $it->id ?>][location_id]" class="form-control form-control-sm select2" required>
                  <?php foreach ($locations as $loc): ?>
                    <option value="<?= $loc->id ?>"><?= esc($loc->name) ?> (<?= ucfirst($loc->location_type) ?>)</option>
                  <?php endforeach; ?>
                </select>
              </td>
              <td>
                <input type="number" step="0.001" min="0" max="<?= $it->dispatched_qty ?>" name="items[<?= $it->id ?>][received_qty]" class="form-control form-control-sm text-right font-weight-bold text-success" value="<?= $it->dispatched_qty ?>" required>
              </td>
              <td>
                <input type="number" step="0.001" min="0" name="items[<?= $it->id ?>][damage_qty]" class="form-control form-control-sm text-right text-danger" value="0.000">
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <div class="card-footer bg-white">
      <div class="form-group mb-3">
        <label>Receiving Inspection Note</label>
        <textarea name="note" class="form-control" rows="2" placeholder="Note any box damage, seal status, or remarks..."></textarea>
      </div>

      <button type="submit" class="btn btn-success font-weight-bold px-4 shadow-sm">
        <i class="fas fa-check-double mr-1"></i> Confirm & Accept Transfer Into Stock
      </button>
    </div>
  </div>
</form>
