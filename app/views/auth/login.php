<div class="text-center mb-4">
    <h2 class="auth-form-title">Welcome Back</h2>
    <p class="text-secondary">Sign in to your SIMS account</p>
</div>

<form method="post" action="<?= e(url('/login')) ?>" class="vstack gap-3">
    <?= csrf_field() ?>
    <div>
        <label class="form-label fw-semibold">Email Address</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
            <input class="form-control" type="email" name="email" placeholder="you@unilak.ac.rw" required autofocus>
        </div>
    </div>
    <div>
        <label class="form-label fw-semibold">Password</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-lock"></i></span>
            <input class="form-control" type="password" name="password" placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;" required>
        </div>
    </div>
    <button class="btn btn-primary w-100 py-2 mt-1">
        <i class="bi bi-box-arrow-in-right me-2"></i>Login
    </button>
</form>

<hr class="my-4">
<p class="text-center text-secondary mb-0">
    New international student?
    <a href="<?= e(url('/register')) ?>" class="fw-semibold">Create an account</a>
</p>
<p class="text-center mt-2 mb-0">
    <a href="<?= e(url('/')) ?>" class="text-secondary small">
        <i class="bi bi-arrow-left me-1"></i>Back to home
    </a>
</p>
