<div class="page-heading">
    <h1><i class="bi bi-palette me-2 text-primary"></i>Theme &amp; Colors</h1>
    <p>Customize primary and accent colors, fonts, and inject custom CSS across the entire website.</p>
</div>

<form method="post" action="<?= e(url('/cms/theme')) ?>">
    <?= csrf_field() ?>
    <div class="row g-4">

        <div class="col-lg-6">
            <div class="settings-card mb-4">
                <div class="settings-card-header"><i class="bi bi-palette2 me-2"></i>Brand Colors</div>
                <div class="settings-card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Primary Color</label>
                            <div class="d-flex gap-2 align-items-center">
                                <input type="color" class="form-control form-control-color"
                                       name="primary_color" id="primary_color"
                                       value="<?= e($settings['primary_color'] ?? '#1f6feb') ?>">
                                <input class="form-control" id="primary_hex"
                                       value="<?= e($settings['primary_color'] ?? '#1f6feb') ?>"
                                       readonly style="max-width:110px">
                            </div>
                            <small class="text-muted">Used for buttons, links, hero backgrounds</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Accent Color</label>
                            <div class="d-flex gap-2 align-items-center">
                                <input type="color" class="form-control form-control-color"
                                       name="accent_color" id="accent_color"
                                       value="<?= e($settings['accent_color'] ?? '#0f766e') ?>">
                                <input class="form-control" id="accent_hex"
                                       value="<?= e($settings['accent_color'] ?? '#0f766e') ?>"
                                       readonly style="max-width:110px">
                            </div>
                            <small class="text-muted">Used for secondary accents and badges</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="settings-card mb-4">
                <div class="settings-card-header"><i class="bi bi-type me-2"></i>Typography</div>
                <div class="settings-card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Heading Font</label>
                            <select class="form-select" name="font_heading">
                                <?php foreach (['Inter','Roboto','Open Sans','Montserrat','Poppins','Lato','Nunito','Raleway'] as $f): ?>
                                    <option value="<?= e($f) ?>" <?= ($settings['font_heading']??'Inter')===$f?'selected':'' ?>><?= e($f) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Body Font</label>
                            <select class="form-select" name="font_body">
                                <?php foreach (['Inter','Roboto','Open Sans','Montserrat','Poppins','Lato','Nunito','Raleway'] as $f): ?>
                                    <option value="<?= e($f) ?>" <?= ($settings['font_body']??'Inter')===$f?'selected':'' ?>><?= e($f) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="dark_mode"
                                   id="darkMode" value="1"
                                   <?= !empty($settings['dark_mode']) ? 'checked' : '' ?>>
                            <label class="form-check-label fw-semibold" for="darkMode">
                                Enable Dark Mode <span class="badge bg-warning text-dark ms-1">Experimental</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="settings-card mb-4">
                <div class="settings-card-header"><i class="bi bi-code-slash me-2"></i>Custom CSS</div>
                <div class="settings-card-body">
                    <label class="form-label fw-semibold">Inject Custom CSS</label>
                    <textarea class="form-control font-monospace" name="custom_css" rows="14"
                              style="font-size:13px"><?= e($settings['custom_css'] ?? '') ?></textarea>
                    <small class="text-muted">CSS added inside <code>&lt;style&gt;</code> on every page. Use with care.</small>
                </div>
            </div>
        </div>

    </div>

    <div class="d-flex gap-3 mt-2">
        <button class="btn btn-primary px-5">
            <i class="bi bi-check-lg me-2"></i>Save Theme
        </button>
        <a href="<?= e(url('/cms')) ?>" class="btn btn-outline-secondary">Cancel</a>
    </div>
</form>

<script>
['primary','accent'].forEach(function(name) {
    var picker = document.getElementById(name + '_color');
    var hex    = document.getElementById(name + '_hex');
    picker.addEventListener('input', function() { hex.value = picker.value; });
});
</script>
