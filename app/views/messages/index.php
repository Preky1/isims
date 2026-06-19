<div class="page-heading">
    <h1>Messaging Center</h1>
    <p><?= has_role('student') ? 'Your conversations with ISR leaders.' : 'Student support inbox.' ?></p>
</div>

<?php if (has_role('student')): ?>
<div class="form-panel mb-4">
    <h2 class="h5 mb-3">Send New Message</h2>
    <form method="post" action="<?= e(url('/messages')) ?>">
        <?= csrf_field() ?>
        <div class="row g-3">
            <div class="col-md-7">
                <input class="form-control" name="subject" placeholder="Subject" required>
            </div>
            <div class="col-md-5">
                <select class="form-select" name="category" required>
                    <option value="general_inquiry">General Inquiry</option>
                    <option value="academic_concern">Academic Concern</option>
                    <option value="resource_request">Resource Request</option>
                    <option value="event_question">Event Question</option>
                    <option value="complaint">Complaint</option>
                    <option value="suggestion">Suggestion</option>
                </select>
            </div>
            <div class="col-12">
                <textarea class="form-control" name="body" rows="4" placeholder="Describe your concern&hellip;" required></textarea>
            </div>
            <div class="col-12">
                <button class="btn btn-primary">
                    <i class="bi bi-send me-1"></i>Send message
                </button>
            </div>
        </div>
    </form>
</div>
<?php endif; ?>

<div class="row g-4">
    <div class="col-lg-5 vstack gap-2">
        <?php if (! $messages): ?>
            <p class="text-secondary">No messages yet.</p>
        <?php endif; ?>
        <?php foreach ($messages as $message): ?>
            <a class="list-card text-decoration-none <?= (int) ($activeMessageId ?? 0) === (int) $message['id'] ? 'border-primary' : '' ?>"
               href="<?= e(url('/messages?message_id=' . (int) $message['id'])) ?>">
                <div class="d-flex justify-content-between mb-1">
                    <span class="badge text-bg-<?= match($message['status']) {
                        'open'        => 'primary',
                        'in_progress' => 'warning',
                        'resolved'    => 'success',
                        default       => 'secondary'
                    } ?>"><?= e($message['status']) ?></span>
                    <small class="text-secondary"><?= e(str_replace('_', ' ', $message['category'])) ?></small>
                </div>
                <h3 class="h6 text-dark mb-1"><?= e($message['subject']) ?></h3>
                <small class="text-secondary">
                    <?= has_role('student') ? '' : 'From: ' . e($message['student_name']) . ' &middot; ' ?>
                    <?= e(date('M d, Y', strtotime($message['updated_at']))) ?>
                </small>
            </a>
        <?php endforeach; ?>
    </div>

    <div class="col-lg-7">
        <div class="form-panel h-100">
            <?php if ($activeMessageId && $activeMessage): ?>
                <h2 class="h5 mb-1"><?= e($activeMessage['subject']) ?></h2>
                <p class="text-secondary small mb-3">
                    From: <?= e($activeMessage['student_name']) ?> &middot;
                    Status: <strong><?= e($activeMessage['status']) ?></strong>
                </p>
                <div class="border rounded p-3 mb-3 bg-light">
                    <p class="mb-0"><?= nl2br(e($activeMessage['body'])) ?></p>
                </div>
                <?php foreach ($replies as $reply): ?>
                    <div class="thread-reply <?= $reply['role_slug'] !== 'student' ? 'border-primary' : '' ?>">
                        <strong><?= e($reply['name']) ?></strong>
                        <span class="badge text-bg-secondary ms-1"><?= e(str_replace('_', ' ', $reply['role_slug'])) ?></span>
                        <p class="mt-1 mb-0"><?= nl2br(e($reply['body'])) ?></p>
                        <small class="text-secondary"><?= e(date('M d, Y H:i', strtotime($reply['created_at']))) ?></small>
                    </div>
                <?php endforeach; ?>

                <?php if ($activeMessage['status'] !== 'resolved'): ?>
                    <form method="post" action="<?= e(url('/messages/reply')) ?>" class="vstack gap-3 mt-3">
                        <?= csrf_field() ?>
                        <input type="hidden" name="message_id" value="<?= (int) $activeMessageId ?>">
                        <?php if (has_role('isr_leader', 'leader_admin', 'system_admin')): ?>
                            <select class="form-select" name="status">
                                <option value="in_progress">In Progress</option>
                                <option value="resolved">Mark Resolved</option>
                                <option value="open">Keep Open</option>
                            </select>
                        <?php else: ?>
                            <input type="hidden" name="status" value="open">
                        <?php endif; ?>
                        <textarea class="form-control" name="body" rows="4"
                                  placeholder="Write your reply&hellip;" required></textarea>
                        <button class="btn btn-primary">
                            <i class="bi bi-reply me-1"></i>Post reply
                        </button>
                    </form>
                <?php else: ?>
                    <div class="alert alert-success mt-3 mb-0">This conversation is resolved.</div>
                <?php endif; ?>
            <?php else: ?>
                <p class="text-secondary">Select a message from the left to view the thread.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
