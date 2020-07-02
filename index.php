<?php

define('MONOLOG_READER', 1);

require __DIR__ . '/autoload.php';

use MonologReader\HttpFoundation\Request;
use MonologReader\Kernel;

$kernel = new Kernel();
$request = new Request();

$response = $kernel->handleRequest($request);

$response->send();
