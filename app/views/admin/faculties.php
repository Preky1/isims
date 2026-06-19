<?php
$levelLabels = ['bachelor'=>'Bachelor','master'=>'Master','doctorate'=>'Doctorate','diploma'=>'Diploma','certificate'=>'Certificate'];
$levelColors = ['bachelor'=>'primary','master'=>'success','doctorate'=>'danger','diploma'=>'warning','certificate'=>'secondary'];
?>

<div class="page-heading d-flex align-items-center justify-content-between flex-wrap gap-3">
    <div>
        <h1>Faculties &amp; Programs</h1>
        <p>Manage university faculties, departments, and academic programs.</p>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addFacultyModal">
        <i class="bi bi-plus-lg me-1"></i>Add Faculty
    </button>
</div>

<?php if (empty($faculties)): ?>
    <div class="empty-state">
        <i class="bi bi-diagram-3"></i>
        <h5>No faculties yet</h5>
        <p>Add your first faculty to get started.</p>
    </div>
<?php else: ?>

<div class="row g-4">
<?php foreach ($faculties as $fac):
    $model    = new Faculty();
    $depts    = $model->departments((int)$fac['id']);
    $programs = $model->programs((int)$fac['id']);
    $bCount   = count(array_filter($programs, fn($p)=>$p['level']==='bachelor'));
    $mCount   = count(array_filter($programs, fn($p)=>$p['level']==='master'));
?>
<div class="col-12">
<div class="fac-card" style="--fac-color:<?= e($fac['color']) ?>">

    <!-- Faculty Header -->
    <div class="fac-header">
        <div class="fac-color-bar"></div>
        <div class="fac-header-body">
            <div class="d-flex align-items-start justify-content-between gap-3 flex-wrap">
                <div class="d-flex align-items-center gap-3">
                    <div class="fac-badge" style="background:<?= e($fac['color']) ?>">
                        <?= e(strtoupper(substr($fac['code'],0,2))) ?>
                    </div>
                    <div>
                        <h5 class="fac-name mb-0"><?= e($fac['name']) ?></h5>
                        <span class="fac-code-tag"><?= e($fac['code']) ?></span>
                        <?php if (!$fac['is_active']): ?>
                            <span class="badge bg-secondary ms-1">Inactive</span>
                        <?php endif; ?>
                        <?php if ($fac['description']): ?>
                            <p class="fac-desc mb-0 mt-1"><?= e($fac['description']) ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <div class="fac-stat"><strong><?= (int)$fac['dept_count'] ?></strong><span>Depts</span></div>
                    <div class="fac-stat"><strong><?= (int)$fac['program_count'] ?></strong><span>Programs</span></div>
                    <?php if ($bCount): ?><div class="fac-stat"><strong><?= $bCount ?></strong><span>BSc/BA/BBA</span></div><?php endif; ?>
                    <?php if ($mCount): ?><div class="fac-stat"><strong><?= $mCount ?></strong><span>Masters</span></div><?php endif; ?>
                    <div class="d-flex align-items-center gap-1 ms-2">
                        <button class="btn btn-sm btn-outline-secondary"
                                data-bs-toggle="modal" data-bs-target="#editFacultyModal"
                                data-id="<?= $fac['id'] ?>"
                                data-name="<?= e($fac['name']) ?>"
                                data-code="<?= e($fac['code']) ?>"
                                data-desc="<?= e($fac['description']) ?>"
                                data-color="<?= e($fac['color']) ?>"
                                data-active="<?= $fac['is_active'] ?>">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger"
                                data-bs-toggle="modal" data-bs-target="#deleteFacultyModal"
                                data-id="<?= $fac['id'] ?>"
                                data-name="<?= e($fac['name']) ?>">
                            <i class="bi bi-trash3"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="fac-body row g-0">

        <!-- Departments column -->
        <div class="col-md-5 fac-col-left">
            <div class="fac-section-title">
                <i class="bi bi-collection me-1"></i>Departments
                <button class="btn btn-xs ms-auto"
                        data-bs-toggle="modal" data-bs-target="#addDeptModal"
                        data-faculty-id="<?= $fac['id'] ?>"
                        data-faculty-name="<?= e($fac['name']) ?>">
                    <i class="bi bi-plus"></i> Add
                </button>
            </div>
            <?php if (empty($depts)): ?>
                <p class="text-muted small px-3 pb-3">No departments yet.</p>
            <?php else: ?>
                <ul class="dept-list">
                <?php foreach ($depts as $d): ?>
                    <li class="dept-item">
                        <div class="dept-dot" style="background:<?= e($fac['color']) ?>"></div>
                        <div class="dept-info">
                            <span class="dept-name"><?= e($d['name']) ?></span>
                            <span class="dept-code"><?= e($d['code']) ?></span>
                        </div>
                        <?php if ($d['student_count'] > 0): ?>
                            <span class="dept-students"><?= (int)$d['student_count'] ?> <i class="bi bi-people-fill"></i></span>
                        <?php endif; ?>
                        <form method="post" action="<?= e(url('/admin/departments/delete')) ?>" class="ms-auto">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id" value="<?= $d['id'] ?>">
                            <button class="btn-icon-danger" type="submit"
                                    onclick="return confirm('Delete department <?= e($d['name']) ?>?')">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </form>
                    </li>
                <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>

        <!-- Programs column -->
        <div class="col-md-7 fac-col-right">
            <div class="fac-section-title">
                <i class="bi bi-mortarboard me-1"></i>Academic Programs
                <button class="btn btn-xs ms-auto"
                        data-bs-toggle="modal" data-bs-target="#addProgramModal"
                        data-faculty-id="<?= $fac['id'] ?>"
                        data-faculty-name="<?= e($fac['name']) ?>"
                        data-depts="<?= e(json_encode(array_map(fn($d)=>['id'=>$d['id'],'name'=>$d['name']], $depts))) ?>">
                    <i class="bi bi-plus"></i> Add
                </button>
            </div>
            <?php if (empty($programs)): ?>
                <p class="text-muted small px-3 pb-3">No programs yet.</p>
            <?php else: ?>
                <div class="programs-grid">
                <?php foreach ($programs as $p): ?>
                    <div class="program-item">
                        <span class="badge bg-<?= $levelColors[$p['level']] ?? 'secondary' ?> level-badge">
                            <?= $levelLabels[$p['level']] ?? $p['level'] ?>
                        </span>
                        <span class="program-name"><?= e($p['name']) ?></span>
                        <form method="post" action="<?= e(url('/admin/programs/delete')) ?>" class="ms-auto flex-shrink-0">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id" value="<?= $p['id'] ?>">
                            <button class="btn-icon-danger" type="submit"
                                    onclick="return confirm('Delete program <?= e($p['name']) ?>?')">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </form>
                    </div>
                <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

    </div><!-- /fac-body -->
