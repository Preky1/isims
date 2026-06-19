<div class="page-heading">
    <h1>FAQ Knowledge Base</h1>
    <p>Search common answers before opening a support message.</p>
</div>

<form class="row g-3 mb-4" method="get" action="<?= e(url('/faqs')) ?>">
    <div class="col-md-6">
        <input class="form-control" name="q" value="<?= e($_GET['q'] ?? '') ?>" placeholder="Search FAQs&hellip;">
    </div>
    <div class="col-md-4">
        <select class="form-select" name="category">
            <option value="">All categories</option>
            <?php foreach (['academic','immigration','registration','campus_life','resources','general'] as $cat): ?>
                <option value="<?= $cat ?>" <?= ($_GET['category'] ?? '') === $cat ? 'selected' : '' ?>>
                    <?= ucfirst(str_replace('_', ' ', $cat)) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-2">
        <button class="btn btn-outline-primary w-100">Search</button>
    </div>
</form>

<?php if (has_role('isr_leader', 'leader_admin', 'system_admin')): ?>
<div class="form-panel mb-4">
    <?php if ($editFaq): ?>
        <h2 class="h5 mb-3">Edit FAQ</h2>
        <form method="post" action="<?= e(url('/faqs/update')) ?>">
            <?= csrf_field() ?>
            <input type="hidden" name="id" value="<?= (int) $editFaq['id'] ?>">
    <?php else: ?>
        <h2 class="h5 mb-3">Add New FAQ</h2>
        <form method="post" action="<?= e(url('/faqs')) ?>">
            <?= csrf_field() ?>
    <?php endif; ?>
        <div class="row g-3">
            <div class="col-md-4">
                <select class="form-select" name="category">
                    <?php foreach (['academic','immigration','registration','campus_life','resources','general'] as $cat): ?>
                        <option value="<?= $cat ?>"
                            <?= ($editFaq['category'] ?? 'general') === $cat ? 'selected' : '' ?>>
                            <?= ucfirst(str_replace('_', ' ', $cat)) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-8">
                <input class="form-control" name="question"
                       value="<?= e($editFaq['question'] ?? '') ?>"
                       placeholder="Question" required>
            </div>
            <div class="col-12">
                <textarea class="form-control" name="answer" rows="3"
                          placeholder="Answer" required><?= e($editFaq['answer'] ?? '') ?></textarea>
            </div>
            <div class="col-12 d-flex gap-2">
                <button class="btn btn-primary">
                    <?= $editFaq ? 'Update FAQ' : 'Add FAQ' ?>
                </button>
                <?php if ($editFaq): ?>
                    <a href="<?= e(url('/faqs')) ?>" class="btn btn-outline-secondary">Cancel</a>
                <?php endif; ?>
            </div>
        </div>
    </form>
</div>
<?php endif; ?>

<div class="vstack gap-3">
    <?php if (! $faqs): ?>
        <p class="text-secondary">No FAQs found. Try a different search.</p>
    <?php endif; ?>
    <?php foreach ($faqs as $faq): ?>
        <article class="list-card">
            <div class="d-flex justify-content-between gap-3">
                <div class="flex-grow-1">
                    <span class="badge text-bg-secondary mb-2">
                        <?= ucfirst(str_replace('_', ' ', e($faq['category']))) ?>
                    </span>
                    <h2 class="h5 mb-1"><?= e($faq['question']) ?></h2>
                    <p class="mb-0"><?= nl2br(e($faq['answer'])) ?></p>
                </div>
                <?php if (has_role('isr_leader', 'leader_admin', 'system_admin')): ?>
                    <div class="d-flex flex-column gap-1 flex-shrink-0">
                        <a href="<?= e(url('/faqs?edit=' . (int) $faq['id'])) ?>"
                           class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form method="post" action="<?= e(url('/faqs/delete')) ?>"
                              onsubmit="return confirm('Delete this FAQ?')">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id" value="<?= (int) $faq['id'] ?>">
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
