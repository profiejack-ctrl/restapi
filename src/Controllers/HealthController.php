<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Response;

final class HealthController
{
    public function index(): void
    {
        Response::json([
            'status' => 'ok',
            'app' => getenv('APP_NAME') ?: 'API',
            'version' => getenv('API_VERSION') ?: 'v1',
            'timestamp' => date(DATE_ATOM),
        ]);
    }
}