<?php

use Kawanamiyuu\HtbFeed\AppModule;
use Kawanamiyuu\HtbFeed\Framework\ApplicationInterface;
use Laminas\Diactoros\ServerRequestFactory;
use Ray\Compiler\ScriptInjector;
use Ray\Di\Bind;
use Ray\Di\InjectorInterface;

define('ROOT_DIR', dirname(__DIR__));

require ROOT_DIR . '/bootstrap/bootstrap.php';
require ROOT_DIR . '/vendor/autoload.php';

$injector = new ScriptInjector(ROOT_DIR . '/var/tmp', function () use (&$injector) {
    $module = new AppModule();
    (new Bind($module->getContainer(), InjectorInterface::class))->toInstance($injector);
    return $module;
});

$app = $injector->getInstance(ApplicationInterface::class);
/* @var ApplicationInterface $app */

$request = ServerRequestFactory::fromGlobals();

$app($request);
