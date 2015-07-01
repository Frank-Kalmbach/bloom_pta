<?php
require 'vendor/autoload.php';

//setting local timezone for log
date_default_timezone_set('America/Kentucky/Louisville');

//creating an 'alias' for Logger so can reduce repeat use
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

//creating a new object variable for logging with following methods:
$log = new Logger('name');
$log->pushHandler(new StreamHandler('app.log', Logger::WARNING));

$log->addWarning('Foo');
$log->addError('Bar');

echo 'Hello, World!';




?>