<?php

use Kawanamiyuu\HtbFeed\AppModule;
use Kawanamiyuu\HtbFeed\Http\RouterMiddleware;
use Kawanamiyuu\HtbFeed\Http\ResponderMiddleware;
use Ray\Di\Injector;
use Relay\Relay;
use Zend\Diactoros\ServerRequestFactory;

require dirname(__DIR__) . '/bootstrap/bootstrap.php';
require dirname(__DIR__) . '/vendor/autoload.php';

$request = ServerRequestFactory::fromGlobals();

$injector = new Injector(new AppModule);

$relay = new Relay([
    ResponderMiddleware::class,
    RouterMiddleware::class
], function ($middleware) use($injector) {
    return $injector->getInstance($middleware);
});

$relay->handle($request);
