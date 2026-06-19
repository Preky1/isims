<div class="page-heading">
    <h1>User Management</h1>
    <p>Create ISR leader accounts and manage the ISIMS community.</p>
</div>

<div class="form-panel mb-4">
    <h2 class="h5 mb-3">Create New Account</h2>
    <form method="post" action="<?= e(url('/admin/users')) ?>">
        <?= csrf_field() ?>
        <div class="row g-3">
            <div class="col-md-4">
                <input class="form-control" name="name" placeholder="Full name" required>
            </div>
            <div class="col-md-4">
                <input class="form-control" type="email" name="email" placeholder="Email" required>
            </div>
            <div class="col-md-4">
                <input class="form-control" type="password" name="password" placeholder="Temporary password" required>
            </div>
            <div class="col-md-3">
                <input class="form-control" name="position" placeholder="Position / title">
            </div>
            <div class="col-md-3">
                <input class="form-control" name="phone" placeholder="Phone">
            </div>
            <div class="col-md-3">
                <select class="form-select" name="department_id">
                    <option value="">No department</option>
                    <?php foreach ($departments as $department): ?>
                        <option value="<?= (int) $department['id'] ?>"><?= e($department['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php if (has_role('system_admin')): ?>
            <div class="col-md-3">
                <select class="form-select" name="role_slug">
                    <option value="isr_leader">ISR Leader</option>
                    <option value="leader_admin">Leader Admin</option>
                </select>
            </div>
            <?php endif; ?>
            <div class="col-12">
                <button class="btn btn-primary">
                    <i class="bi bi-person-plus me-1"></i>Create account
                </button>
            </div>
        </div>
    </form>
</div>

<div class="table-responsive table-panel">
    <table class="table align-middle mb-0">
        <thead class="table-light">
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Department</th>
                <th>Status</th>
                <th class="text-end">Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?= e($user['name']) ?></td>
                <td><?= e($user['email']) ?></td>
                <td>
                    <span class="badge text-bg-<?= match($user['role_slug']) {
                        'system_admin'  => 'danger',
                        'leader_admin'  => 'warning',
                        'isr_leader'    => 'primary',
                        default         => 'secondary'
                    } ?>">
                        <?= e($user['role_name']) ?>
                    </span>
                </td>
                <td><?= e($user['department_name'] ?? '&mdash;') ?></td>
                <td>
                    <span class="badge text-bg-<?= $user['status'] === 'active' ? 'success' : 'secondary' ?>">
                        <?= e($user['status']) ?>
                    </span>
                </td>
                <td class="text-end">
                    <form method="post" action="<?= e(url('/admin/users/toggle')) ?>" class="d-inline">
                        <?= csrf_field() ?>
                        <input type="hidden" name="id" value="<?= (int) $user['id'] ?>">
                        <button class="btn btn-sm btn-outline-secondary"
                                title="<?= $user['status'] === 'active' ? 'Deactivate' : 'Activate' ?>">
                            <i class="bi bi-<?= $user['status'] === 'active' ? 'person-dash' : 'person-check' ?>"></i>
                        </button>
                    </form>
                    <?php if (has_role('system_admin') && (int) $user['id'] !== (int) auth_user()['id']): ?>
                    <form method="post" action="<?= e(url('/admin/users/delete')) ?>" class="d-inline"
                          onsubmit="return confirm('Delete this user? This cannot be undone.')">
                        <?= csrf_field() ?>
                        <input type="hidden" name="id" value="<?= (int) $user['id'] ?>">
                        <button class="btn btn-sm btn-outline-danger" title="Delete">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
