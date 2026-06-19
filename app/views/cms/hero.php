<div class="page-heading d-flex align-items-center justify-content-between flex-wrap gap-3">
    <div>
        <h1><i class="bi bi-images me-2 text-primary"></i>Hero / Banner</h1>
        <p>Edit the public homepage hero section — title, subtitle, buttons, and background image.</p>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#heroModal" data-mode="add">
        <i class="bi bi-plus-lg me-1"></i>Add Slide
    </button>
</div>

<?php if (empty($slides)): ?>
    <div class="empty-state">
        <i class="bi bi-images"></i>
        <h5>No hero slides</h5>
        <p>Add your first hero slide to display on the homepage.</p>
    </div>
<?php else: ?>
<div id="heroList" class="row g-4">
<?php foreach ($slides as $slide): ?>
<div class="col-12 drag-hero-row" data-id="<?= $slide['id'] ?>" draggable="false">
<div class="settings-card">
    <div class="settings-card-header d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-2">
            <span class="drag-handle text-muted me-1" style="cursor:grab;font-size:18px" title="Drag to reorder">
                <i class="bi bi-grip-vertical"></i>
            </span>
            <?php if ($slide['is_active']): ?>
                <span class="badge bg-success">Active</span>
            <?php else: ?>
                <span class="badge bg-secondary">Inactive</span>
            <?php endif; ?>
            <strong><?= e($slide['title'] ?: 'Untitled Slide') ?></strong>
            <span class="text-muted small">— <?= e($slide['eyebrow']) ?></span>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#heroModal"
                    data-mode="edit"
                    data-id="<?= $slide['id'] ?>"
                    data-eyebrow="<?= e($slide['eyebrow']) ?>"
                    data-title="<?= e($slide['title']) ?>"
                    data-subtitle="<?= e($slide['subtitle']) ?>"
                    data-description="<?= e($slide['description']) ?>"
                    data-btn1-text="<?= e($slide['btn1_text']) ?>"
                    data-btn1-url="<?= e($slide['btn1_url']) ?>"
                    data-btn1-icon="<?= e($slide['btn1_icon']) ?>"
                    data-btn2-text="<?= e($slide['btn2_text']) ?>"
                    data-btn2-url="<?= e($slide['btn2_url']) ?>"
                    data-btn2-icon="<?= e($slide['btn2_icon']) ?>"
                    data-bg="<?= e($slide['bg_image']) ?>"
                    data-active="<?= $slide['is_active'] ?>"
                    data-order="<?= $slide['sort_order'] ?>">
                <i class="bi bi-pencil"></i> Edit
            </button>
            <form method="post" action="<?= e(url('/cms/hero/delete')) ?>">
                <?= csrf_field() ?>
                <input type="hidden" name="id" value="<?= $slide['id'] ?>">
                <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this hero slide?')">
                    <i class="bi bi-trash3"></i>
                </button>
            </form>
        </div>
    </div>
    <div class="settings-card-body">
        <div class="row g-3 align-items-center">
            <?php if ($slide['bg_image']): ?>
            <div class="col-md-3">
                <img src="<?= e(url($slide['bg_image'])) ?>" class="img-fluid rounded"
                     style="max-height:100px;object-fit:cover;width:100%" alt="Hero BG">
            </div>
            <?php endif; ?>
            <div class="col">
                <p class="mb-1"><strong><?= e($slide['subtitle']) ?></strong></p>
                <p class="text-muted small mb-1"><?= e(mb_substr($slide['description'],0,120)) ?><?= mb_strlen($slide['description'])>120?'&hellip;':'' ?></p>
                <span class="badge bg-primary me-1">
                    <i class="bi <?= e($slide['btn1_icon']) ?> me-1"></i><?= e($slide['btn1_text']) ?>
                </span>
                <span class="badge bg-outline-secondary border">
                    <i class="bi <?= e($slide['btn2_icon']) ?> me-1"></i><?= e($slide['btn2_text']) ?>
                </span>
            </div>
        </div>
    </div>
</div>
</div>
<?php endforeach; ?>
</div>
<?php endif; ?>

