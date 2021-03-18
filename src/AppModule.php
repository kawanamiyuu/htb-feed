<?php

declare(strict_types=1);

namespace Kawanamiyuu\HtbFeed;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use K9u\Framework\FrameworkModule;
use Kawanamiyuu\HtbFeed\Http\ConstraintValidatorFactory;
use Kawanamiyuu\HtbFeed\Http\ValidatorProvider;
use Ray\Di\AbstractModule;
use Ray\Di\Scope;
use Symfony\Component\Validator\ConstraintValidatorFactoryInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AppModule extends AbstractModule
{
    protected function configure(): void
    {
        $this->install(new FrameworkModule([], __DIR__ . '/Http'));

        $this->bind(ClientInterface::class)
            ->to(Client::class)->in(Scope::SINGLETON);

        // TODO: extract to ValidatorModule

        $this->bind(ConstraintValidatorFactoryInterface::class)
            ->to(ConstraintValidatorFactory::class)->in(Scope::SINGLETON);

        $this->bind(ValidatorInterface::class)
            ->toProvider(ValidatorProvider::class)->in(Scope::SINGLETON);
    }
}
