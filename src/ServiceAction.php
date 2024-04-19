<?php

namespace Pim;

use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;

interface ServiceAction
{
    public static function actionKey(): string;

    public static function handle(Request $request): Response;

    public static function canReturn(Response $response, Request $request): bool;

    public static function middlewares(): array;
}
