<?php

session_start();

require_once __DIR__ . '/vendor/autoload.php';
error_reporting(E_ALL & ~E_NOTICE);

use App\App;
use App\Lib\Erro;

try {
    $app = new App();
    $app->run();
}catch (\Exception $e){
    $oError = new Erro($e);
    $oError->render();
}