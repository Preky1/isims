<?php
// Helper: get section field with fallback
$S = function(string $key, string $field, string $fallback = '') use ($sec): string {
    return !empty($sec[$key][$field]) ? $sec[$key][$field] : $fallback;
};

// Hero data
$heroEyebrow  = $hero['eyebrow']     ?? 'University of Lay Adventists of Kigali';
$heroTitle    = $hero['title']       ?? 'UNILAK Nyanza Campus';
$heroSubtitle = $hero['subtitle']    ?? 'Student Information Management System';
$heroDesc     = $hero['description'] ?? 'Your central hub for announcements, campus events, academic resources, and direct communication with SR leadership.';
$heroBg       = !empty($hero['bg_image']) ? url($hero['bg_image']) : '';
$btn1Text     = $hero['btn1_text']   ?? 'Student Registration';
$btn1Url      = $hero['btn1_url']    ?? '/register';
$btn1Icon     = $hero['btn1_icon']   ?? 'bi-person-plus';
$btn2Text     = $hero['btn2_text']   ?? 'Login to Portal';
$btn2Url      = $hero['btn2_url']    ?? '/login';
$btn2Icon     = $hero['btn2_icon']   ?? 'bi-box-arrow-in-right';

$logoPath   = $settings['logo_path'] ?? '';
$schoolName = $settings['school_name'] ?? 'UNILAK';
?>

<!-- ── HERO ───────────────────────────────────────────────────────── -->
<section class="hero-section">
    <div class="hero-bg <?= $heroBg ? '' : 'hero-no-image' ?>"
         <?= $heroBg ? 'style="background-image:url(\'' . e($heroBg) . '\')"' : '' ?>>
    </div>
    <div class="hero-overlay"></div>
    <div class="container hero-content">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <?php if ($logoPath): ?>
                    <img src="<?= e(url($logoPath)) ?>" alt="<?= e($schoolName) ?> logo"
                         class="hero-logo mx-auto d-block mb-4"
                         style="height:80px;width:auto;border-radius:20px;">
                <?php else: ?>
                    <div class="school-logo-placeholder hero-logo mx-auto mb-4">
                        <span><?= e(strtoupper(substr($schoolName, 0, 1))) ?></span>
                    </div>
                <?php endif; ?>
                <p class="hero-eyebrow"><?= e($heroEyebrow) ?></p>
                <h1 class="hero-title"><?= e($heroTitle) ?></h1>
                <p class="hero-subtitle"><?= e($heroSubtitle) ?></p>
                <p class="hero-desc"><?= e($heroDesc) ?></p>
                <div class="d-flex gap-3 justify-content-center flex-wrap mt-4">
                    <a href="<?= e(url(ltrim($btn1Url,'/'))) ?>" class="btn btn-hero-primary btn-lg">
                        <i class="bi <?= e($btn1Icon) ?> me-2"></i><?= e($btn1Text) ?>
                    </a>
                    <a href="<?= e(url(ltrim($btn2Url,'/'))) ?>" class="btn btn-hero-outline btn-lg">
                        <i class="bi <?= e($btn2Icon) ?> me-2"></i><?= e($btn2Text) ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="hero-scroll"><i class="bi bi-chevron-double-down"></i></div>
</section>

<!-- ── STATS STRIP ────────────────────────────────────────────────── -->
<section class="stats-strip">
    <div class="container">
        <div class="row g-0 text-center">
            <?php
            $stats = [
                ['stats_announcements','Announcements','bi-megaphone'],
                ['stats_events',       'Campus Events','bi-calendar-event'],
                ['stats_resources',    'Resources',    'bi-folder2-open'],
                ['stats_support',      'SR Support',   'bi-chat-dots'],
            ];
            foreach ($stats as [$key,$default,$icon]):
                $label = $S($key,'heading',$default);
            ?>
            <div class="col-6 col-md-3 stat-item">
                <i class="bi <?= e($icon) ?> stat-icon"></i>
                <div class="stat-label"><?= e($label) ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ── ABOUT ──────────────────────────────────────────────────────── -->
