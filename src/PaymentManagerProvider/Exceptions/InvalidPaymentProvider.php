<?php

namespace Pim\PaymentManagerProvider\Exceptions;

use InvalidArgumentException;
use Pim\Enums\ExceptionCode;

class InvalidPaymentProvider extends InvalidArgumentException
{
    public function __construct(string $class)
    {
        parent::__construct(
            "Invalid PaymentProvider class provided, expect \"Pim\PaymentProvider\" class, provided \"$class\".",
            ExceptionCode::DeveloperException
        );
    }
}
