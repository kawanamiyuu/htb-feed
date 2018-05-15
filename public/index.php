<?php

use Kawanamiyuu\HtbFeed\Http\RouterMiddleware;
use Kawanamiyuu\HtbFeed\Http\ResponderMiddleware;
use Relay\Relay;
use Zend\Diactoros\ServerRequestFactory;

require dirname(__DIR__) . '/bootstrap/bootstrap.php';
require dirname(__DIR__) . '/vendor/autoload.php';

$request = ServerRequestFactory::fromGlobals();

$relay = new Relay([
    new ResponderMiddleware,
    new RouterMiddleware
]);

$relay->handle($request);
