<?php

declare(strict_types=1);

abstract class BaseController
{
    protected function view(string $view, array $data = [], string $layout = 'app'): void
    {
        extract($data, EXTR_SKIP);
        ob_start();
        require APP_PATH . '/views/' . $view . '.php';
        $content = ob_get_clean();
        require APP_PATH . '/views/layouts/' . $layout . '.php';
    }

    protected function input(string $key, mixed $default = null): mixed
    {
        return trim((string) ($_POST[$key] ?? $_GET[$key] ?? $default));
    }

    protected function validate(array $rules): array
    {
        $errors = [];
        foreach ($rules as $field => $label) {
            if (trim((string) ($_POST[$field] ?? '')) === '') {
                $errors[$field] = $label . ' is required.';
            }
        }
        return $errors;
    }
}
