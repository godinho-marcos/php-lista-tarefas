<?php

declare(strict_types=1);

namespace App\Core;

final class Router
{
    private array $routes = [];

    public function get(string $path, callable $handler): void
    {
        $this->routes[$path]['GET'] = $handler;
    }

    public function dispatch(string $method, string $uri): void
    {
        $path = explode('?', $uri, 2)[0];
        $methods = $this->routes[$path] ?? [];

        if ($methods === []) {
            http_response_code(404);
            echo 'Página não encontrada.';
            return;
        }

        if (!isset($methods[$method])) {
            http_response_code(405);
            header('Allow: ' . implode(', ', array_keys($methods)));
            echo 'Método não permitido.';
            return;
        }

        $methods[$method]();
    }
}
