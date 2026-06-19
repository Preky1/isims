<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $__s = (new Setting())->all(); ?>
    <title><?= e($__s['app_name'] ?? 'SIMS') ?></title>
    <?php if (!empty($__s['favicon_path'])): ?>
        <link rel="icon" href="<?= e(url($__s['favicon_path'])) ?>">
    <?php endif; ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="<?= e(url('assets/css/app.css')) ?>" rel="stylesheet">
    <style>:root{--brand:<?= e($__s['primary_color']??'#1f6feb') ?>;--accent:<?= e($__s['accent_color']??'#0f766e') ?>;}
    <?= $__s['custom_css'] ?? '' ?></style>
</head>
<body>
<?php
    $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $scriptDir   = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
    $relPath     = $scriptDir ? substr($currentPath, strlen($scriptDir)) : $currentPath;
    $relPath     = '/' . ltrim($relPath ?: '/', '/');
    $unread      = (new Notification())->unreadCount((int) auth_user()['id']);
?>
<div class="app-shell">
    <aside class="sidebar">
        <div class="brand-row">
            <?php
            $__bs = (new Setting())->all();
            $__logo = $__bs['logo_path'] ?? '';
            $__appName = $__bs['app_name'] ?? 'SIMS';
            ?>
            <?php if ($__logo): ?>
                <img src="<?= e(url($__logo)) ?>" alt="logo"
                     style="height:36px;width:auto;border-radius:8px;flex-shrink:0">
            <?php else: ?>
                <div class="brand-mark">S</div>
            <?php endif; ?>
            <div>
                <strong><?= e($__appName) ?></strong>
                <small><?= e(auth_user()['role_name'] ?? '') ?></small>
            </div>
        </div>
        <nav class="nav flex-column gap-1">
            <?php
            $nav = [
                '/dashboard'   => ['Dashboard',        'bi-speedometer2'],
                '/announcements' => ['Announcements',  'bi-megaphone'],
                '/messages'    => ['Messages',          'bi-chat-dots'],
                '/resources'   => ['Resources',         'bi-folder2-open'],
                '/events'      => ['Calendar & Events', 'bi-calendar-event'],
                '/faqs'        => ['FAQs',              'bi-question-circle'],
                '/notifications' => ['Notifications',  'bi-bell'],
            ];
            foreach ($nav as $href => $item): ?>
                <a class="nav-link <?= str_starts_with($relPath, $href) ? 'active' : '' ?>"
                   href="<?= e(url($href)) ?>">
                    <i class="bi <?= $item[1] ?> me-2"></i><?= $item[0] ?>
                    <?php if ($href === '/notifications' && $unread > 0): ?>
                        <span class="badge bg-danger ms-auto"><?= $unread ?></span>
                    <?php endif; ?>
                </a>
            <?php endforeach; ?>
            <?php if (has_role('leader_admin', 'system_admin')): ?>
                <div class="nav-divider">Admin</div>
                <a class="nav-link <?= str_starts_with($relPath, '/admin/users') ? 'active' : '' ?>"
                   href="<?= e(url('/admin/users')) ?>">
                    <i class="bi bi-people me-2"></i>Manage Users
                </a>
            <?php endif; ?>
            <?php if (has_role('system_admin')): ?>
                <a class="nav-link <?= str_starts_with($relPath, '/admin/reports') ? 'active' : '' ?>"
                   href="<?= e(url('/admin/reports')) ?>">
                    <i class="bi bi-bar-chart-line me-2"></i>Reports
                </a>
                <a class="nav-link <?= str_starts_with($relPath, '/admin/faculties') ? 'active' : '' ?>"
                   href="<?= e(url('/admin/faculties')) ?>">
                    <i class="bi bi-diagram-3 me-2"></i>Faculties &amp; Programs
                </a>
                <a class="nav-link <?= str_starts_with($relPath, '/admin/settings') ? 'active' : '' ?>"
                   href="<?= e(url('/admin/settings')) ?>">
                    <i class="bi bi-gear me-2"></i>System Settings
                </a>
                <div class="nav-divider">CMS</div>
                <a class="nav-link <?= str_starts_with($relPath, '/cms/hero') ? 'active' : '' ?>"
                   href="<?= e(url('/cms/hero')) ?>">
                    <i class="bi bi-images me-2"></i>Hero / Banner
                </a>
                <a class="nav-link <?= str_starts_with($relPath, '/cms/sections') ? 'active' : '' ?>"
                   href="<?= e(url('/cms/sections')) ?>">
                    <i class="bi bi-layout-text-sidebar me-2"></i>Page Sections
                </a>
                <a class="nav-link <?= str_starts_with($relPath, '/cms/menus') ? 'active' : '' ?>"
                   href="<?= e(url('/cms/menus')) ?>">
                    <i class="bi bi-list-ul me-2"></i>Navigation Menus
                </a>
                <a class="nav-link <?= str_starts_with($relPath, '/cms/footer') ? 'active' : '' ?>"
                   href="<?= e(url('/cms/footer')) ?>">
                    <i class="bi bi-layout-sidebar-reverse me-2"></i>Footer
                </a>
                <a class="nav-link <?= str_starts_with($relPath, '/cms/media') ? 'active' : '' ?>"
                   href="<?= e(url('/cms/media')) ?>">
                    <i class="bi bi-cloud-arrow-up me-2"></i>Media Library
                </a>
                <a class="nav-link <?= str_starts_with($relPath, '/cms/theme') ? 'active' : '' ?>"
                   href="<?= e(url('/cms/theme')) ?>">
                    <i class="bi bi-palette me-2"></i>Theme &amp; Colors
                </a>
                <a class="nav-link <?= str_starts_with($relPath, '/cms/profile') ? 'active' : '' ?>"
                   href="<?= e(url('/cms/profile')) ?>">
                    <i class="bi bi-person-circle me-2"></i>My Profile
                </a>
            <?php endif; ?>
        </nav>
    </aside>
    <div class="content-shell">
        <header class="topbar">
            <div>
                <div class="fw-semibold"><?= e(auth_user()['name'] ?? '') ?></div>
                <small class="text-secondary"><?= e(auth_user()['email'] ?? '') ?></small>
            </div>
            <a class="btn btn-outline-danger btn-sm" href="<?= e(url('/logout')) ?>">
                <i class="bi bi-box-arrow-right me-1"></i>Logout
            </a>
        </header>
        <main class="main-content">
            <?php require APP_PATH . '/views/layouts/flash.php'; ?>
            <?= $content ?>
        </main>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= e(url('assets/js/app.js')) ?>"></script>
</body>
</html>
