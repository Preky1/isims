<?php

declare(strict_types=1);

final class Faculty extends BaseModel
{
    public function all(): array
    {
        return $this->query(
            'SELECT f.*,
                    COUNT(DISTINCT d.id) AS dept_count,
                    COUNT(DISTINCT p.id) AS program_count
             FROM faculties f
             LEFT JOIN departments d ON d.faculty_id = f.id
             LEFT JOIN programs p ON p.faculty_id = f.id
             GROUP BY f.id
             ORDER BY f.name'
        )->fetchAll();
    }

    public function find(int $id): ?array
    {
        return $this->query('SELECT * FROM faculties WHERE id = ? LIMIT 1', [$id])->fetch() ?: null;
    }

    public function departments(int $facultyId): array
    {
        return $this->query(
            'SELECT d.*, COUNT(u.id) AS student_count
             FROM departments d
             LEFT JOIN users u ON u.department_id = d.id
             WHERE d.faculty_id = ?
             GROUP BY d.id ORDER BY d.name',
            [$facultyId]
        )->fetchAll();
    }

    public function programs(int $facultyId): array
    {
        return $this->query(
            'SELECT * FROM programs WHERE faculty_id = ? ORDER BY level, name',
            [$facultyId]
        )->fetchAll();
    }

    public function create(array $data): int
    {
        $this->query(
            'INSERT INTO faculties (name, code, description, color) VALUES (?, ?, ?, ?)',
            [$data['name'], strtoupper($data['code']), $data['description'] ?? '', $data['color'] ?? '#1f6feb']
        );
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $this->query(
            'UPDATE faculties SET name = ?, code = ?, description = ?, color = ?, is_active = ?, updated_at = NOW() WHERE id = ?',
            [$data['name'], strtoupper($data['code']), $data['description'] ?? '', $data['color'] ?? '#1f6feb', $data['is_active'] ?? 1, $id]
        );
    }

    public function delete(int $id): void
    {
        $this->query('DELETE FROM faculties WHERE id = ?', [$id]);
    }

    public function addDepartment(array $data): void
    {
        $this->query(
            'INSERT INTO departments (faculty_id, name, code) VALUES (?, ?, ?)',
            [$data['faculty_id'], $data['name'], strtoupper($data['code'])]
        );
    }

    public function deleteDepartment(int $id): void
    {
        $this->query('DELETE FROM departments WHERE id = ?', [$id]);
    }

    public function addProgram(array $data): void
    {
        $this->query(
            'INSERT INTO programs (faculty_id, department_id, name, level) VALUES (?, ?, ?, ?)',
            [$data['faculty_id'], $data['department_id'] ?: null, $data['name'], $data['level']]
        );
    }

    public function deleteProgram(int $id): void
    {
        $this->query('DELETE FROM programs WHERE id = ?', [$id]);
    }
}
