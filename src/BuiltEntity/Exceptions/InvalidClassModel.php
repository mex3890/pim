<?php

namespace Pim\BuiltEntity\Exceptions;

use InvalidArgumentException;

class InvalidClassModel extends InvalidArgumentException
{
    public function __construct(?string $class)
    {
        $message = "Invalid argument, expected instanceof Illuminate\Database\Eloquent\Model and $class provided.";

        if ($class === null) {
            $message = 'Invalid argument, expected instanceof Illuminate\Database\Eloquent\Model and null provided.';
        }

        parent::__construct($message);
    }
}
