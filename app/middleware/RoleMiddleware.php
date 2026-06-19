<?php

declare(strict_types=1);

final class RoleMiddleware
{
    public static array $roles = [];

    public static function require(string ...$roles): string
    {
        $key = 'RoleMiddleware_' . md5(implode('|', $roles));
        if (! class_exists($key)) {
            self::$roles[$key] = $roles;
            eval("final class {$key} { public static function handle(): void { RoleMiddleware::check('{$key}'); } }");
        }
        return $key;
    }

    public static function check(string $key): void
    {
        if (! has_role(...(self::$roles[$key] ?? []))) {
            http_response_code(403);
            exit('403 - You do not have permission to access this page.');
        }
    }
}
