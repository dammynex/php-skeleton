<?php

namespace Core\Misc\Functions;

use Core\Config\App;

function getnow()
{
    return date('Y-m-d H:i:s');
}


function filterText($str)
{
    return htmlspecialchars(nl2br($str));
}

function redirect($endpoint)
{
    $url = App::getConfig('url');
    header('location:' . $url . $endpoint);
    exit;
}