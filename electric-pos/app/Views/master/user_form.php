<div class="row mb-2">
  <div class="col-sm-6">
    <h1 class="m-0 text-dark font-weight-bold">
      <?= isset($user) ? 'Edit User: ' . esc($user->full_name) : 'Create New System User' ?>
    </h1>
  </div>
  <div class="col-sm-6 text-right">
    <a href="<?= base_url('master/users') ?>" class="btn btn-secondary shadow-sm font-weight-bold">
      <i class="fas fa-arrow-left mr-1"></i> Back to Users
    </a>
  </div>
</div>

<div class="card card-outline card-primary shadow-sm" style="max-width: 850px;">
  <div class="card-body">
    <form method="post" action="<?= current_url() ?>">
      <?= csrf_field() ?>

      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label>Full Name <span class="text-danger">*</span></label>
            <input type="text" name="full_name" class="form-control" value="<?= old('full_name', $user->full_name ?? '') ?>" required placeholder="e.g. Asad Mehmood">
          </div>
        </div>

        <div class="col-md-6">
          <div class="form-group">
            <label>Username <span class="text-danger">*</span></label>
            <input type="text" name="username" class="form-control" value="<?= old('username', $user->username ?? '') ?>" <?= isset($user) ? 'readonly' : 'required' ?> placeholder="Login username">
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label>Role / Designation <span class="text-danger">*</span></label>
            <select name="role_id" class="form-control select2" required>
              <?php foreach ($roles as $r): ?>
                <option value="<?= $r->id ?>" <?= (isset($user) && $user->role_id == $r->id) ? 'selected' : '' ?>>
                  <?= esc($r->name) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="col-md-6">
          <div class="form-group">
            <label>Password <?= isset($user) ? '<small class="text-muted">(leave blank to keep current)</small>' : '<span class="text-danger">*</span>' ?></label>
            <input type="password" name="password" class="form-control" <?= isset($user) ? '' : 'required' ?> placeholder="Password">
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="<?= old('email', $user->email ?? '') ?>">
          </div>
        </div>

        <div class="col-md-6">
          <div class="form-group">
            <label>Phone Number</label>
            <input type="text" name="phone" class="form-control" value="<?= old('phone', $user->phone ?? '') ?>">
          </div>
        </div>
      </div>

      <div class="form-group">
        <label class="font-weight-bold">Store Branch Access Authorization <span class="text-danger">*</span></label>
        <div class="border rounded p-3 bg-light">
          <?php foreach ($stores as $st): ?>
            <div class="custom-control custom-checkbox custom-control-inline">
              <input type="checkbox" class="custom-control-input" id="st_<?= $st->id ?>" name="store_ids[]" value="<?= $st->id ?>" <?= (isset($assignedStoreIds) && in_array($st->id, $assignedStoreIds)) || !isset($user) ? 'checked' : '' ?>>
              <label class="custom-control-label" for="st_<?= $st->id ?>"><?= esc($st->name) ?> (<?= esc($st->code) ?>)</label>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <?php if (isset($user)): ?>
        <div class="form-group">
          <label>Account Status</label>
          <select name="is_active" class="form-control col-md-4">
            <option value="1" <?= $user->is_active ? 'selected' : '' ?>>Active</option>
            <option value="0" <?= !$user->is_active ? 'selected' : '' ?>>Suspended / Inactive</option>
          </select>
        </div>
      <?php endif; ?>

      <button type="submit" class="btn btn-primary font-weight-bold px-4 shadow-sm mt-2">
        <i class="fas fa-save mr-1"></i> Save User Record
      </button>
    </form>
  </div>
</div>
