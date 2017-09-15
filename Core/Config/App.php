<?php

namespace Core\Config;

class App
{

    /**
    * Configurations are loaded from the "config.ini" file in this directory
    * To add a new setting, declare it in the ini file
    */
    public static function Config()
    {

        $config = parse_ini_file( __DIR__ . '/config.ini' );

        $domain = empty($config['domain_name']) ? $_SERVER['HTTP_HOST'] : $config['domain_name'];
        
        /**
        * Website url
        *
        */
        $config['url'] = $config['http_protocol'] . '://' . $domain;

        /**
        * Document root
        *
        */
        $config['root'] = $_SERVER['DOCUMENT_ROOT'] . '/' . $config['base_dir'];

        /**
        * Template directory
        *
        */
        $config['tpl_directory'] = $config['root'] . $config['tpl_folder_name'];

        return $config;
    }
    
    public static function getConfig($name)
    {
        return self::Config()[$name];
    }

}