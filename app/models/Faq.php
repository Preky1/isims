<?php

declare(strict_types=1);

final class Faq extends BaseModel
{
    public function all(string $q = '', string $category = ''): array
    {
        $where  = ['f.is_published = 1'];
        $params = [];

        if ($q !== '') {
            $where[]  = '(f.question LIKE ? OR f.answer LIKE ?)';
            $params[] = "%$q%";
            $params[] = "%$q%";
        }
        if ($category !== '') {
            $where[]  = 'f.category = ?';
            $params[] = $category;
        }

        return $this->query(
            'SELECT f.*, u.name AS author_name FROM faqs f JOIN users u ON u.id = f.user_id
             WHERE ' . implode(' AND ', $where) . ' ORDER BY f.category, f.question',
            $params
        )->fetchAll();
    }

    /** @deprecated use all() */
    public function search(string $q = ''): array
    {
        return $this->all($q);
    }

    public function find(int $id): ?array
    {
        return $this->query('SELECT * FROM faqs WHERE id = ? LIMIT 1', [$id])->fetch() ?: null;
    }

    public function create(array $data): void
    {
        $this->query(
            'INSERT INTO faqs (user_id, category, question, answer) VALUES (?, ?, ?, ?)',
            [$data['user_id'], $data['category'], $data['question'], $data['answer']]
        );
    }

    public function update(int $id, array $data): void
    {
        $this->query(
            'UPDATE faqs SET category = ?, question = ?, answer = ?, updated_at = NOW() WHERE id = ?',
            [$data['category'], $data['question'], $data['answer'], $id]
        );
    }

    public function delete(int $id): void
    {
        $this->query('DELETE FROM faqs WHERE id = ?', [$id]);
    }
}
