<div class="page-heading">
    <h1><i class="bi bi-layout-sidebar-reverse me-2 text-primary"></i>Footer Management</h1>
    <p>Edit footer text, tagline, contact information, social media links, and copyright notice.</p>
</div>

<form method="post" action="<?= e(url('/cms/footer')) ?>">
    <?= csrf_field() ?>
    <div class="row g-4">

        <!-- Left column -->
        <div class="col-lg-7">

            <div class="settings-card mb-4">
                <div class="settings-card-header"><i class="bi bi-card-text me-2"></i>Footer Text</div>
                <div class="settings-card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold">Footer Tagline</label>
                            <input class="form-control" name="footer_tagline"
                                   value="<?= e($footer['footer_tagline'] ?? '') ?>"
                                   placeholder="International Student Information Management System">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Copyright Text Override</label>
                            <input class="form-control" name="copyright_text"
                                   value="<?= e($settings['copyright_text'] ?? '') ?>"
                                   placeholder="Leave blank to use school name from System Settings">
                            <small class="text-muted">e.g. "University of Lay Adventists of Kigali (UNILAK)"</small>
                        </div>
                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="footer_show_login"
                                       id="showLogin" value="1"
                                       <?= !empty($footer['footer_show_login']) ? 'checked' : '' ?>>
                                <label class="form-check-label fw-semibold" for="showLogin">
                                    Show "Staff Login" link in footer
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="settings-card mb-4">
                <div class="settings-card-header"><i class="bi bi-telephone me-2"></i>Contact Information</div>
                <div class="settings-card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email</label>
                            <input class="form-control" type="email" name="school_email"
                                   value="<?= e($settings['school_email'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Phone</label>
                            <input class="form-control" name="school_phone"
                                   value="<?= e($settings['school_phone'] ?? '') ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Address</label>
                            <input class="form-control" name="school_address"
                                   value="<?= e($settings['school_address'] ?? '') ?>">
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Right column: social -->
        <div class="col-lg-5">
            <div class="settings-card mb-4">
                <div class="settings-card-header"><i class="bi bi-share me-2"></i>Social Media Links</div>
                <div class="settings-card-body">
                    <?php
                    $socials = [
                        'social_facebook'  => ['bi-facebook',  'Facebook URL'],
                        'social_twitter'   => ['bi-twitter-x', 'Twitter / X URL'],
                        'social_instagram' => ['bi-instagram',  'Instagram URL'],
                        'social_linkedin'  => ['bi-linkedin',   'LinkedIn URL'],
                        'social_youtube'   => ['bi-youtube',    'YouTube URL'],
                    ];
                    foreach ($socials as $k => [$icon, $label]): ?>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="bi <?= e($icon) ?> me-1"></i><?= e($label) ?>
                        </label>
                        <input class="form-control" type="url" name="<?= e($k) ?>"
                               value="<?= e($settings[$k] ?? '') ?>"
                               placeholder="https://...">
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

    </div>

    <div class="d-flex gap-3 mt-2">
        <button class="btn btn-primary px-5">
            <i class="bi bi-check-lg me-2"></i>Save Footer Settings
        </button>
        <a href="<?= e(url('/cms')) ?>" class="btn btn-outline-secondary">Cancel</a>
    </div>
</form>
