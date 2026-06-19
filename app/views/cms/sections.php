<?php
$pages = ['home' => 'Homepage'];
$currentPage = $page ?? 'home';
$sectionLabels = [
    'stats_announcements' => 'Stats — Announcements label',
    'stats_events'        => 'Stats — Events label',
    'stats_resources'     => 'Stats — Resources label',
    'stats_support'       => 'Stats — Support label',
    'about_heading'       => 'About Section',
    'announcements_section' => 'Announcements Section',
    'events_section'      => 'Events Section',
    'features_heading'    => 'Features Section',
    'cta_heading'         => 'Call to Action Section',
];
// Index by section_key, preserve DB sort_order
$indexed = [];
foreach ($sections as $s) { $indexed[$s['section_key']] = $s; }

// Build ordered list: DB rows first (by sort_order), then any keys not yet in DB
$ordered = [];
$dbSorted = $sections; // already sorted by sort_order from controller
foreach ($dbSorted as $s) {
    if (isset($sectionLabels[$s['section_key']])) {
        $ordered[$s['section_key']] = $sectionLabels[$s['section_key']];
    }
}
// Add any label keys missing from DB
foreach ($sectionLabels as $k => $lbl) {
    if (!isset($ordered[$k])) $ordered[$k] = $lbl;
}
?>

<div class="page-heading d-flex align-items-center justify-content-between flex-wrap gap-3">
    <div>
        <h1><i class="bi bi-layout-text-sidebar me-2 text-primary"></i>Page Sections</h1>
        <p>Edit headings, body text, images, and visibility. Drag rows to reorder.</p>
    </div>
</div>

<div class="d-flex gap-2 mb-4">
    <?php foreach ($pages as $slug => $label): ?>
        <a href="<?= e(url('/cms/sections?page='.urlencode($slug))) ?>"
           class="btn btn-sm <?= $currentPage === $slug ? 'btn-primary' : 'btn-outline-secondary' ?>">
            <?= e($label) ?>
        </a>
    <?php endforeach; ?>
</div>

<div id="sectionsAccordion" class="accordion">
<?php foreach ($ordered as $key => $label):
    $row = $indexed[$key] ?? ['id'=>null,'heading'=>'','subheading'=>'','body'=>'','image'=>'','bg_color'=>'','is_active'=>1,'sort_order'=>0];
    $isActive = (bool)($row['is_active'] ?? 1);
    $rowId = $row['id'] ?? null;
