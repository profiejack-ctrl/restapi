<?php

declare(strict_types=1);

namespace App\Core;

use mysqli;
use RuntimeException;

final class Database
{
    private static ?mysqli $connection = null;

    public static function connection(): mysqli
    {
        if (self::$connection instanceof mysqli) {
            return self::$connection;
        }

        $driver = getenv('DB_DRIVER') ?: 'mysql';
        if ($driver !== 'mysql') {
            throw new RuntimeException('Only mysql DB_DRIVER is supported in this starter.');
        }

        $host = getenv('DB_HOST') ?: '127.0.0.1';
        $port = (int) (getenv('DB_PORT') ?: '3306');
        $name = getenv('DB_NAME') ?: '';
        $user = getenv('DB_USER') ?: '';
        $pass = getenv('DB_PASS') ?: '';

        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        self::$connection = new mysqli($host, $user, $pass, $name, $port);
        self::$connection->set_charset('utf8mb4');

        return self::$connection;
    }
}