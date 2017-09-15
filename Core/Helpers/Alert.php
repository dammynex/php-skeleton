<?php

namespace Core\Helpers;

use Core\Helpers\Session;

/**
* Manage user notifications
*
*/
class Alert
{

    protected const NAME = 'YOUR_ALERT_SESSION_NAME';

    /**
    * get current notification message
    *
    */
    public static function Get()
    {
        $data = Session::Exists(self::NAME) ? Session::Get(self::NAME) : null;
        Session::Remove(self::NAME);
        return $data;
    }

    /**
    * set new alert message
    *
    * @param {string} $type (error|success|warning|info) Notification type
    * @param {string} $message Notification message
    */
    public static function Set($type, $message)
    {
        Session::Set(self::NAME, [
            'type' => $type,
            'message' => $message
        ]);
        return true;
    }

    /**
    * set error message
    *
    * @param {string} $message Error message
    */
    public static function SetError($message)
    {
        return self::Set('error', $message);
    }

    /**
    * set notification message
    *
    * @param {string} $message Notification message
    */
    public static function SetNotif($message)
    {
        return self::Set('notif', $message);
    }

    /**
    * set success alert
    *
    * @param {string} $message Notification message
    */
    public static function SetSuccess($message)
    {
        return self::Set('success', $message);
    }

    /**
    * set warning alert
    *
    * @param {string} $message Notification message
    */
    public static function SetWarning($message)
    {
        return self::Set('warning', $message);
    }

}