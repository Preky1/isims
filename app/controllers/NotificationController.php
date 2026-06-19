<?php

declare(strict_types=1);

final class NotificationController extends BaseController
{
    public function index(): void
    {
        $user = auth_user();
        $this->view('notifications/index', [
            'notifications' => (new Notification())->recent((int) $user['id'], 30),
        ]);
    }

    public function markRead(): void
    {
        $user = auth_user();
        (new Notification())->markRead((int) $this->input('id'), (int) $user['id']);
        redirect('/notifications');
    }

    public function markAllRead(): void
    {
        (new Notification())->markAllRead((int) auth_user()['id']);
        flash('success', 'All notifications marked as read.');
        redirect('/notifications');
    }
}
