<?php

require_once '../Core/Autoload.php';

try {

    $vit->build('index');

} catch(VIT\Exception\Build $e) {

    die($e->getMessage());
}