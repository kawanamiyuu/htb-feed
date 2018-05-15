<?php

use Kawanamiyuu\HtbFeed\AppModule;
use Kawanamiyuu\HtbFeed\Http\RouterMiddleware;
use Kawanamiyuu\HtbFeed\Http\ResponderMiddleware;
use Psr\Http\Message\ServerRequestInterface;
use Ray\Di\Injector;
use Relay\Relay;

require dirname(__DIR__) . '/bootstrap/bootstrap.php';
require dirname(__DIR__) . '/vendor/autoload.php';

$injector = new Injector(new AppModule);

$request = $injector->getInstance(ServerRequestInterface::class);

$relay = new Relay([
    ResponderMiddleware::class,
    RouterMiddleware::class
], function ($middleware) use($injector) {
    return $injector->getInstance($middleware);
});

$relay->handle($request);
