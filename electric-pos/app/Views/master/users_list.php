<div class="row mb-2">
  <div class="col-sm-6">
    <h1 class="m-0 text-dark font-weight-bold"><i class="fas fa-users-cog mr-2 text-primary"></i> System Users & Cashiers</h1>
  </div>
  <div class="col-sm-6 text-right">
    <a href="<?= base_url('master/users/add') ?>" class="btn btn-primary shadow-sm font-weight-bold">
      <i class="fas fa-user-plus mr-1"></i> Add System User
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
          <th>#</th>
          <th>Full Name</th>
          <th>Username</th>
          <th>Role</th>
          <th>Email</th>
          <th>Phone</th>
          <th>Status</th>
          <th>Last Login</th>
          <th width="10%" class="text-center">Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($users)): ?>
          <?php $i=1; foreach ($users as $u): ?>
            <tr>
              <td><?= $i++ ?></td>
              <td><b><?= esc($u->full_name) ?></b></td>
              <td><code><?= esc($u->username) ?></code></td>
              <td><span class="badge badge-info"><?= esc($u->role_name ?? 'User') ?></span></td>
              <td><?= esc($u->email ?? '-') ?></td>
              <td><?= esc($u->phone ?? '-') ?></td>
              <td>
                <?php if ($u->is_active): ?>
                  <span class="badge badge-success">Active</span>
                <?php else: ?>
                  <span class="badge badge-danger">Inactive</span>
                <?php endif; ?>
              </td>
              <td><?= !empty($u->last_login) ? date('d M Y, h:i A', strtotime($u->last_login)) : 'Never' ?></td>
              <td class="text-center">
                <a href="<?= base_url('master/users/edit/' . $u->id) ?>" class="btn btn-xs btn-warning font-weight-bold">
                  <i class="fas fa-edit"></i> Edit
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
