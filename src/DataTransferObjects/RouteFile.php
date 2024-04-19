<?php

namespace Pim\DataTransferObjects;

use Pim\Enums\HttpMethod;

final readonly class RouteFile
{
    final public static function make(
        string $action_key,
        string $service_key,
        HttpMethod $method,
        ?string $path = null
    ): RouteFile
    {
        return new self($action_key, $service_key, $method, $path ?? "$service_key/$action_key");
    }

    private function __construct(
        public string     $action_key,
        public string     $service_key,
        public HttpMethod $method,
        public string     $path
    )
    {
    }
}