</div><!-- /fac-card -->
</div>
<?php endforeach; ?>
</div>

<?php endif; ?>

<!-- ── Add Faculty Modal ──────────────────────────────────────────── -->
<div class="modal fade" id="addFacultyModal" tabindex="-1">
  <div class="modal-dialog modal-md">
    <form class="modal-content" method="post" action="<?= e(url('/admin/faculties')) ?>">
      <?= csrf_field() ?>
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-bold"><i class="bi bi-plus-circle me-2 text-primary"></i>Add Faculty</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="row g-3">
          <div class="col-md-8">
            <label class="form-label fw-semibold">Faculty Name <span class="text-danger">*</span></label>
            <input class="form-control" name="name" placeholder="e.g. Faculty of Computing" required>
          </div>
          <div class="col-md-4">
            <label class="form-label fw-semibold">Code <span class="text-danger">*</span></label>
            <input class="form-control text-uppercase" name="code" placeholder="e.g. FCIS" required>
          </div>
          <div class="col-12">
            <label class="form-label fw-semibold">Description</label>
            <input class="form-control" name="description" placeholder="Short description (optional)">
          </div>
          <div class="col-md-6">
            <label class="form-label fw-semibold">Faculty Color</label>
            <div class="d-flex gap-2 align-items-center">
              <input type="color" class="form-control form-control-color" name="color" value="#1f6feb">
              <span class="text-muted small">Used for visual identity</span>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer border-0 pt-0">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Create Faculty</button>
      </div>
    </form>
  </div>
</div>

<!-- ── Edit Faculty Modal ─────────────────────────────────────────── -->
<div class="modal fade" id="editFacultyModal" tabindex="-1">
  <div class="modal-dialog modal-md">
    <form class="modal-content" method="post" action="<?= e(url('/admin/faculties/update')) ?>">
      <?= csrf_field() ?>
      <input type="hidden" name="id" id="edit_fac_id">
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2 text-primary"></i>Edit Faculty</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="row g-3">
          <div class="col-md-8">
            <label class="form-label fw-semibold">Faculty Name <span class="text-danger">*</span></label>
            <input class="form-control" name="name" id="edit_fac_name" required>
          </div>
          <div class="col-md-4">
            <label class="form-label fw-semibold">Code <span class="text-danger">*</span></label>
            <input class="form-control text-uppercase" name="code" id="edit_fac_code" required>
          </div>
          <div class="col-12">
            <label class="form-label fw-semibold">Description</label>
            <input class="form-control" name="description" id="edit_fac_desc">
          </div>
          <div class="col-md-6">
            <label class="form-label fw-semibold">Faculty Color</label>
            <input type="color" class="form-control form-control-color" name="color" id="edit_fac_color">
          </div>
          <div class="col-md-6">
            <label class="form-label fw-semibold">Status</label>
            <select class="form-select" name="is_active" id="edit_fac_active">
              <option value="1">Active</option>
              <option value="0">Inactive</option>
            </select>
          </div>
        </div>
      </div>
      <div class="modal-footer border-0 pt-0">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Save Changes</button>
      </div>
    </form>
  </div>
</div>