<?php
$aboutActive = ($sec['about_heading']['is_active'] ?? 1);
if ($aboutActive):
    $aboutEyebrow = $S('about_heading','subheading','About UNILAK Nyanza');
    $aboutHeading = $S('about_heading','heading','Empowering Students');
    $aboutBody    = $S('about_heading','body','');
    $aboutImage   = $S('about_heading','image','');
    $aboutBg      = $S('about_heading','bg_color','bg-white');
    $aboutParas   = $aboutBody ? explode("\n\n", trim($aboutBody)) : [
        'UNILAK Nyanza Campus is home to a vibrant community of students from across Africa and beyond. The Student Representative (SR) leadership team is here to support your academic journey and campus experience.',
        'Through the SIMS portal, students receive timely announcements, access academic resources, view campus events, and communicate directly with SR leaders.',
    ];
?>
<section class="public-section <?= e($aboutBg ?: 'bg-white') ?>">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <?php if ($aboutImage): ?>
                    <img src="<?= e(url($aboutImage)) ?>" class="img-fluid rounded-3 shadow" alt="Campus">
                <?php else: ?>
                    <div class="campus-photo-placeholder rounded-3">
                        <i class="bi bi-building"></i>
                        <p>Campus Photo Here</p>
                        <small>Upload via CMS &rarr; Page Sections</small>
                    </div>
                <?php endif; ?>
            </div>
            <div class="col-lg-6">
                <span class="section-eyebrow"><?= e($aboutEyebrow) ?></span>
                <h2 class="section-heading"><?= e($aboutHeading) ?></h2>
                <?php foreach ($aboutParas as $para): ?>
                    <p class="text-secondary"><?= e(trim($para)) ?></p>
                <?php endforeach; ?>
                <div class="d-flex gap-3 flex-wrap mt-4">
                    <div class="about-badge"><i class="bi bi-shield-check me-2 text-primary"></i>Secure Portal</div>
                    <div class="about-badge"><i class="bi bi-bell me-2 text-primary"></i>Real-time Alerts</div>
                    <div class="about-badge"><i class="bi bi-people me-2 text-primary"></i>SR Support</div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ── ANNOUNCEMENTS ──────────────────────────────────────────────── -->
