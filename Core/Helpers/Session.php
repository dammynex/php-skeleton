<?php

namespace Core\Helpers;

/**
*
* Manages sessions
* creates, edits and removes sessions
*/
class Session
{

    /**
    * adds new cookie
    *
    * @param {string} $name Session name
    * @param {any} $value Session value
    */
    public static function Set(string $name, $value)
    {
        $_SESSION[$name] = $value;
        return $value;
    }

    /**
    * check if session exists
    *
    * @param {string} $name Session name
    */
    public static function Exists(string $name)
    {
        return (isset($_SESSION[$name]));
    }
    
    /**
    * get session value
    *
    * @param {string} $name Session name
    */
    public static function Get(string $name)
    {
        if(self::Exists($name)) return $_SESSION[$name];
        return null;
    }

    /**
    * remove session
    *
    * @param {string} $name session name
    */
    public static function Remove(string $name)
    {
        if(self::Exists($name)) {
            unset($_SESSION[$name]);
        }
    }
}