<?php

namespace Pim\Entity\Exceptions;

use BadFunctionCallException;
use Pim\Enums\ExceptionCode;

class InvalidPropertyBuild extends BadFunctionCallException
{
    public function __construct(string $property)
    {
        parent::__construct(
            "Failed on try call property $property from non QiTechEntity object.",
            ExceptionCode::DeveloperException->value
        );
    }
}
