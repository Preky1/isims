<div class="page-heading d-flex justify-content-between align-items-start">
    <div>
        <h1>Notifications</h1>
        <p>Your latest activity alerts.</p>
    </div>
    <form method="post" action="<?= e(url('/notifications/read-all')) ?>">
        <?= csrf_field() ?>
        <button class="btn btn-outline-secondary btn-sm">Mark all as read</button>
    </form>
</div>
<div class="vstack gap-3">
    <?php if (! $notifications): ?>
        <p class="text-secondary">No notifications yet.</p>
    <?php endif; ?>
    <?php foreach ($notifications as $note): ?>
        <article class="list-card <?= $note['read_at'] ? 'opacity-60' : '' ?>">
            <div class="d-flex justify-content-between align-items-start gap-3">
                <div>
                    <span class="badge text-bg-secondary"><?= e($note['type']) ?></span>
                    <h2 class="h6 mt-2 mb-1"><?= e($note['title']) ?></h2>
                    <?php if ($note['body']): ?><p class="mb-0"><?= e($note['body']) ?></p><?php endif; ?>
                    <small class="text-secondary"><?= e(date('M d, Y H:i', strtotime($note['created_at']))) ?></small>
                </div>
                <?php if (! $note['read_at']): ?>
                    <form method="post" action="<?= e(url('/notifications/read')) ?>" class="flex-shrink-0">
                        <?= csrf_field() ?>
                        <input type="hidden" name="id" value="<?= (int) $note['id'] ?>">
                        <button class="btn btn-sm btn-outline-primary">Mark read</button>
                    </form>
                <?php endif; ?>
            </div>
        </article>
    <?php endforeach; ?>
</div>
