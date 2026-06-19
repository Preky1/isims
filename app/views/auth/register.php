<div class="text-center mb-4">
    <h2 class="auth-form-title">Student Registration</h2>
    <p class="text-secondary">Create your SIMS account &mdash; leaders are added by admins</p>
</div>

<form method="post" action="<?= e(url('/register')) ?>" class="vstack gap-3">
    <?= csrf_field() ?>
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label fw-semibold">Full Name</label>
            <input class="form-control" name="name" placeholder="Your full name" required autofocus>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold">Student Number</label>
            <input class="form-control" name="student_number" placeholder="e.g. STU-2024-001">
        </div>
        <div class="col-12">
            <label class="form-label fw-semibold">Email Address</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                <input class="form-control" type="email" name="email" placeholder="you@unilak.ac.rw" required>
            </div>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold">Phone</label>
            <input class="form-control" name="phone" placeholder="+250 7XX XXX XXX">
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold">Department</label>
            <select class="form-select" name="department_id">
                <option value="">Select department</option>
                <?php foreach ($departments as $department): ?>
                    <option value="<?= (int) $department['id'] ?>"><?= e($department['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold">Password</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                <input class="form-control" type="password" name="password" placeholder="Min 8 characters" required>
            </div>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold">Confirm Password</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                <input class="form-control" type="password" name="password_confirmation" placeholder="Repeat password" required>
            </div>
        </div>
    </div>
    <button class="btn btn-primary w-100 py-2 mt-1">
        <i class="bi bi-person-check me-2"></i>Create Account
    </button>
</form>

<hr class="my-4">
<p class="text-center text-secondary mb-0">
    Already registered? <a href="<?= e(url('/login')) ?>" class="fw-semibold">Login here</a>
</p>
<p class="text-center mt-2 mb-0">
    <a href="<?= e(url('/')) ?>" class="text-secondary small">
        <i class="bi bi-arrow-left me-1"></i>Back to home
    </a>
</p>
