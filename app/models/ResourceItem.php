<?php

declare(strict_types=1);

final class ResourceItem extends BaseModel
{
    public function search(array $filters = []): array
    {
        $where = [];
        $params = [];

        if (! empty($filters['department_id'])) {
            $where[] = 'r.department_id = ?';
            $params[] = $filters['department_id'];
        }
        if (! empty($filters['q'])) {
            $where[] = '(r.title LIKE ? OR r.description LIKE ?)';
            $params[] = '%' . $filters['q'] . '%';
            $params[] = '%' . $filters['q'] . '%';
        }

        $sql = 'SELECT r.*, u.name AS uploaded_by, d.name AS department_name FROM resources r
            JOIN users u ON u.id = r.user_id LEFT JOIN departments d ON d.id = r.department_id';
        if ($where) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }
        $sql .= ' ORDER BY r.created_at DESC';
        return $this->query($sql, $params)->fetchAll();
    }

    public function create(array $data): void
    {
        $this->query('INSERT INTO resources (user_id, department_id, title, description, category, file_name, file_path, mime_type, file_size)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)', [
            $data['user_id'], $data['department_id'] ?: null, $data['title'], $data['description'], $data['category'],
            $data['file_name'], $data['file_path'], $data['mime_type'], $data['file_size'],
        ]);
    }
}
