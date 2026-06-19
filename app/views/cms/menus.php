<div class="page-heading d-flex align-items-center justify-content-between flex-wrap gap-3">
    <div>
        <h1><i class="bi bi-list-ul me-2 text-primary"></i>Navigation Menus</h1>
        <p>Manage header and footer navigation links. Drag rows to reorder.</p>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#menuModal" data-mode="add">
        <i class="bi bi-plus-lg me-1"></i>Add Menu Item
    </button>
</div>

<?php
$header = array_filter($menus, fn($m) => $m['location'] === 'header');
$footer = array_filter($menus, fn($m) => $m['location'] === 'footer');

function renderMenuTable(array $items, string $title, string $loc): void { ?>
<div class="settings-card mb-4">
    <div class="settings-card-header"><i class="bi bi-layout-wtf me-2"></i><?= e($title) ?></div>
    <div class="table-responsive">
    <?php if (empty($items)): ?>
        <p class="text-muted p-3 mb-0">No items yet.</p>
    <?php else: ?>
    <table class="table align-middle mb-0">
        <thead class="table-light"><tr>
            <th style="width:32px"></th><th>#</th><th>Label</th><th>URL</th><th>Icon</th><th>Target</th><th>Status</th><th class="text-end">Actions</th>
        </tr></thead>
        <tbody id="menu-tbody-<?= e($loc) ?>">
        <?php foreach ($items as $item): ?>
        <tr class="drag-menu-row" data-id="<?= $item['id'] ?>" draggable="false">
            <td><span class="drag-handle text-muted" style="cursor:grab;font-size:18px"><i class="bi bi-grip-vertical"></i></span></td>
            <td><?= (int)$item['sort_order'] ?></td>
            <td><strong><?= e($item['label']) ?></strong></td>
            <td><code><?= e($item['url']) ?></code></td>
            <td><?php if ($item['icon']): ?><i class="bi <?= e($item['icon']) ?>"></i> <small><?= e($item['icon']) ?></small><?php endif; ?></td>
            <td><small><?= e($item['target']) ?></small></td>
            <td>
                <span class="badge bg-<?= $item['is_active'] ? 'success' : 'secondary' ?>">
                    <?= $item['is_active'] ? 'Active' : 'Hidden' ?>
                </span>
            </td>
            <td class="text-end">
                <button class="btn btn-sm btn-outline-primary me-1"
                        data-bs-toggle="modal" data-bs-target="#menuModal"
                        data-mode="edit"
                        data-id="<?= $item['id'] ?>"
                        data-label="<?= e($item['label']) ?>"
                        data-url="<?= e($item['url']) ?>"
                        data-target="<?= e($item['target']) ?>"
                        data-icon="<?= e($item['icon']) ?>"
                        data-location="<?= e($item['location']) ?>"
                        data-order="<?= (int)$item['sort_order'] ?>"
                        data-active="<?= (int)$item['is_active'] ?>">
                    <i class="bi bi-pencil"></i>
                </button>
                <form method="post" action="<?= e(url('/cms/menus/delete')) ?>" class="d-inline"
                      onsubmit="return confirm('Delete this menu item?')">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" value="<?= $item['id'] ?>">
                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash3"></i></button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
    </div>
</div>
<?php }

renderMenuTable($header, 'Header Navigation', 'header');
renderMenuTable($footer, 'Footer Quick Links', 'footer');
?>

