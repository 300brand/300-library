<?php

declare(strict_types=1);

namespace ThreeHundred\Library\Session;

use RuntimeException;

class PhpSession implements SessionInterface
{
    private const NAME = 'session';

    public function __construct()
    {
        $lifetime = 86400;
        $options = [
            'cookie_httponly' => true,
            'cookie_lifetime' => $lifetime,
            'cookie_secure'   => $this->isSsl(),
            'gc_maxlifetime'  => $lifetime,
            'name'            => self::NAME,
            'use_strict_mode' => true,
        ];
        if (!session_start($options)) {
            throw new RuntimeException('Failed to start session');
        }

        // This handles a well documented bug in which PHP does not resend the
        // session cookie with an updated expiration.
        $c = session_get_cookie_params();
        setcookie(
            self::NAME,
            session_id(),
            time() + $c['lifetime'],
            $c['path'],
            $c['domain'],
            $c['secure'],
            $c['httponly']
        );
    }

    public function asArray(): array
    {
        return $_SESSION;
    }

    public function clear(): void
    {
        $_SESSION = [];
    }

    public function get(string $key, $default = null)
    {
        if (!$this->has($key)) {
            return $default;
        }
        return $_SESSION[$key];
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $_SESSION);
    }

    public function set(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    public function unset(string $key): void
    {
        unset($_SESSION[$key]);
    }

    private function isSsl(): bool
    {
        if (!array_key_exists("HTTPS", $_SERVER)) {
            return false;
        }
        return $_SERVER['HTTPS'] == "ON"
            || $_SERVER['HTTPS'] == "on"
            || $_SERVER['HTTPS'] == 1;
    }
}
