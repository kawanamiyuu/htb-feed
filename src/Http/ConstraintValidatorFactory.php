<?php

declare(strict_types=1);

namespace Kawanamiyuu\HtbFeed\Http;

use Ray\Di\InjectorInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidatorFactoryInterface;
use Symfony\Component\Validator\ConstraintValidatorInterface;

class ConstraintValidatorFactory implements ConstraintValidatorFactoryInterface
{
    /**
     * @var ConstraintValidatorInterface[]
     */
    private array $validators;

    private InjectorInterface $injector;

    public function __construct(InjectorInterface $injector)
    {
        $this->injector = $injector;
    }

    /**
     * @inheritDoc
     */
    public function getInstance(Constraint $constraint): ConstraintValidatorInterface
    {
        $validatorClass = $constraint->validatedBy();

        return $this->validators[$validatorClass]
            ?? $this->validators[$validatorClass] = $this->injector->getInstance($validatorClass);
    }
}
