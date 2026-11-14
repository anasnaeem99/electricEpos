<div class="row justify-content-center pt-4">
  <div class="col-md-6 col-lg-5">
    <div class="card card-outline card-primary shadow-lg">
      <div class="card-header text-center bg-primary text-white py-3">
        <h4 class="font-weight-bold m-0"><i class="fas fa-cash-register mr-2"></i> Open Cashier Register Shift</h4>
      </div>
      <div class="card-body p-4">
        <p class="text-muted text-center mb-4">Count your physical cash drawer float before starting sales.</p>

        <form method="post" action="<?= current_url() ?>">
          <?= csrf_field() ?>

          <div class="form-group mb-3">
            <label class="font-weight-bold">Select Register / Counter</label>
            <select name="register_id" class="form-control select2">
              <?php foreach ($registers as $reg): ?>
                <option value="<?= $reg->id ?>"><?= esc($reg->name) ?> (<?= esc($reg->code) ?>)</option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-group mb-4">
            <label class="font-weight-bold">Opening Cash Float Amount (Rs.) <span class="text-danger">*</span></label>
            <div class="input-group input-group-lg">
              <div class="input-group-prepend">
                <span class="input-group-text font-weight-bold">Rs.</span>
              </div>
              <input type="number" step="0.01" name="opening_cash" class="form-control font-weight-bold text-center" value="1000.00" required autofocus>
            </div>
            <small class="text-muted">Enter change money present in cash drawer.</small>
          </div>

          <button type="submit" class="btn btn-primary btn-block font-weight-bold py-3 shadow">
            <i class="fas fa-play mr-1"></i> Start Shift & Launch POS
          </button>
        </form>
      </div>
    </div>
  </div>
</div>
