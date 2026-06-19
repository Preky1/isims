<?php

declare(strict_types=1);

final class Setting extends BaseModel
{
    private static array $cache = [];

    public function all(): array
    {
        $rows = $this->query('SELECT `key`, `value` FROM settings')->fetchAll();
        $result = [];
        foreach ($rows as $row) {
            $result[$row['key']] = $row['value'];
        }
        return $result;
    }

    public function get(string $key, string $default = ''): string
    {
        if (isset(self::$cache[$key])) return self::$cache[$key];
        $row = $this->query('SELECT `value` FROM settings WHERE `key` = ? LIMIT 1', [$key])->fetch();
        self::$cache[$key] = $row ? (string) $row['value'] : $default;
        return self::$cache[$key];
    }

    public function set(string $key, string $value): void
    {
        $this->query(
            'INSERT INTO settings (`key`, `value`) VALUES (?, ?)
             ON DUPLICATE KEY UPDATE `value` = VALUES(`value`)',
            [$key, $value]
        );
        self::$cache[$key] = $value;
    }

    public function saveMany(array $data): void
    {
        foreach ($data as $key => $value) {
            $this->set($key, (string) $value);
        }
    }
}
