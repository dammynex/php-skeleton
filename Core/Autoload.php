<?php

namespace Core;

/**
* Autoloader
*
*/
spl_autoload_register(function ($cls) {

    $dir = __DIR__;

    $module = "/../{$cls}.php";

    $raw_filename = $dir . $module;

    $filename = str_replace('\\', '/', $raw_filename);

    if(file_exists($filename)) {

        require_once $filename;
    }
});

/**
* Include template engine
*
*/
require_once __DIR__ . '/VIT/VITAutoload.php';

/**
* Include bootloader
*
*/
require_once __DIR__ . '/Boot.php';