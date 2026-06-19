<div class="page-heading">
    <h1>Resource Repository</h1>
    <p>Library materials, notes, past papers, and study guides.</p>
</div>
<form class="row g-3 mb-4" method="get" action="<?= e(url('/resources')) ?>">
    <div class="col-md-7"><input class="form-control" name="q" value="<?= e($_GET['q'] ?? '') ?>" placeholder="Search resources"></div>
    <div class="col-md-3">
        <select class="form-select" name="department_id">
            <option value="">All departments</option>
            <?php foreach ($departments as $department): ?>
                <option value="<?= (int) $department['id'] ?>" <?= (string) ($_GET['department_id'] ?? '') === (string) $department['id'] ? 'selected' : '' ?>><?= e($department['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-2"><button class="btn btn-outline-primary w-100">Filter</button></div>
</form>
<?php if (has_role('isr_leader', 'leader_admin', 'system_admin')): ?>
<form class="form-panel mb-4" method="post" action="<?= e(url('/resources')) ?>" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <div class="row g-3">
        <div class="col-md-5"><input class="form-control" name="title" placeholder="Resource title" required></div>
        <div class="col-md-3">
            <select class="form-select" name="category">
                <option value="pdf">PDF</option>
                <option value="notes">Notes</option>
                <option value="past_paper">Past paper</option>
                <option value="study_guide">Study guide</option>
                <option value="other">Other</option>
            </select>
        </div>
        <div class="col-md-4"><input class="form-control" type="file" name="resource" required></div>
        <div class="col-md-4">
            <select class="form-select" name="department_id"><option value="">All departments</option><?php foreach ($departments as $department): ?><option value="<?= (int) $department['id'] ?>"><?= e($department['name']) ?></option><?php endforeach; ?></select>
        </div>
        <div class="col-md-8"><input class="form-control" name="description" placeholder="Short description"></div>
        <div class="col-12"><button class="btn btn-primary">Upload resource</button></div>
    </div>
</form>
<?php endif; ?>
<div class="resource-grid">
    <?php foreach ($resources as $resource): ?>
        <article class="list-card">
            <span class="badge text-bg-secondary"><?= e($resource['category']) ?></span>
            <h2 class="h5 mt-2"><?= e($resource['title']) ?></h2>
            <p><?= e($resource['description']) ?></p>
            <small class="text-secondary"><?= e($resource['department_name'] ?? 'All departments') ?> · <?= number_format((int) $resource['file_size'] / 1024, 1) ?> KB</small>
            <div class="mt-3"><a class="btn btn-sm btn-outline-primary" href="<?= e(url($resource['file_path'])) ?>">Download</a></div>
        </article>
    <?php endforeach; ?>
</div>
