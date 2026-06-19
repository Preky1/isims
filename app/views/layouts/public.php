<?php
$s        = $settings ?? [];
$appName  = $s['app_name']  ?? 'ISIMS';
$schoolName = $s['school_name'] ?? 'UNILAK';
$campusName = $s['campus_name'] ?? 'Nyanza Campus';
$logoPath = !empty($s['logo_path']) ? $s['logo_path'] : '';
$favicon  = !empty($s['favicon_path']) ? url($s['favicon_path']) : '';
$primary  = $s['primary_color'] ?? '#1f6feb';
$accent   = $s['accent_color']  ?? '#0f766e';
$tagline  = $s['site_tagline']  ?? 'Student Information Management System';
$navItems = $headerMenus ?? [];
$footerData = $footer ?? [];
$fMenus   = $footerMenus ?? [];
$cmsMissing = empty($s) && empty($footerData);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($schoolName) ?> &mdash; <?= e($campusName) ?> &mdash; <?= e($appName) ?></title>
    <?php if ($favicon): ?>
        <link rel="icon" href="<?= e($favicon) ?>">
    <?php endif; ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="<?= e(url('assets/css/app.css')) ?>" rel="stylesheet">
    <style>
        :root {
            --brand: <?= e($primary) ?>;
            --brand-dark: <?= e($primary) ?>cc;
            --accent: <?= e($accent) ?>;
        }
        <?= $s['custom_css'] ?? '' ?>
    </style>
</head>
<body class="public-body">

<!-- ── Top Navigation ──────────────────────────────────────────────── -->
<nav class="public-nav">
    <div class="container d-flex align-items-center justify-content-between py-3">
        <a href="<?= e(url('/')) ?>" class="public-nav-brand">
            <?php if ($logoPath): ?>
                <img src="<?= e(url($logoPath)) ?>" alt="<?= e($schoolName) ?> logo"
                     style="height:44px;width:auto;border-radius:10px;">
            <?php else: ?>
                <div class="school-logo-placeholder">
                    <span><?= e(strtoupper(substr($schoolName, 0, 1))) ?></span>
                </div>
            <?php endif; ?>
            <div>
                <strong><?= e($schoolName) ?></strong>
                <small><?= e($campusName) ?> &mdash; <?= e($appName) ?></small>
            </div>
        </a>
        <div class="d-flex gap-2">
            <?php if ($navItems): ?>
                <?php foreach ($navItems as $item): ?>
                    <a href="<?= e(url(ltrim($item['url'],'/'))) ?>"
                       target="<?= e($item['target']) ?>"
                       class="btn btn-outline-light btn-sm">
                        <?php if ($item['icon']): ?>
                            <i class="bi <?= e($item['icon']) ?> me-1"></i>
                        <?php endif; ?>
                        <?= e($item['label']) ?>
                    </a>
                <?php endforeach; ?>
            <?php else: ?>
                <a href="<?= e(url('/login')) ?>" class="btn btn-outline-light btn-sm">
                    <i class="bi bi-box-arrow-in-right me-1"></i>Login
                </a>
                <a href="<?= e(url('/register')) ?>" class="btn btn-light btn-sm">
                    <i class="bi bi-person-plus me-1"></i>Register
                </a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<?php require APP_PATH . '/views/layouts/flash.php'; ?>

<?php if (!empty($cmsMissing)): ?>
<div style="background:#fef3c7;border-bottom:2px solid #f59e0b;padding:12px 0;text-align:center;font-size:14px;color:#92400e">
    <strong>&#9888; Setup required:</strong>
    CMS tables are missing. Please
    <a href="<?= e(url('/migrate.php')) ?>" style="color:#b45309;font-weight:700">run the migration</a>
    to finish setup.
</div>
<?php endif; ?>

<?= $content ?>

<!-- ── Footer ──────────────────────────────────────────────────────── -->
<footer class="public-footer">
    <div class="container">
        <?php
        $socials = [
            'social_facebook'  => ['bi-facebook',  'Facebook'],
            'social_twitter'   => ['bi-twitter-x', 'Twitter/X'],
            'social_instagram' => ['bi-instagram',  'Instagram'],
            'social_linkedin'  => ['bi-linkedin',   'LinkedIn'],
            'social_youtube'   => ['bi-youtube',    'YouTube'],
        ];
        $hasSocials = false;
        foreach ($socials as $k => $v) {
            if (!empty($s[$k])) { $hasSocials = true; break; }
        }
        ?>

        <?php if ($hasSocials || $fMenus): ?>
        <div class="d-flex flex-wrap justify-content-center gap-3 mb-3">
            <?php foreach ($socials as $k => [$icon, $label]): ?>
                <?php if (!empty($s[$k])): ?>
                    <a href="<?= e($s[$k]) ?>" target="_blank" rel="noopener"
                       class="text-white opacity-75 fs-5" title="<?= e($label) ?>">
                        <i class="bi <?= e($icon) ?>"></i>
                    </a>
                <?php endif; ?>
            <?php endforeach; ?>
            <?php foreach ($fMenus as $item): ?>
                <a href="<?= e(url(ltrim($item['url'],'/'))) ?>"
                   target="<?= e($item['target']) ?>"
                   class="text-white opacity-75 small"><?= e($item['label']) ?></a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <p class="text-center mb-1">
            &copy; <?= date('Y') ?>
            <strong><?= e(!empty($s['copyright_text']) ? $s['copyright_text'] : $schoolName) ?></strong>
            <?php if ($campusName): ?>&mdash; <?= e($campusName) ?><?php endif; ?>
        </p>
        <p class="text-center mb-0 opacity-75 small">
            <?= e(!empty($footerData['footer_tagline']) ? $footerData['footer_tagline'] : ($tagline ?: $appName)) ?>
            <?php if (!empty($footerData['footer_show_login'])): ?>
                &mdash; <a href="<?= e(url('/login')) ?>" class="text-white">Staff Login</a>
            <?php endif; ?>
        </p>

        <?php if (!empty($s['school_email']) || !empty($s['school_phone'])): ?>
        <p class="text-center mt-2 mb-0 small opacity-60">
            <?php if (!empty($s['school_email'])): ?>
                <i class="bi bi-envelope me-1"></i><?= e($s['school_email']) ?>
            <?php endif; ?>
            <?php if (!empty($s['school_phone'])): ?>
                &nbsp;&nbsp;<i class="bi bi-telephone me-1"></i><?= e($s['school_phone']) ?>
            <?php endif; ?>
        </p>
        <?php endif; ?>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= e(url('assets/js/app.js')) ?>"></script>
</body>
</html>
