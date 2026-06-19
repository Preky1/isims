<?php

declare(strict_types=1);

final class Announcement extends BaseModel
{
    public function visibleFor(?array $user): array
    {
        $params = [];
        $where  = ['a.is_archived = 0'];

        if ($user && $user['role_slug'] === 'student') {
            $where[] = "(a.audience IN ('public','students') OR (a.audience = 'department' AND a.department_id = ?))";
            $params[] = $user['department_id'];
        }

        $sql = 'SELECT a.*, u.name AS author_name, d.name AS department_name
                FROM announcements a
                JOIN users u ON u.id = a.user_id
                LEFT JOIN departments d ON d.id = a.department_id
                WHERE ' . implode(' AND ', $where) . '
                ORDER BY COALESCE(a.published_at, a.created_at) DESC';

        return $this->query($sql, $params)->fetchAll();
    }

    public function all(): array
    {
        return $this->query(
            'SELECT a.*, u.name AS author_name, d.name AS department_name
             FROM announcements a
             JOIN users u ON u.id = a.user_id
             LEFT JOIN departments d ON d.id = a.department_id
             ORDER BY COALESCE(a.published_at, a.created_at) DESC'
        )->fetchAll();
    }

    public function find(int $id): ?array
    {
        return $this->query(
            'SELECT a.*, u.name AS author_name, d.name AS department_name
             FROM announcements a
             JOIN users u ON u.id = a.user_id
             LEFT JOIN departments d ON d.id = a.department_id
             WHERE a.id = ? LIMIT 1',
            [$id]
        )->fetch() ?: null;
    }

    public function create(array $data): int
    {
        $this->query(
            'INSERT INTO announcements (user_id, department_id, title, body, audience, published_at)
             VALUES (?, ?, ?, ?, ?, NOW())',
            [$data['user_id'], $data['department_id'] ?: null, $data['title'], $data['body'], $data['audience']]
        );
        return (int) $this->db->lastInsertId();
    }

    public function archive(int $id): void
    {
        $this->query('UPDATE announcements SET is_archived = 1 WHERE id = ?', [$id]);
    }

    public function delete(int $id): void
    {
        $this->query('DELETE FROM announcements WHERE id = ?', [$id]);
    }
}
