<?php

declare(strict_types=1);

final class EventItem extends BaseModel
{
    public function upcoming(): array
    {
        return $this->query(
            'SELECT e.*, u.name AS created_by FROM events e JOIN users u ON u.id = e.user_id
             WHERE e.starts_at >= DATE_SUB(NOW(), INTERVAL 1 DAY)
             ORDER BY e.starts_at LIMIT 20'
        )->fetchAll();
    }

    public function all(): array
    {
        return $this->query(
            'SELECT e.*, u.name AS created_by FROM events e JOIN users u ON u.id = e.user_id
             ORDER BY e.starts_at DESC'
        )->fetchAll();
    }

    public function calendar(): array
    {
        return $this->query(
            'SELECT c.*, u.name AS created_by FROM calendar_events c JOIN users u ON u.id = c.user_id
             ORDER BY c.starts_on DESC LIMIT 30'
        )->fetchAll();
    }

    public function createEvent(array $data): int
    {
        $this->query(
            'INSERT INTO events (user_id, title, description, category, location, starts_at, ends_at)
             VALUES (?, ?, ?, ?, ?, ?, ?)',
            [
                $data['user_id'], $data['title'], $data['description'],
                $data['category'], $data['location'],
                $data['starts_at'], $data['ends_at'] ?: null,
            ]
        );
        $newId = (int) $this->db->lastInsertId();

        (new Notification())->notifyRole(
            'student',
            'new_event',
            'New Event: ' . $data['title'],
            $data['location'] ? 'Location: ' . $data['location'] : '',
            '/events'
        );

        return $newId;
    }

    public function delete(int $id): void
    {
        $this->query('DELETE FROM events WHERE id = ?', [$id]);
    }
}
