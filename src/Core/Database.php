<?php

declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;
use RuntimeException;

final class Database
{
    private static ?PDO $connection = null;

    public static function connection(): PDO
    {
        if (self::$connection instanceof PDO) {
            return self::$connection;
        }

        $driver = getenv('DB_DRIVER') ?: 'pgsql';
        if ($driver !== 'pgsql') {
            throw new RuntimeException('Only pgsql DB_DRIVER is supported for this Render setup.');
        }

        $databaseUrl = getenv('DATABASE_URL') ?: '';
        if ($databaseUrl === '') {
            throw new RuntimeException('DATABASE_URL is required.');
        }

        $parts = parse_url($databaseUrl);
        if ($parts === false || !isset($parts['host'], $parts['path'])) {
            throw new RuntimeException('Invalid DATABASE_URL format.');
        }

        $host = (string) $parts['host'];
        $port = isset($parts['port']) ? (int) $parts['port'] : 5432;
        $dbName = ltrim((string) $parts['path'], '/');
        $user = isset($parts['user']) ? urldecode((string) $parts['user']) : '';
        $pass = isset($parts['pass']) ? urldecode((string) $parts['pass']) : '';
        $urlSslMode = null;
        if (isset($parts['query'])) {
            parse_str((string) $parts['query'], $queryParts);
            $urlSslMode = isset($queryParts['sslmode']) ? (string) $queryParts['sslmode'] : null;
        }
        $sslMode = $urlSslMode ?: (getenv('DB_SSLMODE') ?: 'prefer');

        $dsn = sprintf(
            'pgsql:host=%s;port=%d;dbname=%s;sslmode=%s',
            $host,
            $port,
            $dbName,
            $sslMode
        );

        try {
            self::$connection = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $e) {
            throw new RuntimeException('Database connection failed: ' . $e->getMessage(), 0, $e);
        }

        return self::$connection;
    }
}
