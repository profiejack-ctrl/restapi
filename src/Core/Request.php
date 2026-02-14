<?php

declare(strict_types=1);

namespace App\Core;

final class Request
{
    public static function json(): array
    {
        $input = file_get_contents('php://input');
        if ($input === false || trim($input) === '') {
            return [];
        }

        $decoded = json_decode($input, true);
        return is_array($decoded) ? $decoded : [];
    }
}