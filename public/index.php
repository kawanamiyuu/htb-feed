<?php

use Kawanamiyuu\HtbFeed\Application;
use Kawanamiyuu\HtbFeed\AppModule;
use Kawanamiyuu\HtbFeed\Http\ErrorHandler;
use Kawanamiyuu\HtbFeed\Http\ResponseEmitter;
use Kawanamiyuu\HtbFeed\Http\RequestHandler;
use Ray\Compiler\ScriptInjector;

define('ROOT_DIR', dirname(__DIR__));

require ROOT_DIR . '/bootstrap/bootstrap.php';
require ROOT_DIR . '/vendor/autoload.php';

$injector = new ScriptInjector(ROOT_DIR . '/var/tmp', function () {
    return new AppModule();
});

(new Application($injector))->run([
    ResponseEmitter::class,
    ErrorHandler::class,
    RequestHandler::class
]);