<!-- ── Delete Faculty Modal ───────────────────────────────────────── -->
<div class="modal fade" id="deleteFacultyModal" tabindex="-1">
  <div class="modal-dialog modal-sm">
    <form class="modal-content" method="post" action="<?= e(url('/admin/faculties/delete')) ?>">
      <?= csrf_field() ?>
      <input type="hidden" name="id" id="del_fac_id">
      <div class="modal-body text-center py-4">
        <div class="del-icon mb-3"><i class="bi bi-exclamation-triangle-fill text-danger" style="font-size:2.5rem"></i></div>
        <h6 class="fw-bold mb-1">Delete Faculty?</h6>
        <p class="text-muted small mb-0">This will also delete all departments and programs under <strong id="del_fac_name"></strong>. This cannot be undone.</p>
      </div>
      <div class="modal-footer justify-content-center border-0 pt-0">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-danger">Delete</button>
      </div>
    </form>
  </div>
</div>

<!-- ── Add Department Modal ───────────────────────────────────────── -->
<div class="modal fade" id="addDeptModal" tabindex="-1">
  <div class="modal-dialog modal-sm">
    <form class="modal-content" method="post" action="<?= e(url('/admin/departments')) ?>">
      <?= csrf_field() ?>
      <input type="hidden" name="faculty_id" id="dept_faculty_id">
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-bold"><i class="bi bi-collection me-2 text-primary"></i>Add Department</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p class="text-muted small mb-3">Faculty: <strong id="dept_faculty_name"></strong></p>
        <div class="mb-3">
          <label class="form-label fw-semibold">Department Name <span class="text-danger">*</span></label>
          <input class="form-control" name="name" placeholder="e.g. Information Technology" required>
        </div>
        <div>
          <label class="form-label fw-semibold">Code <span class="text-danger">*</span></label>
          <input class="form-control text-uppercase" name="code" placeholder="e.g. IT" required>
        </div>
      </div>
      <div class="modal-footer border-0 pt-0">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Add Department</button>
      </div>
    </form>
  </div>
</div>

<!-- ── Add Program Modal ──────────────────────────────────────────── -->
<div class="modal fade" id="addProgramModal" tabindex="-1">
  <div class="modal-dialog modal-md">
    <form class="modal-content" method="post" action="<?= e(url('/admin/programs')) ?>">
      <?= csrf_field() ?>
      <input type="hidden" name="faculty_id" id="prog_faculty_id">
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-bold"><i class="bi bi-mortarboard me-2 text-primary"></i>Add Program</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p class="text-muted small mb-3">Faculty: <strong id="prog_faculty_name"></strong></p>
        <div class="row g-3">
          <div class="col-12">
            <label class="form-label fw-semibold">Program Name <span class="text-danger">*</span></label>
            <input class="form-control" name="name" placeholder="e.g. BSc in Information Technology" required>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-semibold">Level <span class="text-danger">*</span></label>
            <select class="form-select" name="level" required>
              <option value="bachelor">Bachelor</option>
              <option value="master">Master</option>
              <option value="doctorate">Doctorate</option>
              <option value="diploma">Diploma</option>
              <option value="certificate">Certificate</option>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-semibold">Department (optional)</label>
            <select class="form-select" name="department_id" id="prog_dept_select">
              <option value="">— None —</option>
            </select>
          </div>
        </div>
      </div>
      <div class="modal-footer border-0 pt-0">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Add Program</button>
      </div>
    </form>
  </div>
</div>

<script>
/* Edit Faculty */
document.getElementById('editFacultyModal').addEventListener('show.bs.modal', function(e) {
    var b = e.relatedTarget;
    document.getElementById('edit_fac_id').value    = b.dataset.id;
    document.getElementById('edit_fac_name').value  = b.dataset.name;
    document.getElementById('edit_fac_code').value  = b.dataset.code;
    document.getElementById('edit_fac_desc').value  = b.dataset.desc;
    document.getElementById('edit_fac_color').value = b.dataset.color;
    document.getElementById('edit_fac_active').value = b.dataset.active;
});

/* Delete Faculty */
document.getElementById('deleteFacultyModal').addEventListener('show.bs.modal', function(e) {
    var b = e.relatedTarget;
    document.getElementById('del_fac_id').value = b.dataset.id;
    document.getElementById('del_fac_name').textContent = b.dataset.name;
});

/* Add Department */
document.getElementById('addDeptModal').addEventListener('show.bs.modal', function(e) {
    var b = e.relatedTarget;
    document.getElementById('dept_faculty_id').value = b.dataset.facultyId;
    document.getElementById('dept_faculty_name').textContent = b.dataset.facultyName;
});

/* Add Program */
document.getElementById('addProgramModal').addEventListener('show.bs.modal', function(e) {
    var b = e.relatedTarget;
    document.getElementById('prog_faculty_id').value    = b.dataset.facultyId;
    document.getElementById('prog_faculty_name').textContent = b.dataset.facultyName;
    var sel = document.getElementById('prog_dept_select');
    sel.innerHTML = '<option value="">— None —</option>';
    var depts = JSON.parse(b.dataset.depts || '[]');
    depts.forEach(function(d) {
        var o = document.createElement('option');
        o.value = d.id; o.textContent = d.name;
        sel.appendChild(o);
    });
});
</script>