<!-- Menu Modal -->
<div class="modal fade" id="menuModal" tabindex="-1">
  <div class="modal-dialog">
    <form class="modal-content" method="post" action="<?= e(url('/cms/menus')) ?>">
      <?= csrf_field() ?>
      <input type="hidden" name="id" id="menu_id">
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-bold" id="menuModalTitle">
            <i class="bi bi-list-ul me-2 text-primary"></i>Menu Item
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label fw-semibold">Label <span class="text-danger">*</span></label>
            <input class="form-control" name="label" id="menu_label" required>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-semibold">URL <span class="text-danger">*</span></label>
            <input class="form-control" name="url" id="menu_url" placeholder="/login" required>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-semibold">Bootstrap Icon class</label>
            <input class="form-control" name="icon" id="menu_icon" placeholder="bi-box-arrow-in-right">
            <small class="text-muted">e.g. <code>bi-person-plus</code></small>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-semibold">Opens in</label>
            <select class="form-select" name="target" id="menu_target">
              <option value="_self">Same tab</option>
              <option value="_blank">New tab</option>
            </select>
          </div>
          <div class="col-md-5">
            <label class="form-label fw-semibold">Location</label>
            <select class="form-select" name="location" id="menu_location">
              <option value="header">Header</option>
              <option value="footer">Footer</option>
            </select>
          </div>
          <div class="col-md-4">
            <label class="form-label fw-semibold">Sort Order</label>
            <input class="form-control" type="number" name="sort_order" id="menu_order" value="0">
          </div>
          <div class="col-md-3 d-flex align-items-end">
            <div class="form-check form-switch mb-2">
              <input class="form-check-input" type="checkbox" name="is_active" id="menu_active" value="1" checked>
              <label class="form-check-label" for="menu_active">Visible</label>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer border-0 pt-0">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Save Item</button>
      </div>
    </form>
  </div>
</div>

<script>
document.getElementById('menuModal').addEventListener('show.bs.modal', function(e) {
    var b = e.relatedTarget;
    var edit = b.dataset.mode === 'edit';
    document.getElementById('menuModalTitle').innerHTML =
        '<i class="bi bi-list-ul me-2 text-primary"></i>' + (edit ? 'Edit Menu Item' : 'Add Menu Item');
    document.getElementById('menu_id').value       = edit ? b.dataset.id : '';
    document.getElementById('menu_label').value    = edit ? b.dataset.label : '';
    document.getElementById('menu_url').value      = edit ? b.dataset.url : '';
    document.getElementById('menu_icon').value     = edit ? b.dataset.icon : '';
    document.getElementById('menu_target').value   = edit ? b.dataset.target : '_self';
    document.getElementById('menu_location').value = edit ? b.dataset.location : 'header';
    document.getElementById('menu_order').value    = edit ? b.dataset.order : '0';
    document.getElementById('menu_active').checked = edit ? b.dataset.active === '1' : true;
});

(function () {
    ['header','footer'].forEach(function (loc) {
        var tbody = document.getElementById('menu-tbody-' + loc);
        if (!tbody) return;
        var dragging = null;
        tbody.addEventListener('dragstart', function (e) {
            dragging = e.target.closest('tr.drag-menu-row');
            if (dragging) dragging.style.opacity = '0.5';
        });
        tbody.addEventListener('dragend', function () {
            if (dragging) { dragging.style.opacity = ''; dragging = null; }
        });
        tbody.addEventListener('dragover', function (e) {
            e.preventDefault();
            var target = e.target.closest('tr.drag-menu-row');
            if (target && target !== dragging) {
                var rect = target.getBoundingClientRect();
                tbody.insertBefore(dragging, e.clientY > rect.top + rect.height / 2 ? target.nextSibling : target);
            }
        });
        tbody.addEventListener('drop', function (e) {
            e.preventDefault();
            var ids = [];
            tbody.querySelectorAll('tr.drag-menu-row').forEach(function (r) { ids.push(r.dataset.id); });
            var fd = new FormData();
            fd.append('_csrf', '<?= csrf_token() ?>');
            ids.forEach(function (id) { fd.append('ids[]', id); });
            fetch('<?= e(url('/cms/menus/reorder')) ?>', { method: 'POST', body: fd }).catch(function(){});
        });
        tbody.querySelectorAll('tr.drag-menu-row').forEach(function (row) {
            row.setAttribute('draggable', 'false');
            var handle = row.querySelector('.drag-handle');
            if (handle) {
                handle.addEventListener('mousedown', function () { row.setAttribute('draggable', 'true'); });
                handle.addEventListener('mouseup',   function () { row.setAttribute('draggable', 'false'); });
            }
        });
    });
}());
</script>
