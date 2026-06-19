<?php

declare(strict_types=1);

final class Message extends BaseModel
{
    public function inboxFor(array $user): array
    {
        if ($user['role_slug'] === 'student') {
            return $this->query(
                'SELECT m.*, u.name AS student_name, a.name AS assignee_name
                 FROM messages m
                 JOIN users u ON u.id = m.student_id
                 LEFT JOIN users a ON a.id = m.assigned_to
                 WHERE m.student_id = ? ORDER BY m.updated_at DESC',
                [$user['id']]
            )->fetchAll();
        }

        return $this->query(
            'SELECT m.*, u.name AS student_name, a.name AS assignee_name
             FROM messages m
             JOIN users u ON u.id = m.student_id
             LEFT JOIN users a ON a.id = m.assigned_to
             ORDER BY m.updated_at DESC'
        )->fetchAll();
    }

    public function find(int $id): ?array
    {
        return $this->query(
            'SELECT m.*, u.name AS student_name FROM messages m JOIN users u ON u.id = m.student_id WHERE m.id = ? LIMIT 1',
            [$id]
        )->fetch() ?: null;
    }

    public function create(array $data): int
    {
        $this->query(
            'INSERT INTO messages (student_id, subject, category, body) VALUES (?, ?, ?, ?)',
            [$data['student_id'], $data['subject'], $data['category'], $data['body']]
        );
        $newId = (int) $this->db->lastInsertId();

        // Notify all ISR leaders about the new student message
        (new Notification())->notifyRole(
            'isr_leader',
            'new_message',
            'New message: ' . $data['subject'],
            substr($data['body'], 0, 100),
            '/messages?message_id=' . $newId
        );

        return $newId;
    }

    public function reply(int $messageId, int $userId, string $body, string $status): void
    {
        $this->query(
            'INSERT INTO message_replies (message_id, user_id, body) VALUES (?, ?, ?)',
            [$messageId, $userId, $body]
        );
        $this->query(
            'UPDATE messages SET status = ?, assigned_to = COALESCE(assigned_to, ?), updated_at = NOW() WHERE id = ?',
            [$status, $userId, $messageId]
        );

        // Notify the original student that their message received a reply
        $message = $this->find($messageId);
        if ($message && (int) $message['student_id'] !== $userId) {
            (new Notification())->create(
                (int) $message['student_id'],
                'reply',
                'Your message received a reply',
                substr($body, 0, 100),
                '/messages?message_id=' . $messageId
            );
        }
    }

    public function replies(int $messageId): array
    {
        return $this->query(
            'SELECT r.*, u.name, roles.slug AS role_slug
             FROM message_replies r
             JOIN users u ON u.id = r.user_id
             JOIN roles ON roles.id = u.role_id
             WHERE r.message_id = ? ORDER BY r.created_at',
            [$messageId]
        )->fetchAll();
    }
}
