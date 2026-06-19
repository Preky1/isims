<?php

declare(strict_types=1);

final class AnnouncementController extends BaseController
{
    public function index(): void
    {
        $user = auth_user();
        $isLeader = has_role('isr_leader', 'leader_admin', 'system_admin');

        $this->view('announcements/index', [
            'announcements' => $isLeader
                ? (new Announcement())->all()
                : (new Announcement())->visibleFor($user),
            'departments'   => (new User())->departments(),
        ]);
    }

    public function store(): void
    {
        $errors = $this->validate(['title' => 'Title', 'body' => 'Body']);
        if ($errors) {
            flash('error', 'Title and body are required.');
            redirect('/announcements');
        }

        $announcementModel = new Announcement();
        $id = $announcementModel->create([
            'user_id'       => auth_user()['id'],
            'department_id' => $this->input('department_id'),
            'title'         => $this->input('title'),
            'body'          => $this->input('body'),
            'audience'      => $this->input('audience', 'public'),
        ]);

        // Notify all students about the new announcement
        (new Notification())->notifyRole(
            'student',
            'announcement',
            'New Announcement: ' . $this->input('title'),
            substr($this->input('body'), 0, 120),
            '/announcements'
        );

        flash('success', 'Announcement published.');
        redirect('/announcements');
    }

    public function archive(): void
    {
        (new Announcement())->archive((int) $this->input('id'));
        flash('success', 'Announcement archived.');
        redirect('/announcements');
    }

    public function delete(): void
    {
        (new Announcement())->delete((int) $this->input('id'));
        flash('success', 'Announcement deleted.');
        redirect('/announcements');
    }
}
