<?php

define('MONOLOG_READER', 1);
session_set_cookie_params(86400*30);

require __DIR__ . '/common/Session.php';
require __DIR__ . '/common/Request.php';
require __DIR__ . '/common/Response.php';
require __DIR__ . '/common/BaseController.php';
require __DIR__ . '/common/PageNotFoundController.php';
require __DIR__ . '/common/MonologReader.php';
require __DIR__ . '/common/functions.php';

$controller = new PageNotFoundController();
$controllerName = !empty($_GET['c']) ? $_GET['c'] : 'index';
$controllerClass = camelize($controllerName).'Controller';
$controllerFile = __DIR__.'/controllers/'.$controllerClass.'.php';

if (file_exists($controllerFile)) {
    require $controllerFile;

    $controller = new $controllerClass();
}

$request = new Request();

$controller->handleRequest($request)->send();
