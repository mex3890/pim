<?php

namespace Pim;

use Pim\DataTransferObjects\ConfigFile;
use Pim\DataTransferObjects\RouteFile;

abstract class PaymentProvider
{
    abstract public static function actionMaps(): array;

    /**
     * @return RouteFile[]
     */
    abstract public static function customRoutes(): array;

    /**
     * Path to Provider config file.
     * @return null|ConfigFile
     */
    public static function configFilePath(): ?ConfigFile
    {
        return null;
    }

    /**
     * Store all services that need be loaded in application.
     * @return array
     */
    abstract public static function paymentServices(): array;
}
