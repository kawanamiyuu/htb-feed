<?php

declare(strict_types=1);

use K9u\Framework\ApplicationInterface;
use K9u\Framework\CachedInjectorFactory;
use Kawanamiyuu\HtbFeed\AppModule;
use Laminas\Diactoros\ServerRequestFactory;

const ROOT_DIR = __DIR__ . '/..';

require ROOT_DIR . '/bootstrap/bootstrap.php';
require ROOT_DIR . '/vendor/autoload.php';

$module = new AppModule();
$injector = (new CachedInjectorFactory(ROOT_DIR . '/build/scripts'))($module);

$app = $injector->getInstance(ApplicationInterface::class);
/* @var ApplicationInterface $app */

$request = ServerRequestFactory::fromGlobals();

$app($request);
