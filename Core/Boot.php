<?php

@session_start();
@ob_start();

$app = new Core\Config\App;

/**
* Set default timezone
*
*/
date_default_timezone_set($app::getConfig('timezone'));

/**
* Use vit
*
*/

try {

    $vit = new VIT\VIT( $config = ['dir' => $app::getConfig('tpl_directory')] );

    /**
    * Assign globals
    *
    */
    $vit->assign(['config' => $app::Config(), 'time' => time()]);

} catch(VIT\Exception\Config $e) {

    die($e->getMessage());

} catch(VIT\Exception\Build $e) {

    die($e->getMessage());
}