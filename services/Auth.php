<?php

namespace services;

use models\Login;

class Auth
{
    public static function createToken(Login $user): void
    {
        $token = $user->id . ':' . $user->authToken;
        setcookie('token', $token, 0, '/', '', false, true);
    }

    public static function deleteToken(): void
    {
        setcookie('token', '', 1);
    }

    public static function getUserByToken(): ?Login
    {
        $token = $_COOKIE['token'] ?? '';

        if (empty($token)) {
            return null;
        }

        [$userId, $authToken] = explode(':', $token, 2);

        $user = Login::findById((int) $userId);

        
        if ($user === null) {
            return null;
        }

        if ($user->authToken !== $authToken) {
            return null;
        }

        return $user;
    }
}