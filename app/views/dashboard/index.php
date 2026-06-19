<div class="page-heading">
    <h1>Welcome, <?= e(auth_user()['name']) ?></h1>
    <p><?= e(auth_user()['role_name']) ?> workspace &mdash; <?= e(date('l, F j, Y')) ?></p>
</div>

<?php if (has_role('isr_leader', 'leader_admin', 'system_admin')): ?>
<div class="metric-grid mb-4" style="grid-template-columns:repeat(4,minmax(0,1fr))">
    <div class="metric"><span>Total Students</span><strong><?= (int) $stats['students'] ?></strong></div>
    <div class="metric"><span>ISR Leaders</span><strong><?= (int) $stats['leaders'] ?></strong></div>
    <div class="metric"><span>Announcements</span><strong><?= (int) $stats['announcements'] ?></strong></div>
    <div class="metric"><span>Open Messages</span><strong><?= (int) ($stats['open_messages'] ?? $stats['messages']) ?></strong></div>
    <div class="metric"><span>Resources</span><strong><?= (int) $stats['resources'] ?></strong></div>
    <div class="metric"><span>Events</span><strong><?= (int) $stats['events'] ?></strong></div>
    <div class="metric"><span>FAQs</span><strong><?= (int) $stats['faqs'] ?></strong></div>
    <div class="metric"><span>Notifications Sent</span><strong><?= (int) $stats['notifications'] ?></strong></div>
</div>
<?php if (has_role('system_admin')): ?>
<div class="row g-3 mb-4">
    <?php
    $quickLinks = [
        ['/cms',              'bi-grid-1x2',              'CMS Dashboard',    '#1f6feb'],
        ['/cms/hero',         'bi-images',                'Hero / Banner',    '#7c3aed'],
        ['/cms/sections',     'bi-layout-text-sidebar',   'Page Sections',    '#0f766e'],
        ['/cms/menus',        'bi-list-ul',               'Navigation Menus', '#b45309'],
        ['/cms/media',        'bi-cloud-arrow-up',        'Media Library',    '#be185d'],
        ['/cms/theme',        'bi-palette',               'Theme & Colors',   '#059669'],
        ['/admin/settings',   'bi-gear',                  'System Settings',  '#dc2626'],
        ['/admin/reports',    'bi-bar-chart-line',        'Reports',          '#4f46e5'],
    ];
    foreach ($quickLinks as [$href, $icon, $label, $color]): ?>
    <div class="col-6 col-md-3">
        <a href="<?= e(url($href)) ?>" class="d-flex align-items-center gap-2 p-3 rounded-3 text-decoration-none"
           style="background:#fff;border:1px solid var(--line);transition:box-shadow .15s"
           onmouseover="this.style.boxShadow='0 4px 16px rgba(24,32,47,.1)'"
           onmouseout="this.style.boxShadow=''">
            <span style="width:36px;height:36px;border-radius:8px;background:<?= e($color) ?>18;color:<?= e($color) ?>;display:grid;place-items:center;font-size:18px;flex-shrink:0">
                <i class="bi <?= e($icon) ?>"></i>
            </span>
            <span style="font-size:13px;font-weight:600;color:var(--ink)"><?= e($label) ?></span>
        </a>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>
<?php else: ?>
<div class="metric-grid mb-4" style="grid-template-columns: repeat(3, minmax(0,1fr))">
    <div class="metric"><span>My Messages</span><strong><?= count($messages) ?></strong></div>
    <div class="metric"><span>Notifications</span><strong><?= count($notifications) ?></strong></div>
    <div class="metric"><span>Upcoming Events</span><strong><?= count($events) ?></strong></div>
</div>
<?php endif; ?>

<div class="row g-4">
    <section class="col-lg-6">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h2 class="section-title mb-0">Recent Announcements</h2>
            <a href="<?= e(url('/announcements')) ?>" class="btn btn-sm btn-outline-primary">View all</a>
        </div>
        <?php if (! $announcements): ?>
            <p class="text-secondary">No announcements yet.</p>
        <?php endif; ?>
        <?php foreach ($announcements as $item): ?>
            <article class="list-card mb-3">
                <span class="badge text-bg-secondary"><?= e($item['audience']) ?></span>
                <h3 class="h5 mt-2 mb-1"><?= e($item['title']) ?></h3>
                <p class="mb-0"><?= e(mb_substr($item['body'], 0, 140)) ?><?= mb_strlen($item['body']) > 140 ? '&hellip;' : '' ?></p>
            </article>
        <?php endforeach; ?>
    </section>

    <section class="col-lg-6">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h2 class="section-title mb-0">Upcoming Events</h2>
            <a href="<?= e(url('/events')) ?>" class="btn btn-sm btn-outline-primary">View all</a>
        </div>
        <?php if (! $events): ?>
            <p class="text-secondary">No upcoming events.</p>
        <?php endif; ?>
        <?php foreach ($events as $event): ?>
            <article class="list-card mb-3">
                <span class="badge text-bg-secondary"><?= e($event['category']) ?></span>
                <h3 class="h5 mt-2 mb-1"><?= e($event['title']) ?></h3>
                <small class="text-secondary">
                    <?= e($event['location'] ?: 'TBA') ?> &middot;
                    <?= e(date('M d, Y H:i', strtotime($event['starts_at']))) ?>
                </small>
            </article>
        <?php endforeach; ?>
    </section>

    <section class="col-lg-7">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h2 class="section-title mb-0">Recent Messages</h2>
            <a href="<?= e(url('/messages')) ?>" class="btn btn-sm btn-outline-primary">View all</a>
        </div>
        <?php if (! $messages): ?>
            <p class="text-secondary">No messages yet.</p>
        <?php endif; ?>
        <?php foreach ($messages as $message): ?>
            <a class="list-card d-block text-decoration-none mb-2"
               href="<?= e(url('/messages?message_id=' . (int) $message['id'])) ?>">
                <div class="d-flex justify-content-between">
                    <span class="badge text-bg-<?= match($message['status']) {
                        'open'        => 'primary',
                        'in_progress' => 'warning',
                        'resolved'    => 'success',
                        default       => 'secondary'
                    } ?>"><?= e($message['status']) ?></span>
                    <small class="text-secondary"><?= e($message['category']) ?></small>
                </div>
                <h3 class="h6 mt-2 text-dark mb-0"><?= e($message['subject']) ?></h3>
            </a>
        <?php endforeach; ?>
    </section>

    <section class="col-lg-5">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h2 class="section-title mb-0">Notifications</h2>
            <a href="<?= e(url('/notifications')) ?>" class="btn btn-sm btn-outline-primary">View all</a>
        </div>
        <?php if (! $notifications): ?>
            <p class="text-secondary">No notifications.</p>
        <?php endif; ?>
        <?php foreach ($notifications as $note): ?>
            <article class="list-card mb-2 <?= $note['read_at'] ? 'opacity-75' : '' ?>">
                <?php if (! $note['read_at']): ?>
                    <span class="badge text-bg-danger mb-1">New</span>
                <?php endif; ?>
                <strong class="d-block"><?= e($note['title']) ?></strong>
                <?php if ($note['body']): ?><p class="mb-0 small"><?= e($note['body']) ?></p><?php endif; ?>
            </article>
        <?php endforeach; ?>
    </section>
</div>