?>
<div class="accordion-item border mb-3 rounded-3 overflow-hidden drag-section"
     data-id="<?= $rowId ?>"
     style="border-color:var(--line)!important">
    <h2 class="accordion-header d-flex align-items-center">
        <span class="drag-handle px-3 text-muted" style="cursor:grab;font-size:18px" title="Drag to reorder">
            <i class="bi bi-grip-vertical"></i>
        </span>
        <button class="accordion-button collapsed fw-semibold flex-fill" type="button"
                data-bs-toggle="collapse" data-bs-target="#sec_<?= $key ?>">
            <?php if (!$isActive): ?>
                <span class="badge bg-secondary me-2">Hidden</span>
            <?php endif; ?>
            <?= e($label) ?>
            <?php if ($row['heading']): ?>
                <span class="text-muted fw-normal ms-2 small">&mdash; <?= e(mb_substr($row['heading'],0,50)) ?></span>
            <?php endif; ?>
        </button>
    </h2>
    <div id="sec_<?= $key ?>" class="accordion-collapse collapse">
        <div class="accordion-body bg-white">
            <form method="post" action="<?= e(url('/cms/sections')) ?>" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <input type="hidden" name="page" value="<?= e($currentPage) ?>">
                <input type="hidden" name="section_key" value="<?= e($key) ?>">
                <input type="hidden" name="image_current" value="<?= e($row['image']) ?>">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Heading</label>
                        <input class="form-control" name="heading" value="<?= e($row['heading']) ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Subheading / Eyebrow</label>
                        <input class="form-control" name="subheading" value="<?= e($row['subheading']) ?>">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Body Text / Description</label>
                        <textarea class="form-control" name="body" rows="4"><?= e($row['body']) ?></textarea>
                        <small class="text-muted">Separate paragraphs with a blank line.</small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Section Image</label>
                        <?php if ($row['image']): ?>
                            <div class="mb-2">
                                <img src="<?= e(url($row['image'])) ?>" style="max-height:80px;border-radius:6px" alt="">
                            </div>
                        <?php endif; ?>
                        <input class="form-control" type="file" name="image" accept="image/*">
                        <small class="text-muted">Max 5MB. Replaces current image if uploaded.</small>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Background Color</label>
                        <input class="form-control" name="bg_color" value="<?= e($row['bg_color']) ?>"
                               placeholder="e.g. bg-white or bg-light">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Sort Order</label>
                        <input class="form-control" type="number" name="sort_order" value="<?= (int)($row['sort_order']??0) ?>">
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" name="is_active"
                                   id="active_<?= $key ?>" value="1" <?= $isActive ? 'checked' : '' ?>>
                            <label class="form-check-label" for="active_<?= $key ?>">Visible</label>
                        </div>
                    </div>
                    <div class="col-12 d-flex gap-2">
                        <button class="btn btn-primary btn-sm px-4">
                            <i class="bi bi-check-lg me-1"></i>Save Section
                        </button>
                        <?php if ($rowId): ?>
                        <form method="post" action="<?= e(url('/cms/sections/delete')) ?>"
                              onsubmit="return confirm('Delete this section? This cannot be undone.')">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id" value="<?= $rowId ?>">
                            <input type="hidden" name="page" value="<?= e($currentPage) ?>">
                            <button class="btn btn-outline-danger btn-sm">
                                <i class="bi bi-trash3 me-1"></i>Delete
                            </button>
                        </form>
                        <?php endif; ?>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endforeach; ?>
</div>

<script>
(function () {
    // Minimal drag-and-drop reorder for sections
    var list = document.getElementById('sectionsAccordion');
    var dragging = null;

    list.addEventListener('dragstart', function (e) {
        dragging = e.target.closest('.drag-section');
        if (dragging) {
            dragging.style.opacity = '0.5';
            e.dataTransfer.effectAllowed = 'move';
        }
    });
    list.addEventListener('dragend', function () {
        if (dragging) { dragging.style.opacity = ''; dragging = null; }
    });
    list.addEventListener('dragover', function (e) {
        e.preventDefault();
        var target = e.target.closest('.drag-section');
        if (target && target !== dragging) {
            var rect = target.getBoundingClientRect();
            var after = e.clientY > rect.top + rect.height / 2;
            list.insertBefore(dragging, after ? target.nextSibling : target);
        }
    });
    list.addEventListener('drop', function (e) {
        e.preventDefault();
        var items = list.querySelectorAll('.drag-section[data-id]');
        var ids = [];
        items.forEach(function (el) { if (el.dataset.id) ids.push(el.dataset.id); });
        if (!ids.length) return;
        var fd = new FormData();
        fd.append('_csrf', '<?= csrf_token() ?>');
        fd.append('page', '<?= e($currentPage) ?>');
        ids.forEach(function (id) { fd.append('ids[]', id); });
        fetch('<?= e(url('/cms/sections/reorder')) ?>', { method: 'POST', body: fd })
            .catch(function () {});
    });

    // Make items draggable only via handle
    list.querySelectorAll('.drag-section').forEach(function (el) {
        el.setAttribute('draggable', 'false');
        var handle = el.querySelector('.drag-handle');
        if (handle) {
            handle.addEventListener('mousedown', function () { el.setAttribute('draggable', 'true'); });
            handle.addEventListener('mouseup',   function () { el.setAttribute('draggable', 'false'); });
        }
    });
}());
</script>
