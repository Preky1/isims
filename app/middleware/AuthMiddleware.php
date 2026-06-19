<?php

declare(strict_types=1);

final class AuthMiddleware
{
    public static function handle(): void
    {
        if (! auth_user()) {
            flash('error', 'Please log in to continue.');
            redirect('/login');
        }

        if (isset($_SESSION['last_activity']) && time() - $_SESSION['last_activity'] > 1800) {
            session_destroy();
            session_start();
            flash('error', 'Your session expired. Please log in again.');
            redirect('/login');
        }

        $_SESSION['last_activity'] = time();
    }
}
