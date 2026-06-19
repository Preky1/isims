#!/bin/bash
set -e

echo "==> Waiting for MySQL to be ready..."
until php -r "
    try {
        \$pdo = new PDO(
            'mysql:host=' . getenv('DB_HOST') . ';port=' . (getenv('DB_PORT') ?: 3306) . ';charset=utf8mb4',
            getenv('DB_USERNAME') ?: getenv('DB_USER'),
            getenv('DB_PASSWORD') ?: getenv('DB_PASS'),
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
        echo 'ok';
    } catch(Exception \$e) { exit(1); }
" 2>/dev/null | grep -q ok; do
    echo "   MySQL not ready — retrying in 2s..."
    sleep 2
done
echo "==> MySQL is ready."

echo "==> Running database setup..."
php /var/www/html/database/setup.php
echo "==> Database setup complete."

echo "==> Starting Apache..."
exec apache2-foreground
