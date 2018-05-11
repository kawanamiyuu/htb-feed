<?php

use Kawanamiyuu\HtbFeed\Http\ResponseBuilder;
use Kawanamiyuu\HtbFeed\Http\ResponseEmitter;
use Kawanamiyuu\HtbFeed\Http\ResponseFactory;
use Relay\Relay;
use Zend\Diactoros\ServerRequestFactory;

require dirname(__DIR__) . '/bootstrap/bootstrap.php';
require dirname(__DIR__) . '/vendor/autoload.php';

$request = ServerRequestFactory::fromGlobals();

$relay = new Relay([
    new ResponseEmitter,
    new ResponseBuilder,
    new ResponseFactory
]);

$relay->handle($request);
