<?php

namespace Pim;

use Pim\DataTransferObjects\RouteFile;

interface PaymentService
{
    public static function serviceKey(): string;
    public static function serviceActions(): array;
    /**
     * @return RouteFile[]
     */
    public static function customRoutes(): array;
}
