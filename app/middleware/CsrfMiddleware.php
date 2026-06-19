<?php

declare(strict_types=1);

final class CsrfMiddleware
{
    public static function handle(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        if (! hash_equals($_SESSION['_csrf'] ?? '', $_POST['_csrf'] ?? '')) {
            http_response_code(419);
            exit('Security token mismatch.');
        }
    }
}
