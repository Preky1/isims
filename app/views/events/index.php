<div class="page-heading">
    <h1>Calendar &amp; Events</h1>
    <p>Academic dates, meetings, seminars, sports, and campus events.</p>
</div>

<?php if (has_role('isr_leader', 'leader_admin', 'system_admin')): ?>
<div class="form-panel mb-4">
    <h2 class="h5 mb-3">Create New Event</h2>
    <form method="post" action="<?= e(url('/events')) ?>">
        <?= csrf_field() ?>
        <div class="row g-3">
            <div class="col-md-5">
                <input class="form-control" name="title" placeholder="Event title" required>
            </div>
            <div class="col-md-3">
                <select class="form-select" name="category">
                    <option value="general">General</option>
                    <option value="prayer_week">Prayer Week</option>
                    <option value="sports">Sports</option>
                    <option value="seminar">Seminar</option>
                    <option value="meeting">Meeting</option>
                </select>
            </div>
            <div class="col-md-4">
                <input class="form-control" name="location" placeholder="Location (optional)">
            </div>
            <div class="col-md-6">
                <label class="form-label text-secondary small">Start</label>
                <input class="form-control" type="datetime-local" name="starts_at" required>
            </div>
            <div class="col-md-6">
                <label class="form-label text-secondary small">End (optional)</label>
                <input class="form-control" type="datetime-local" name="ends_at">
            </div>
            <div class="col-12">
                <textarea class="form-control" name="description" rows="3" placeholder="Description"></textarea>
            </div>
            <div class="col-12">
                <button class="btn btn-primary">
                    <i class="bi bi-calendar-plus me-1"></i>Create event
                </button>
            </div>
        </div>
    </form>
</div>
<?php endif; ?>

<div class="row g-4">
    <section class="col-lg-7">
        <h2 class="section-title">Upcoming Events</h2>
        <?php if (! $events): ?>
            <p class="text-secondary">No upcoming events.</p>
        <?php endif; ?>
        <?php foreach ($events as $event): ?>
            <article class="list-card mb-3">
                <div class="d-flex justify-content-between gap-2">
                    <div>
                        <span class="badge text-bg-secondary mb-1"><?= e($event['category']) ?></span>
                        <h3 class="h5 mb-1"><?= e($event['title']) ?></h3>
                        <?php if ($event['description']): ?>
                            <p class="mb-1"><?= e($event['description']) ?></p>
                        <?php endif; ?>
                        <small class="text-secondary">
                            <?php if ($event['location']): ?>
                                <i class="bi bi-geo-alt"></i> <?= e($event['location']) ?> &middot;
                            <?php endif; ?>
                            <i class="bi bi-clock"></i> <?= e(date('M d, Y H:i', strtotime($event['starts_at']))) ?>
                            <?= $event['ends_at'] ? ' &ndash; ' . e(date('H:i', strtotime($event['ends_at']))) : '' ?>
                        </small>
                    </div>
                    <?php if (has_role('isr_leader', 'leader_admin', 'system_admin')): ?>
                        <form method="post" action="<?= e(url('/events/delete')) ?>" class="flex-shrink-0"
                              onsubmit="return confirm('Delete this event?')">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id" value="<?= (int) $event['id'] ?>">
                            <button class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </article>
        <?php endforeach; ?>
    </section>
    <section class="col-lg-5">
        <h2 class="section-title">Academic Calendar</h2>
        <?php if (! $calendar): ?>
            <p class="text-secondary">No calendar entries.</p>
        <?php endif; ?>
        <?php foreach ($calendar as $item): ?>
            <article class="list-card mb-3">
                <strong><?= e($item['title']) ?></strong>
                <p class="mb-0 mt-1">
                    <span class="badge text-bg-secondary"><?= e(str_replace('_', ' ', $item['event_type'])) ?></span>
                    <br>
                    <small class="text-secondary">
                        <?= e($item['starts_on']) ?>
                        <?= $item['ends_on'] ? ' to ' . e($item['ends_on']) : '' ?>
                    </small>
                </p>
                <?php if ($item['notes']): ?>
                    <p class="mt-1 mb-0 small"><?= e($item['notes']) ?></p>
                <?php endif; ?>
            </article>
        <?php endforeach; ?>
    </section>
</div>