<?php if ($sec['announcements_section']['is_active'] ?? 1): ?>
<section class="public-section bg-light">
    <div class="container">
        <div class="section-header text-center mb-5">
            <span class="section-eyebrow"><?= e($S('announcements_section','subheading','Stay Informed')) ?></span>
            <h2 class="section-heading"><?= e($S('announcements_section','heading','Latest Announcements')) ?></h2>
            <p class="text-secondary"><?= e($S('announcements_section','body','Official updates from SR leadership')) ?></p>
        </div>
        <?php if (!$announcements): ?>
            <p class="text-center text-secondary">No announcements yet. Check back soon.</p>
        <?php endif; ?>
        <div class="row g-4">
            <?php foreach ($announcements as $ann):
                $audienceColor = match($ann['audience']) {
                    'students'   => 'primary',
                    'department' => 'warning',
                    default      => 'success',
                };
            ?>
            <div class="col-md-6 col-lg-4">
                <article class="pub-card h-100">
                    <div class="pub-card-top">
                        <span class="badge text-bg-<?= $audienceColor ?>">
                            <?= e(ucfirst($ann['audience'])) ?>
                        </span>
                        <small class="text-secondary ms-auto">
                            <?= e(date('M d, Y', strtotime($ann['published_at'] ?? $ann['created_at']))) ?>
                        </small>
                    </div>
                    <h3 class="pub-card-title"><?= e($ann['title']) ?></h3>
                    <p class="pub-card-body">
                        <?= e(mb_substr($ann['body'], 0, 160)) ?><?= mb_strlen($ann['body']) > 160 ? '&hellip;' : '' ?>
                    </p>
                    <div class="pub-card-footer">
                        <i class="bi bi-person-circle me-1"></i><?= e($ann['author_name']) ?>
                    </div>
                </article>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-5">
            <a href="<?= e(url('/login')) ?>" class="btn btn-outline-primary btn-lg">
                <i class="bi bi-box-arrow-in-right me-2"></i>Login to see all announcements
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ── EVENTS ─────────────────────────────────────────────────────── -->
<?php if ($sec['events_section']['is_active'] ?? 1): ?>
<section class="public-section bg-white">
    <div class="container">
        <div class="section-header text-center mb-5">
            <span class="section-eyebrow"><?= e($S('events_section','subheading','Campus Life')) ?></span>
            <h2 class="section-heading"><?= e($S('events_section','heading','Upcoming Events')) ?></h2>
            <p class="text-secondary"><?= e($S('events_section','body','Prayer weeks, seminars, sports, and more')) ?></p>
        </div>
        <?php if (!$events): ?>
            <p class="text-center text-secondary">No upcoming events at the moment.</p>
        <?php endif; ?>
        <div class="row g-4">
            <?php foreach ($events as $event): ?>
            <div class="col-md-6 col-lg-4">
                <article class="event-card h-100">
                    <div class="event-date-block">
                        <span class="event-month"><?= e(date('M', strtotime($event['starts_at']))) ?></span>
                        <span class="event-day"><?= e(date('d', strtotime($event['starts_at']))) ?></span>
                    </div>
                    <div class="event-info">
                        <span class="badge text-bg-secondary mb-1">
                            <?= e(ucfirst(str_replace('_', ' ', $event['category']))) ?>
                        </span>
                        <h3 class="event-title"><?= e($event['title']) ?></h3>
                        <?php if ($event['location']): ?>
                            <p class="event-meta"><i class="bi bi-geo-alt me-1"></i><?= e($event['location']) ?></p>
                        <?php endif; ?>
                        <p class="event-meta">
                            <i class="bi bi-clock me-1"></i>
                            <?= e(date('H:i', strtotime($event['starts_at']))) ?>
                            <?= $event['ends_at'] ? ' &ndash; ' . e(date('H:i', strtotime($event['ends_at']))) : '' ?>
                        </p>
                    </div>
                </article>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-5">
            <a href="<?= e(url('/login')) ?>" class="btn btn-outline-primary btn-lg">
                <i class="bi bi-calendar-event me-2"></i>Login to view full calendar
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ── FEATURES ───────────────────────────────────────────────────── -->
<?php if ($sec['features_heading']['is_active'] ?? 1): ?>
<section class="public-section features-section">
    <div class="container">
        <div class="section-header text-center mb-5">
            <span class="section-eyebrow"><?= e($S('features_heading','subheading','What We Offer')) ?></span>
            <h2 class="section-heading text-white"><?= e($S('features_heading','heading','Everything You Need in One Place')) ?></h2>
        </div>
        <div class="row g-4">
            <?php
            $features = [
                ['bi-megaphone',       'Announcements',    'Get public, student-only, and department-specific updates from SR leadership.'],
                ['bi-chat-dots',       'Messaging Center', 'Send messages to SR leaders for academic concerns, resource requests, and more.'],
                ['bi-folder2-open',    'Resource Library', 'Access PDFs, past papers, study guides, and notes uploaded by the Librarian.'],
                ['bi-calendar-event',  'School Calendar',  'Stay on top of exam dates, registration periods, holidays, and campus events.'],
                ['bi-question-circle', 'FAQ Knowledge Base','Find instant answers to common academic, registration, and campus life questions.'],
                ['bi-bell',            'Notifications',    'Receive real-time alerts for new announcements, replies, resources, and events.'],
            ];
            foreach ($features as $f): ?>
            <div class="col-md-6 col-lg-4">
                <div class="feature-card">
                    <div class="feature-icon"><i class="bi <?= $f[0] ?>"></i></div>
                    <h3 class="feature-title"><?= $f[1] ?></h3>
                    <p class="feature-desc"><?= $f[2] ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ── CTA ────────────────────────────────────────────────────────── -->
<?php if ($sec['cta_heading']['is_active'] ?? 1): ?>
<section class="cta-section">
    <div class="container text-center">
        <h2 class="cta-title"><?= e($S('cta_heading','heading','Ready to Get Started?')) ?></h2>
        <p class="cta-sub"><?= e($S('cta_heading','body','Register as a student or log in to access your SIMS portal.')) ?></p>
        <div class="d-flex gap-3 justify-content-center flex-wrap">
            <a href="<?= e(url('/register')) ?>" class="btn btn-light btn-lg px-5">
                <i class="bi bi-person-plus me-2"></i>Register Now
            </a>
            <a href="<?= e(url('/login')) ?>" class="btn btn-outline-light btn-lg px-5">
                <i class="bi bi-box-arrow-in-right me-2"></i>Login
            </a>
        </div>
    </div>
</section>
<?php endif; ?>
