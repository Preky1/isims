<?php

declare(strict_types=1);

final class User extends BaseModel
{
    public function findByEmail(string $email): ?array
    {
        return $this->query(
            'SELECT users.*, roles.slug AS role_slug, roles.name AS role_name, departments.name AS department_name
             FROM users
             JOIN roles ON roles.id = users.role_id
             LEFT JOIN departments ON departments.id = users.department_id
             WHERE users.email = ? LIMIT 1',
            [$email]
        )->fetch() ?: null;
    }

    public function find(int $id): ?array
    {
        return $this->query(
            'SELECT users.*, roles.slug AS role_slug, roles.name AS role_name, departments.name AS department_name
             FROM users
             JOIN roles ON roles.id = users.role_id
             LEFT JOIN departments ON departments.id = users.department_id
             WHERE users.id = ? LIMIT 1',
            [$id]
        )->fetch() ?: null;
    }

    public function createStudent(array $data): bool
    {
        $roleId = $this->roleId('student');
        return $this->query(
            'INSERT INTO users (role_id, department_id, name, email, password, phone, student_number)
             VALUES (?, ?, ?, ?, ?, ?, ?)',
            [
                $roleId,
                $data['department_id'] ?: null,
                $data['name'],
                $data['email'],
                password_hash($data['password'], PASSWORD_BCRYPT),
                $data['phone'] ?: null,
                $data['student_number'] ?: null,
            ]
        )->rowCount() > 0;
    }

    public function createStaff(array $data, string $roleSlug): bool
    {
        return $this->query(
            'INSERT INTO users (role_id, department_id, name, email, password, phone, position)
             VALUES (?, ?, ?, ?, ?, ?, ?)',
            [
                $this->roleId($roleSlug),
                $data['department_id'] ?: null,
                $data['name'],
                $data['email'],
                password_hash($data['password'], PASSWORD_BCRYPT),
                $data['phone'] ?: null,
                $data['position'] ?: null,
            ]
        )->rowCount() > 0;
    }

    public function updateLastLogin(int $id): void
    {
        $this->query('UPDATE users SET last_login_at = NOW() WHERE id = ?', [$id]);
    }

    public function toggleStatus(int $id): void
    {
        $this->query(
            "UPDATE users SET status = IF(status = 'active', 'inactive', 'active') WHERE id = ?",
            [$id]
        );
    }

    public function delete(int $id): void
    {
        $this->query('DELETE FROM users WHERE id = ?', [$id]);
    }

    public function all(): array
    {
        return $this->query(
            'SELECT users.*, roles.name AS role_name, roles.slug AS role_slug, departments.name AS department_name
             FROM users
             JOIN roles ON roles.id = users.role_id
             LEFT JOIN departments ON departments.id = users.department_id
             ORDER BY users.created_at DESC'
        )->fetchAll();
    }

    public function roles(): array
    {
        return $this->query('SELECT * FROM roles ORDER BY id')->fetchAll();
    }

    public function departments(): array
    {
        return $this->query('SELECT * FROM departments ORDER BY name')->fetchAll();
    }

    public function stats(): array
    {
        return [
            'students'      => (int) $this->query("SELECT COUNT(*) AS t FROM users JOIN roles ON roles.id = users.role_id WHERE roles.slug = 'student'")->fetch()['t'],
            'leaders'       => (int) $this->query("SELECT COUNT(*) AS t FROM users JOIN roles ON roles.id = users.role_id WHERE roles.slug = 'isr_leader'")->fetch()['t'],
            'announcements' => (int) $this->query('SELECT COUNT(*) AS t FROM announcements')->fetch()['t'],
            'messages'      => (int) $this->query('SELECT COUNT(*) AS t FROM messages')->fetch()['t'],
            'resources'     => (int) $this->query('SELECT COUNT(*) AS t FROM resources')->fetch()['t'],
            'events'        => (int) $this->query('SELECT COUNT(*) AS t FROM events')->fetch()['t'],
            'faqs'          => (int) $this->query('SELECT COUNT(*) AS t FROM faqs')->fetch()['t'],
            'open_messages' => (int) $this->query("SELECT COUNT(*) AS t FROM messages WHERE status = 'open'")->fetch()['t'],
        ];
    }

    public function departmentStats(): array
    {
        return $this->query(
            "SELECT d.name AS department, COUNT(u.id) AS students
             FROM departments d
             LEFT JOIN users u ON u.department_id = d.id
             JOIN roles r ON r.id = u.role_id AND r.slug = 'student'
             GROUP BY d.id, d.name
             ORDER BY students DESC"
        )->fetchAll();
    }

    public function findById(int $id): ?array
    {
        return $this->find($id);
    }

    public function updateProfile(int $id, string $name, string $email, string $phone = ''): void
    {
        $this->query(
            'UPDATE users SET name=?, email=?, phone=?, updated_at=NOW() WHERE id=?',
            [$name, $email, $phone ?: null, $id]
        );
    }

    public function updatePassword(int $id, string $hashed): void
    {
        $this->query('UPDATE users SET password=?, updated_at=NOW() WHERE id=?', [$hashed, $id]);
    }

    private function roleId(string $slug): int
    {
        return (int) $this->query('SELECT id FROM roles WHERE slug = ? LIMIT 1', [$slug])->fetch()['id'];
    }
}
