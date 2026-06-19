<?php

declare(strict_types=1);

final class MessageController extends BaseController
{
    public function index(): void
    {
        $user            = auth_user();
        $messageModel    = new Message();
        $messageId       = (int) ($_GET['message_id'] ?? 0);
        $activeMessage   = null;
        $replies         = [];

        if ($messageId) {
            $activeMessage = $messageModel->find($messageId);
            // Students can only view their own messages
            if ($activeMessage && $user['role_slug'] === 'student' && (int) $activeMessage['student_id'] !== (int) $user['id']) {
                $activeMessage = null;
            }
            if ($activeMessage) {
                $replies = $messageModel->replies($messageId);
            }
        }

        $this->view('messages/index', [
            'messages'        => $messageModel->inboxFor($user),
            'replies'         => $replies,
            'activeMessageId' => $messageId ?: null,
            'activeMessage'   => $activeMessage,
        ]);
    }

    public function store(): void
    {
        $errors = $this->validate(['subject' => 'Subject', 'body' => 'Message']);
        if ($errors) {
            flash('error', 'Subject and message are required.');
            redirect('/messages');
        }

        $id = (new Message())->create([
            'student_id' => auth_user()['id'],
            'subject'    => $this->input('subject'),
            'category'   => $this->input('category', 'general_inquiry'),
            'body'       => $this->input('body'),
        ]);

        flash('success', 'Message sent.');
        redirect('/messages?message_id=' . $id);
    }

    public function reply(): void
    {
        $errors = $this->validate(['body' => 'Reply']);
        if ($errors) {
            flash('error', 'Reply cannot be empty.');
            redirect('/messages');
        }

        $messageId = (int) $this->input('message_id');
        (new Message())->reply(
            $messageId,
            (int) auth_user()['id'],
            $this->input('body'),
            $this->input('status', 'open')
        );
        flash('success', 'Reply posted.');
        redirect('/messages?message_id=' . $messageId);
    }
}
