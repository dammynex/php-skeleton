<?php

namespace Core\Helpers;

/**
*
* Manages cookies
* creates, edits and removes cookie
*/
class Cookie
{

    /**
    * adds new cookie
    *
    * @param {string} $name Cookie name
    * @param {any} $value Cookie value
    * @param {int} $expires Days to expire
    * @param {string} $path cookie path
    */
    public static function Add(string $name, $value, int $expires = 30, string $path = '/')
    {
        $expires = $expires + (60 * 60);
        setcookie($name, $value, $expires, $path);
        return true;
    }

    /**
    * check if cookie exists
    *
    * @param {string} $name Cookie name
    */
    public static function Exists(string $name)
    {
        return (isset($_COOKIE[$name]));
    }
    
    /**
    * get cookie value
    *
    * @param {string} $name Cookie name
    */
    public static function Get(string $name)
    {
        if(self::Exists($name)) return $_COOKIE[$name];
        return null;
    }

    /**
    * remove cookie
    *
    * @param {string} $name Cookie name
    */
    public static function Remove(string $name)
    {
        if(self::Exists($name)) {
            setcookie($name, $value, -1);
        }
    }
}