<!-- Hero Modal (Add / Edit) -->
<div class="modal fade" id="heroModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <form class="modal-content" method="post" action="<?= e(url('/cms/hero')) ?>" enctype="multipart/form-data">
      <?= csrf_field() ?>
      <input type="hidden" name="id" id="hero_id">
      <input type="hidden" name="bg_image_current" id="hero_bg_current">
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-bold" id="heroModalTitle"><i class="bi bi-images me-2 text-primary"></i>Hero Slide</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="row g-3">
          <div class="col-md-8">
            <label class="form-label fw-semibold">Eyebrow Text</label>
            <input class="form-control" name="eyebrow" id="hero_eyebrow" placeholder="e.g. University of Lay Adventists">
          </div>
          <div class="col-md-4">
            <label class="form-label fw-semibold">Sort Order</label>
            <input class="form-control" type="number" name="sort_order" id="hero_order" value="0">
          </div>
          <div class="col-12">
            <label class="form-label fw-semibold">Main Title <span class="text-danger">*</span></label>
            <input class="form-control" name="title" id="hero_title" required>
          </div>
          <div class="col-12">
            <label class="form-label fw-semibold">Subtitle</label>
            <input class="form-control" name="subtitle" id="hero_subtitle">
          </div>
          <div class="col-12">
            <label class="form-label fw-semibold">Description</label>
            <textarea class="form-control" name="description" id="hero_desc" rows="2"></textarea>
          </div>
          <div class="col-md-4">
            <label class="form-label fw-semibold">Button 1 Text</label>
            <input class="form-control" name="btn1_text" id="hero_btn1_text" placeholder="Student Registration">
          </div>
          <div class="col-md-4">
            <label class="form-label fw-semibold">Button 1 URL</label>
            <input class="form-control" name="btn1_url" id="hero_btn1_url" placeholder="/register">
          </div>
          <div class="col-md-4">
            <label class="form-label fw-semibold">Button 1 Icon</label>
            <input class="form-control" name="btn1_icon" id="hero_btn1_icon" placeholder="bi-person-plus">
          </div>
          <div class="col-md-4">
            <label class="form-label fw-semibold">Button 2 Text</label>
            <input class="form-control" name="btn2_text" id="hero_btn2_text" placeholder="Login to Portal">
          </div>
          <div class="col-md-4">
            <label class="form-label fw-semibold">Button 2 URL</label>
            <input class="form-control" name="btn2_url" id="hero_btn2_url" placeholder="/login">
          </div>
          <div class="col-md-4">
            <label class="form-label fw-semibold">Button 2 Icon</label>
            <input class="form-control" name="btn2_icon" id="hero_btn2_icon" placeholder="bi-box-arrow-in-right">
          </div>
          <div class="col-md-8">
            <label class="form-label fw-semibold">Background Image</label>
            <input class="form-control" type="file" name="bg_image" accept="image/*">
            <small class="text-muted">Max 5MB. Leave blank to keep current. Replaces the gradient fallback.</small>
            <div id="hero_bg_preview" class="mt-2"></div>
          </div>
          <div class="col-md-4">
            <label class="form-label fw-semibold">Status</label>
            <select class="form-select" name="is_active" id="hero_active">
              <option value="1">Active</option>
              <option value="0">Inactive</option>
            </select>
          </div>
        </div>
      </div>
      <div class="modal-footer border-0 pt-0">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Save Slide</button>
      </div>
    </form>
  </div>
</div>

<script>
document.getElementById('heroModal').addEventListener('show.bs.modal', function(e) {
    var b = e.relatedTarget;
    var edit = b.dataset.mode === 'edit';
    document.getElementById('heroModalTitle').innerHTML =
        '<i class="bi bi-images me-2 text-primary"></i>' + (edit ? 'Edit Hero Slide' : 'Add Hero Slide');
    document.getElementById('hero_id').value        = edit ? b.dataset.id : '';
    document.getElementById('hero_eyebrow').value   = edit ? b.dataset.eyebrow : '';
    document.getElementById('hero_title').value     = edit ? b.dataset.title : '';
    document.getElementById('hero_subtitle').value  = edit ? b.dataset.subtitle : '';
    document.getElementById('hero_desc').value      = edit ? b.dataset.description : '';
    document.getElementById('hero_btn1_text').value = edit ? b.dataset.btn1Text : 'Student Registration';
    document.getElementById('hero_btn1_url').value  = edit ? b.dataset.btn1Url : '/register';
    document.getElementById('hero_btn1_icon').value = edit ? b.dataset.btn1Icon : 'bi-person-plus';
    document.getElementById('hero_btn2_text').value = edit ? b.dataset.btn2Text : 'Login to Portal';
    document.getElementById('hero_btn2_url').value  = edit ? b.dataset.btn2Url : '/login';
    document.getElementById('hero_btn2_icon').value = edit ? b.dataset.btn2Icon : 'bi-box-arrow-in-right';
    document.getElementById('hero_active').value    = edit ? b.dataset.active : '1';
    document.getElementById('hero_order').value     = edit ? b.dataset.order : '0';
    document.getElementById('hero_bg_current').value = edit ? b.dataset.bg : '';
    var prev = document.getElementById('hero_bg_preview');
    prev.innerHTML = (edit && b.dataset.bg)
        ? '<img src="<?= e(url('/')) ?>' + b.dataset.bg + '" style="max-height:80px;border-radius:6px" alt="current bg">'
        : '';
});

(function () {
    var list = document.getElementById('heroList');
    if (!list) return;
    var dragging = null;
    list.addEventListener('dragstart', function (e) {
        dragging = e.target.closest('.drag-hero-row');
        if (dragging) dragging.style.opacity = '0.5';
    });
    list.addEventListener('dragend', function () {
        if (dragging) { dragging.style.opacity = ''; dragging = null; }
    });
    list.addEventListener('dragover', function (e) {
        e.preventDefault();
        var target = e.target.closest('.drag-hero-row');
        if (target && target !== dragging) {
            var rect = target.getBoundingClientRect();
            list.insertBefore(dragging, e.clientY > rect.top + rect.height / 2 ? target.nextSibling : target);
        }
    });
    list.addEventListener('drop', function (e) {
        e.preventDefault();
        var ids = [];
        list.querySelectorAll('.drag-hero-row').forEach(function (el) { ids.push(el.dataset.id); });
        var fd = new FormData();
        fd.append('_csrf', '<?= csrf_token() ?>');
        ids.forEach(function (id) { fd.append('ids[]', id); });
        fetch('<?= e(url('/cms/hero/reorder')) ?>', { method: 'POST', body: fd }).catch(function(){});
    });
    list.querySelectorAll('.drag-hero-row').forEach(function (el) {
        el.setAttribute('draggable', 'false');
        var handle = el.querySelector('.drag-handle');
        if (handle) {
            handle.addEventListener('mousedown', function () { el.setAttribute('draggable', 'true'); });
            handle.addEventListener('mouseup',   function () { el.setAttribute('draggable', 'false'); });
        }
    });
}());
</script>
