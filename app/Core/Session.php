<?php

declare(strict_types=1);

namespace App\Core;

final class Session
{
    public static function start(): void
    {
        session_name('agenda_session');
        session_start([
            'use_strict_mode' => true,
            'use_only_cookies' => true,
            'cookie_httponly' => true,
            'cookie_samesite' => 'Lax',
            'cookie_secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
            'cookie_path' => '/',
        ]);
    }

    public static function user(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    public static function signIn(int $id, string $name): void
    {
        session_regenerate_id(true);
        $_SESSION = ['user' => ['id' => $id, 'name' => $name]];
    }

    public static function signOut(): void
    {
        $_SESSION = [];
        $cookie = session_get_cookie_params();
        setcookie(session_name(), '', [
            'expires' => time() - 3600,
            'path' => $cookie['path'],
            'domain' => $cookie['domain'],
            'secure' => $cookie['secure'],
            'httponly' => $cookie['httponly'],
            'samesite' => $cookie['samesite'],
        ]);
        session_destroy();
    }

    public static function csrfToken(): string
    {
        return $_SESSION['csrf'] ??= bin2hex(random_bytes(32));
    }

    public static function checkCsrf(mixed $token): bool
    {
        return is_string($token) && hash_equals(self::csrfToken(), $token);
    }

    public static function setNotice(string $message): void
    {
        $_SESSION['notice'] = $message;
    }

    public static function takeNotice(): ?string
    {
        $message = $_SESSION['notice'] ?? null;
        unset($_SESSION['notice']);

        return $message;
    }
}
