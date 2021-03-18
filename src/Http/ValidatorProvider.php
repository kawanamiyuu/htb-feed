<?php

declare(strict_types=1);

namespace Kawanamiyuu\HtbFeed\Http;

use Ray\Di\ProviderInterface;
use Symfony\Component\Validator\ConstraintValidatorFactoryInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ValidatorProvider implements ProviderInterface
{
    private ConstraintValidatorFactoryInterface $constraintValidatorFactory;

    public function __construct(ConstraintValidatorFactoryInterface $constraintValidatorFactory)
    {
        $this->constraintValidatorFactory = $constraintValidatorFactory;
    }

    /**
     * @inheritDoc
     */
    public function get(): ValidatorInterface
    {
        return Validation::createValidatorBuilder()
            ->setConstraintValidatorFactory($this->constraintValidatorFactory)
            ->enableAnnotationMapping()
            ->getValidator();
    }
}
