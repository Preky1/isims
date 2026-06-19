<?php

declare(strict_types=1);

final class Router
{
    private array $routes = [];

    public function get(string $path, array $handler, array $middleware = []): void
    {
        $this->add('GET', $path, $handler, $middleware);
    }

    public function post(string $path, array $handler, array $middleware = []): void
    {
        $this->add('POST', $path, $handler, $middleware);
    }

    // HTML forms use _method=DELETE hidden field
    public function delete(string $path, array $handler, array $middleware = []): void
    {
        $this->add('DELETE', $path, $handler, $middleware);
    }

    private function add(string $method, string $path, array $handler, array $middleware): void
    {
        $this->routes[$method][$this->normalize($path)] = compact('handler', 'middleware');
    }

    public function dispatch(string $method, string $uri): void
    {
        $path = $this->normalize($this->stripBasePath($uri));

        // 1. exact match
        $route = $this->routes[$method][$path] ?? null;
        $params = [];

        // 2. dynamic segment match  e.g. /messages/:id
        if (! $route) {
            foreach ($this->routes[$method] ?? [] as $pattern => $candidate) {
                if (! str_contains($pattern, ':')) {
                    continue;
                }
                $regex = preg_replace('#:([A-Za-z_]+)#', '(?P<$1>[^/]+)', $pattern);
                if (preg_match('#^' . $regex . '$#', $path, $m)) {
                    $route  = $candidate;
                    $params = array_filter($m, 'is_string', ARRAY_FILTER_USE_KEY);
                    break;
                }
            }
        }

        if (! $route) {
            http_response_code(404);
            echo '<h1>404 &ndash; Page not found</h1>';
            return;
        }

        // Merge dynamic route params into $_GET so controllers can read them
        foreach ($params as $k => $v) {
            $_GET[$k] = $v;
        }

        foreach ($route['middleware'] as $middleware) {
            $middleware::handle();
        }

        [$controller, $action] = $route['handler'];
        (new $controller())->$action();
    }

    private function stripBasePath(string $uri): string
    {
        $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
        if ($scriptDir !== '/' && str_starts_with($uri, $scriptDir)) {
            return substr($uri, strlen($scriptDir)) ?: '/';
        }
        return $uri;
    }

    private function normalize(string $path): string
    {
        $path = '/' . trim($path, '/');
        return $path === '/' ? '/' : rtrim($path, '/');
    }
}
