<?php

namespace Core\Config;

use \PDO;

class Database
{

    /**
    * database configuration
    */
    
    public static function Config()
    {

        return (object) [

            /**
            * default error mode
            *
            */
            'error_mode' => \PDO::ERRMODE_EXCEPTION,

            /**
            * set default fetch mode
            *
            */
            'fetch_method' => \PDO::FETCH_OBJ,

            /**
            * default database driver
            *
            */
            'driver' => 'mysql',

            /**
            * database connection
            *
            */
            'connection' => (object) [

                /**
                * database hostname
                *
                */
                'hostname' => 'localhost',

                /**
                * database connection username
                *
                */
                'username' => 'nex',

                /**
                * database connection password
                *
                */
                'password' => 'nex123',

                /**
                * database table name
                *
                */
                'database' => 'nex'

            ]
        ];
    }
}