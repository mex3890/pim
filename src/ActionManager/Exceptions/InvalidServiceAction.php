<?php

namespace Pim\ActionManager\Exceptions;

use InvalidArgumentException;
use Pim\Enums\ExceptionCode;

class InvalidServiceAction extends InvalidArgumentException
{
    public function __construct(string $action_class)
    {
        parent::__construct(
            "Invalid ServiceAction class, expect \"Pim/ServiceAction\" instance, \"$action_class\" provided.",
            ExceptionCode::DeveloperException
        );
    }
}
