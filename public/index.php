<?php

use Kawanamiyuu\HtbFeed\Application;
use Kawanamiyuu\HtbFeed\AppModule;
use Kawanamiyuu\HtbFeed\Http\ResponderMiddleware;
use Kawanamiyuu\HtbFeed\Http\FeedResponseBuilderMiddleware;
use Ray\Di\Injector;

require dirname(__DIR__) . '/bootstrap/bootstrap.php';
require dirname(__DIR__) . '/vendor/autoload.php';

$injector = new Injector(new AppModule);

$app = $injector->getInstance(Application::class);
/* @var Application $app*/

$app->run([
    ResponderMiddleware::class,
    FeedResponseBuilderMiddleware::class
]);
