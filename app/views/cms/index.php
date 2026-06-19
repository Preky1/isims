<div class="page-heading">
    <h1><i class="bi bi-grid-1x2 me-2 text-primary"></i>Content Management</h1>
    <p>Control every visible part of the public website without editing code.</p>
</div>

<div class="row g-4">
    <?php
    $tiles = [
        ['/cms/hero',     'bi-images',                'Hero / Banner',     'Edit hero title, subtitle, description, buttons, and background image.', '#1f6feb'],
        ['/cms/sections', 'bi-layout-text-sidebar',   'Page Sections',     'Edit about, stats, announcements, events, features, and CTA sections.',   '#0f766e'],
        ['/cms/menus',    'bi-list-ul',               'Navigation Menus',  'Add, edit, reorder, or remove header and footer navigation links.',        '#7c3aed'],
        ['/cms/footer',   'bi-layout-sidebar-reverse','Footer',            'Edit footer text, tagline, contact info, social links, and copyright.',    '#b45309'],
        ['/cms/media',    'bi-cloud-arrow-up',        'Media Library',     'Upload images, videos, PDFs. Reuse media across the entire website.',      '#be185d'],
        ['/cms/theme',    'bi-palette',               'Theme & Colors',    'Change primary color, accent color, fonts, and inject custom CSS.',         '#059669'],
        ['/admin/settings','bi-gear',                 'System Settings',   'Change site name, logo, favicon, school info, and contact details.',        '#dc2626'],
        ['/cms/profile',  'bi-person-circle',         'My Profile',        'Update your admin name, email, phone, and change your password.',           '#6366f1'],
    ];
    foreach ($tiles as [$url, $icon, $title, $desc, $color]): ?>
    <div class="col-md-6 col-lg-3">
        <a href="<?= e(url($url)) ?>" class="cms-tile text-decoration-none">
            <div class="cms-tile-icon" style="background:<?= e($color) ?>15;color:<?= e($color) ?>">
                <i class="bi <?= e($icon) ?>"></i>
            </div>
            <h6 class="cms-tile-title"><?= e($title) ?></h6>
            <p class="cms-tile-desc"><?= e($desc) ?></p>
            <span class="cms-tile-link" style="color:<?= e($color) ?>">
                Manage <i class="bi bi-arrow-right ms-1"></i>
            </span>
        </a>
    </div>
    <?php endforeach; ?>
</div>
