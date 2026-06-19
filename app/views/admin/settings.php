<div class="page-heading">
    <h1>System Settings</h1>
    <p>Manage system name, logo, campus photo, colors, and contact information.</p>
</div>

<form method="post" action="<?= e(url('/admin/settings')) ?>" enctype="multipart/form-data">
    <?= csrf_field() ?>

    <div class="row g-4">

        <!-- Left column -->
        <div class="col-lg-8">

            <!-- General Info -->
            <div class="settings-card mb-4">
                <div class="settings-card-header">
                    <i class="bi bi-info-circle me-2"></i>General Information
                </div>
                <div class="settings-card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">System Name</label>
                            <input class="form-control" name="app_name"
                                   value="<?= e($settings['app_name'] ?? 'ISIMS') ?>" required>
                            <small class="text-secondary">Displayed in browser tab and portal header</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">University Full Name</label>
                            <input class="form-control" name="school_name"
                                   value="<?= e($settings['school_name'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Campus Name</label>
                            <input class="form-control" name="campus_name"
                                   value="<?= e($settings['campus_name'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Country</label>
                            <input class="form-control" name="school_country"
                                   value="<?= e($settings['school_country'] ?? '') ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Site Tagline</label>
                            <input class="form-control" name="site_tagline"
                                   value="<?= e($settings['site_tagline'] ?? '') ?>"
                                   placeholder="Empowering International Students at UNILAK Nyanza">
                            <small class="text-secondary">Displayed on the auth pages and footer</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact -->
            <div class="settings-card mb-4">
                <div class="settings-card-header">
                    <i class="bi bi-telephone me-2"></i>Contact Information
                </div>
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

            <!-- Colors -->
            <div class="settings-card mb-4">
                <div class="settings-card-header">
                    <i class="bi bi-palette me-2"></i>Brand Colors
                </div>
                <div class="settings-card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Primary Color</label>
                            <div class="d-flex gap-2 align-items-center">
                                <input type="color" class="form-control form-control-color"
                                       name="primary_color"
                                       value="<?= e($settings['primary_color'] ?? '#1f6feb') ?>">
                                <input class="form-control" id="primary_color_hex"
                                       value="<?= e($settings['primary_color'] ?? '#1f6feb') ?>"
                                       readonly style="max-width:120px">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Accent Color</label>
                            <div class="d-flex gap-2 align-items-center">
                                <input type="color" class="form-control form-control-color"
                                       name="accent_color"
                                       value="<?= e($settings['accent_color'] ?? '#0f766e') ?>">
                                <input class="form-control" id="accent_color_hex"
                                       value="<?= e($settings['accent_color'] ?? '#0f766e') ?>"
                                       readonly style="max-width:120px">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Right column: uploads -->
        <div class="col-lg-4">

            <!-- Logo -->
            <div class="settings-card mb-4">
                <div class="settings-card-header">
                    <i class="bi bi-image me-2"></i>School Logo
                </div>
                <div class="settings-card-body text-center">
                    <?php if (! empty($settings['logo_path'])): ?>
                        <img src="<?= e(url($settings['logo_path'])) ?>"
                             alt="Logo" class="settings-preview-img mb-3">
                    <?php else: ?>
                        <div class="upload-placeholder mb-3">
                            <i class="bi bi-building"></i>
                            <p>No logo uploaded</p>
                        </div>
                    <?php endif; ?>
                    <input type="file" class="form-control" name="logo"
                           accept="image/jpeg,image/png,image/webp,image/svg+xml">
                    <small class="text-secondary d-block mt-1">Max 2MB. JPG, PNG, SVG, WebP</small>
                </div>
            </div>

            <!-- Favicon -->
            <div class="settings-card mb-4">
                <div class="settings-card-header">
                    <i class="bi bi-grid me-2"></i>Favicon
                </div>
                <div class="settings-card-body text-center">
                    <?php if (! empty($settings['favicon_path'])): ?>
                        <img src="<?= e(url($settings['favicon_path'])) ?>"
                             alt="Favicon" class="settings-preview-img mb-3"
                             style="max-height:64px;max-width:64px">
                    <?php else: ?>
                        <div class="upload-placeholder mb-3">
                            <i class="bi bi-grid"></i>
                            <p>No favicon uploaded</p>
                        </div>
                    <?php endif; ?>
                    <input type="file" class="form-control" name="favicon"
                           accept="image/x-icon,image/png,image/svg+xml">
                    <small class="text-secondary d-block mt-1">ICO, PNG, or SVG. Max 512&times;512px</small>
                </div>
            </div>

            <!-- Campus Photo -->
            <div class="settings-card mb-4">
                <div class="settings-card-header">
                    <i class="bi bi-camera me-2"></i>Campus Photo
                </div>
                <div class="settings-card-body text-center">
                    <?php if (! empty($settings['campus_photo'])): ?>
                        <img src="<?= e(url($settings['campus_photo'])) ?>"
                             alt="Campus" class="settings-preview-img mb-3">
                    <?php else: ?>
                        <div class="upload-placeholder mb-3">
                            <i class="bi bi-geo-alt"></i>
                            <p>No campus photo</p>
                        </div>
                    <?php endif; ?>
                    <input type="file" class="form-control" name="campus_photo"
                           accept="image/jpeg,image/png,image/webp">
                    <small class="text-secondary d-block mt-1">Max 5MB. Used on home &amp; login pages</small>
                </div>
            </div>

        </div>
    </div>

    <div class="d-flex gap-3 mt-2">
        <button class="btn btn-primary px-5">
            <i class="bi bi-check-lg me-2"></i>Save Settings
        </button>
        <a href="<?= e(url('/dashboard')) ?>" class="btn btn-outline-secondary">Cancel</a>
    </div>

</form>

<script>
document.querySelectorAll('input[type=color]').forEach(function(picker) {
    var hex = document.getElementById(picker.name.replace('_color','_color_hex'));
    if (hex) {
        picker.addEventListener('input', function() { hex.value = picker.value; });
    }
});
</script>
