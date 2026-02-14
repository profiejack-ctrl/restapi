<?php

declare(strict_types=1);

namespace App\Core;

use Closure;

final class Router
{
    /** @var array<string, array<string, Closure>> */
    private array $routes = [];

    public function get(string $path, Closure $handler): void
    {
        $this->add('GET', $path, $handler);
    }

    public function post(string $path, Closure $handler): void
    {
        $this->add('POST', $path, $handler);
    }

    public function put(string $path, Closure $handler): void
    {
        $this->add('PUT', $path, $handler);
    }

    public function delete(string $path, Closure $handler): void
    {
        $this->add('DELETE', $path, $handler);
    }

    private function add(string $method, string $path, Closure $handler): void
    {
        $this->routes[$method][$path] = $handler;
    }

    public function dispatch(string $method, string $uri): void
    {
        $path = parse_url($uri, PHP_URL_PATH) ?: '/';

        $dynamicRoutes = [];
        foreach (($this->routes[$method] ?? []) as $routePath => $handler) {
            if (str_contains($routePath, '{')) {
                $dynamicRoutes[$routePath] = $handler;
                continue;
            }

            if ($routePath === $path) {
                $handler();
                return;
            }
        }

        foreach ($dynamicRoutes as $routePath => $handler) {
            $regex = preg_replace('#\{[^/]+\}#', '([^/]+)', $routePath);
            $regex = '#^' . $regex . '$#';
            if ($regex !== null && preg_match($regex, $path, $matches) === 1) {
                array_shift($matches);
                $handler(...$matches);
                return;
            }
        }

        Response::json(['error' => 'Not Found'], 404);
    }
}