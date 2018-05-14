<?php

use Kawanamiyuu\HtbFeed\Http\ResponseBuilderMiddleware;
use Kawanamiyuu\HtbFeed\Http\ResponseEmitterMiddleware;
use Kawanamiyuu\HtbFeed\Http\ResponseFactoryMiddleware;
use Relay\Relay;
use Zend\Diactoros\ServerRequestFactory;

require dirname(__DIR__) . '/bootstrap/bootstrap.php';
require dirname(__DIR__) . '/vendor/autoload.php';

$request = ServerRequestFactory::fromGlobals();

$relay = new Relay([
    new ResponseEmitterMiddleware,
    new ResponseBuilderMiddleware,
    new ResponseFactoryMiddleware
]);

$relay->handle($request);
