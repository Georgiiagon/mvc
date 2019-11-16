<?php

namespace Core;

class Auth {

    public static function isAdmin()
    {
        if (isset($_SERVER['PHP_AUTH_USER']) && $_SERVER['PHP_AUTH_PW']) {
            return $_SERVER['PHP_AUTH_USER'] == 'admin' && $_SERVER['PHP_AUTH_PW'] == '123';
        }

        return false;
    }
}
