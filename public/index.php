<?php

use Kawanamiyuu\HtbFeed\Application;
use Kawanamiyuu\HtbFeed\AppModule;
use Kawanamiyuu\HtbFeed\Http\ErrorHandlerMiddleware;
use Kawanamiyuu\HtbFeed\Http\ResponderMiddleware;
use Kawanamiyuu\HtbFeed\Http\FeedResponseBuilderMiddleware;
use Ray\Compiler\ScriptInjector;
use Ray\Di\Bind;
use Ray\Di\InjectorInterface;

define('ROOT_DIR', dirname(__DIR__));

require ROOT_DIR . '/bootstrap/bootstrap.php';
require ROOT_DIR . '/vendor/autoload.php';

$injector = new ScriptInjector(ROOT_DIR . '/var/tmp', function () use(&$injector) {
    $module = new AppModule;
    (new Bind($module->getContainer(), InjectorInterface::class))->toInstance($injector);
    return $module;
});

$app = $injector->getInstance(Application::class);
/* @var Application $app */

$app->run([
    ResponderMiddleware::class,
    ErrorHandlerMiddleware::class,
    FeedResponseBuilderMiddleware::class
]);
