<div class="page-heading">
    <h1>System Reports</h1>
    <p>High-level statistics and operational visibility.</p>
</div>

<div class="metric-grid mb-4" style="grid-template-columns:repeat(4,minmax(0,1fr))">
    <div class="metric"><span>Students</span><strong><?= (int) $stats['students'] ?></strong></div>
    <div class="metric"><span>ISR Leaders</span><strong><?= (int) $stats['leaders'] ?></strong></div>
    <div class="metric"><span>Announcements</span><strong><?= (int) $stats['announcements'] ?></strong></div>
    <div class="metric"><span>Messages (Total)</span><strong><?= (int) $stats['messages'] ?></strong></div>
    <div class="metric"><span>Open Messages</span><strong><?= (int) $stats['open_messages'] ?></strong></div>
    <div class="metric"><span>Resources</span><strong><?= (int) $stats['resources'] ?></strong></div>
    <div class="metric"><span>Events</span><strong><?= (int) $stats['events'] ?></strong></div>
    <div class="metric"><span>FAQs</span><strong><?= (int) $stats['faqs'] ?></strong></div>
</div>

<div class="table-responsive table-panel">
    <h2 class="h5 mb-3">Students by Department</h2>
    <table class="table align-middle mb-0">
        <thead class="table-light">
            <tr><th>Department</th><th>Students</th></tr>
        </thead>
        <tbody>
        <?php if (! $deptStats): ?>
            <tr><td colspan="2" class="text-secondary">No data.</td></tr>
        <?php endif; ?>
        <?php foreach ($deptStats as $row): ?>
            <tr>
                <td><?= e($row['department']) ?></td>
                <td><strong><?= (int) $row['students'] ?></strong></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
