<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $__as = (new Setting())->all(); ?>
    <title><?= e($__as['school_name'] ?? 'UNILAK') ?> &mdash; <?= e($__as['campus_name'] ?? 'Nyanza Campus') ?> &mdash; <?= e($__as['app_name'] ?? 'SIMS') ?></title>
    <?php if (!empty($__as['favicon_path'])): ?>
        <link rel="icon" href="<?= e(url($__as['favicon_path'])) ?>">
    <?php endif; ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="<?= e(url('assets/css/app.css')) ?>" rel="stylesheet">
    <style>:root{--brand:<?= e($__as['primary_color']??'#1f6feb') ?>;--accent:<?= e($__as['accent_color']??'#0f766e') ?>;}</style>
</head>
<body class="auth-body">
<div class="auth-split">

    <!-- LEFT: Campus photo panel -->
    <div class="auth-photo-panel">
        <!--
            TO ADD CAMPUS PHOTO:
            Replace the class "auth-photo-placeholder" div below with:
            <img src="<?= e(url('assets/img/campus.jpg')) ?>" class="auth-bg-img" alt="UNILAK Nyanza Campus">
        -->
        <div class="auth-photo-placeholder"></div>
        <div class="auth-photo-overlay"></div>
        <div class="auth-photo-content">
            <?php
            $__al = $__as['logo_path'] ?? '';
            $__sn = $__as['school_name'] ?? 'UNILAK';
            $__cn = $__as['campus_name'] ?? 'Nyanza Campus';
            $__country = $__as['school_country'] ?? 'Rwanda';
            $__tagline = $__as['site_tagline'] ?? 'Student Information Management System';
            ?>
            <?php if ($__al): ?>
                <img src="<?= e(url($__al)) ?>" alt="logo"
                     style="height:64px;width:auto;border-radius:14px;margin-bottom:20px;display:block">
            <?php else: ?>
                <div class="school-logo-placeholder auth-logo">
                    <span><?= e(strtoupper(substr($__sn,0,1))) ?></span>
                </div>
            <?php endif; ?>
            <h1 class="auth-school-name"><?= e($__sn) ?></h1>
            <p class="auth-school-sub"><?= e($__cn) ?> &mdash; <?= e($__country) ?></p>
            <p class="auth-school-desc"><?= e($__tagline) ?></p>
            <div class="auth-photo-badges">
                <span><i class="bi bi-shield-check me-1"></i>Secure</span>
                <span><i class="bi bi-bell me-1"></i>Real-time</span>
                <span><i class="bi bi-people me-1"></i>SR Support</span>
            </div>
        </div>
        <a href="<?= e(url('/')) ?>" class="auth-back-link">
            <i class="bi bi-arrow-left me-1"></i>Back to Home
        </a>
    </div>

    <!-- RIGHT: Form panel -->
    <div class="auth-form-panel">
        <div class="auth-form-inner">
            <?php require APP_PATH . '/views/layouts/flash.php'; ?>
            <?= $content ?>
        </div>
    </div>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= e(url('assets/js/app.js')) ?>"></script>
</body>
</html>
