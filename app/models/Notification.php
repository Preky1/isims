<?php

declare(strict_types=1);

final class Notification extends BaseModel
{
    public function recent(int $userId, int $limit = 15): array
    {
        return $this->query(
            'SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT ' . $limit,
            [$userId]
        )->fetchAll();
    }

    public function unreadCount(int $userId): int
    {
        return (int) $this->query(
            'SELECT COUNT(*) AS cnt FROM notifications WHERE user_id = ? AND read_at IS NULL',
            [$userId]
        )->fetch()['cnt'];
    }

    public function totalCount(): int
    {
        return (int) $this->query('SELECT COUNT(*) AS cnt FROM notifications')->fetch()['cnt'];
    }

    public function markRead(int $id, int $userId): void
    {
        $this->query(
            'UPDATE notifications SET read_at = NOW() WHERE id = ? AND user_id = ?',
            [$id, $userId]
        );
    }

    public function markAllRead(int $userId): void
    {
        $this->query(
            'UPDATE notifications SET read_at = NOW() WHERE user_id = ? AND read_at IS NULL',
            [$userId]
        );
    }

    public function create(int $userId, string $type, string $title, string $body = '', ?string $link = null): void
    {
        $this->query(
            'INSERT INTO notifications (user_id, type, title, body, link) VALUES (?, ?, ?, ?, ?)',
            [$userId, $type, $title, $body, $link]
        );
    }

    /** Notify all users with the given role slug */
    public function notifyRole(string $roleSlug, string $type, string $title, string $body = '', ?string $link = null): void
    {
        $users = $this->query(
            'SELECT users.id FROM users JOIN roles ON roles.id = users.role_id WHERE roles.slug = ? AND users.status = \'active\'',
            [$roleSlug]
        )->fetchAll();

        foreach ($users as $user) {
            $this->create((int) $user['id'], $type, $title, $body, $link);
        }
    }
}
