<?php $u = $user ?? auth_user(); ?>

<div class="page-heading">
    <h1><i class="bi bi-person-circle me-2 text-primary"></i>My Profile</h1>
    <p>Update your admin name, email, phone number, and change your login password.</p>
</div>

<div class="row g-4">

    <div class="col-lg-6">
        <div class="settings-card">
            <div class="settings-card-header"><i class="bi bi-person me-2"></i>Profile Information</div>
            <div class="settings-card-body">
                <form method="post" action="<?= e(url('/cms/profile')) ?>">
                    <?= csrf_field() ?>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold">Full Name</label>
                            <input class="form-control" name="name"
                                   value="<?= e($u['name'] ?? '') ?>" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Email Address</label>
                            <input class="form-control" type="email" name="email"
                                   value="<?= e($u['email'] ?? '') ?>" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Phone</label>
                            <input class="form-control" name="phone"
                                   value="<?= e($u['phone'] ?? '') ?>">
                        </div>
                        <div class="col-12">
                            <span class="badge bg-danger me-1">
                                <?= e($u['role_name'] ?? '') ?>
                            </span>
                            <span class="text-muted small">Role cannot be changed here.</span>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-primary px-4">
                                <i class="bi bi-check-lg me-1"></i>Update Profile
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="settings-card">
            <div class="settings-card-header"><i class="bi bi-shield-lock me-2"></i>Change Password</div>
            <div class="settings-card-body">
                <form method="post" action="<?= e(url('/cms/password')) ?>">
                    <?= csrf_field() ?>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold">Current Password</label>
                            <input class="form-control" type="password" name="current_password" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">New Password</label>
                            <input class="form-control" type="password" name="new_password"
                                   minlength="8" required placeholder="At least 8 characters">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Confirm New Password</label>
                            <input class="form-control" type="password" name="confirm_password"
                                   minlength="8" required>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-warning px-4">
                                <i class="bi bi-key me-1"></i>Change Password
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
