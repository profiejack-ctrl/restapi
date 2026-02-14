<?php

declare(strict_types=1);

use App\Controllers\HealthController;
use App\Controllers\UserController;
use App\Core\Router;

return static function (Router $router): void {
    $version = getenv('API_VERSION') ?: 'v1';
    $prefix = '/' . $version;

    $health = new HealthController();
    $users = new UserController();

    $router->get($prefix . '/health', fn () => $health->index());

    $router->get($prefix . '/users', fn () => $users->index());
    $router->get($prefix . '/users/{id}', fn (string $id) => $users->show($id));
    $router->post($prefix . '/users', fn () => $users->store());
    $router->put($prefix . '/users/{id}', fn (string $id) => $users->update($id));
    $router->delete($prefix . '/users/{id}', fn (string $id) => $users->destroy($id));
};