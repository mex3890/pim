<?php

namespace Pim\Enums;

enum ExceptionCode: int
{
    case DeveloperException = 0;
    case LogicException = 1;
    case ThrowableException = 2;
    case NonThrowableException = 3;

}
