<?php

namespace Pim;

abstract class ServiceMap
{
    abstract public static function serviceKey(): string;
    abstract public static function actions(): array;
}
