<?php

namespace Pim\PaymentManagerProvider\Exceptions;

use InvalidArgumentException;
use Pim\Enums\ExceptionCode;

class InvalidServiceMap extends InvalidArgumentException
{
    public function __construct(string $class)
    {
        parent::__construct(
            "Invalid ServiceMap class provided, expect \"Pim\ServiceMap\" class, provided \"$class\".",
            ExceptionCode::DeveloperException
        );
    }
}
