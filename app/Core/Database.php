<?php

declare(strict_types=1);

namespace App\Core;

use PDO;
use RuntimeException;

final class Database
{
    private static ?PDO $connection = null;

    private function __construct()
    {
    }

    public static function connection(): PDO
    {
        if (self::$connection === null) {
            $host = self::setting('DB_HOST');
            $name = self::setting('DB_NAME');

            self::$connection = new PDO(
                "mysql:host=$host;dbname=$name;charset=utf8mb4",
                self::setting('DB_USER'),
                self::setting('DB_PASSWORD'),
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ],
            );
        }

        return self::$connection;
    }

    private static function setting(string $name): string
    {
        $value = getenv($name);

        if ($value === false || $value === '') {
            throw new RuntimeException("Configuração ausente: $name.");
        }

        return $value;
    }
}
