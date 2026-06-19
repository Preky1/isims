<?php

declare(strict_types=1);

final class DashboardController extends BaseController
{
    public function index(): void
    {
        $user = auth_user();
        $statsRaw = (new User())->stats();
        $statsRaw['notifications'] = (int)(new Notification())->totalCount();
        $this->view('dashboard/index', [
            'stats' => $statsRaw,
            'announcements' => array_slice((new Announcement())->visibleFor($user), 0, 4),
            'events' => array_slice((new EventItem())->upcoming(), 0, 4),
            'messages' => array_slice((new Message())->inboxFor($user), 0, 5),
            'notifications' => (new Notification())->recent((int) $user['id']),
        ]);
    }
}
