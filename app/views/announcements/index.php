<div class="page-heading">
    <h1>Announcements</h1>
    <p>Public, student-only, and department-specific updates.</p>
</div>

<?php if (has_role('isr_leader', 'leader_admin', 'system_admin')): ?>
<div class="form-panel mb-4">
    <h2 class="h5 mb-3">Publish New Announcement</h2>
    <form method="post" action="<?= e(url('/announcements')) ?>">
        <?= csrf_field() ?>
        <div class="row g-3">
            <div class="col-md-6">
                <input class="form-control" name="title" placeholder="Announcement title" required>
            </div>
            <div class="col-md-3">
                <select class="form-select" name="audience">
                    <option value="public">Public (everyone)</option>
                    <option value="students">Students only</option>
                    <option value="department">Department-specific</option>
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-select" name="department_id">
                    <option value="">No department</option>
                    <?php foreach ($departments as $department): ?>
                        <option value="<?= (int) $department['id'] ?>"><?= e($department['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-12">
                <textarea class="form-control" name="body" rows="4" placeholder="Announcement details" required></textarea>
            </div>
            <div class="col-12">
                <button class="btn btn-primary">
                    <i class="bi bi-megaphone me-1"></i>Publish announcement
                </button>
            </div>
        </div>
    </form>
</div>
<?php endif; ?>

<div class="vstack gap-3">
    <?php if (! $announcements): ?>
        <p class="text-secondary">No announcements at the moment.</p>
    <?php endif; ?>
    <?php foreach ($announcements as $announcement): ?>
        <?php
        $audienceBadge = match($announcement['audience']) {
            'students'   => 'text-bg-primary',
            'department' => 'text-bg-warning',
            default      => 'text-bg-secondary',
        };
        ?>
        <article class="list-card">
            <div class="d-flex justify-content-between gap-3">
                <div class="flex-grow-1 min-w-0">
                    <span class="badge <?= $audienceBadge ?> mb-2">
                        <?= e($announcement['audience']) ?>
                        <?= $announcement['department_name'] ? '· ' . e($announcement['department_name']) : '' ?>
                    </span>
                    <h2 class="h5 mb-1"><?= e($announcement['title']) ?></h2>
                    <p class="mb-2"><?= nl2br(e($announcement['body'])) ?></p>
                    <small class="text-secondary">
                        By <?= e($announcement['author_name']) ?> &middot;
                        <?= e(date('M d, Y', strtotime($announcement['published_at'] ?? $announcement['created_at']))) ?>
                    </small>
                </div>
                <?php if (has_role('isr_leader', 'leader_admin', 'system_admin')): ?>
                    <div class="d-flex flex-column gap-1 flex-shrink-0">
                        <form method="post" action="<?= e(url('/announcements/archive')) ?>">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id" value="<?= (int) $announcement['id'] ?>">
                            <button class="btn btn-sm btn-outline-secondary w-100">
                                <i class="bi bi-archive"></i>
                            </button>
                        </form>
                        <form method="post" action="<?= e(url('/announcements/delete')) ?>"
                              onsubmit="return confirm('Delete this announcement?')">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id" value="<?= (int) $announcement['id'] ?>">
                            <button class="btn btn-sm btn-outline-danger w-100">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
        </article>
    <?php endforeach; ?>
</div>
