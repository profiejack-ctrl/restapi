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
            throw new RuntimeException('Only mysql DB_DRIVER is supported in this setup.');
        }

        $host = self::envFirst(['DB_HOST', 'MYSQL_HOST', 'MYSQLHOST']) ?: '127.0.0.1';
        $port = (int) (self::envFirst(['DB_PORT', 'MYSQL_PORT', 'MYSQLPORT']) ?: '3306');
        $name = self::envFirst(['DB_NAME', 'MYSQL_DATABASE']) ?: '';
        $user = self::envFirst(['DB_USER', 'MYSQL_USER', 'MYSQLUSER']) ?: '';
        $pass = self::envFirst(['DB_PASS', 'MYSQL_PASSWORD', 'MYSQLPASSWORD']) ?: '';

        $missing = [];
        if ($name === '') {
            $missing[] = 'DB_NAME (or MYSQL_DATABASE)';
        }
        if ($user === '') {
            $missing[] = 'DB_USER (or MYSQL_USER)';
        }
        if ($pass === '') {
            $missing[] = 'DB_PASS (or MYSQL_PASSWORD)';
        }
        if ($missing !== []) {
            throw new RuntimeException('Missing required database env vars: ' . implode(', ', $missing));
        }

        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        try {
            self::$connection = new mysqli($host, $user, $pass, $name, $port);
            self::$connection->set_charset('utf8mb4');
        } catch (\mysqli_sql_exception $e) {
            throw new RuntimeException('Database connection failed: ' . $e->getMessage(), 0, $e);
        }

        return self::$connection;
    }

    /**
     * @param string[] $keys
     */
    private static function envFirst(array $keys): ?string
    {
        foreach ($keys as $key) {
            $value = getenv($key);
            if ($value !== false && $value !== '') {
                return $value;
            }
        }

        return null;
    }
}
