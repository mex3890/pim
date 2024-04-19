<?php

namespace Pim;

use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Pim\DataTransferObjects\RouteFile;

class ActionManager
{
    private static array $actions = [];
    private static array $routes = [];

    /**
     * @param string $service_key
     * @param ServiceAction $action
     * @return void
     */
    public static function addAction(string $service_key, ServiceAction $action): void
    {
        self::$actions[$service_key][$action::actionKey()][] = $action;
    }

    public static function getActions(): array
    {
        return self::$actions;
    }

    public static function getActionsByRouteName(string $route_name): array
    {
        return self::$actions[$route_name] ?? [];
    }

    public static function callActionsByRouteName(string $service_key, string $action_key, Request $request)
    {
        $actions = self::$actions[$service_key][$action_key];
        $response = $actions[0]::handle($request);

        if ($actions[0]::canReturn($response, $request)) {
            return $response;
        }

        array_shift($actions);

        /** @var ServiceAction $action */
        foreach ($actions as $action) {
            $response = $action::handle($request);

            if ($action::canReturn($response, $request)) {
                return $response;
            }
        }

        return $response;
    }

    /**
     * @param RouteFile[] $routes
     * @return void
     */
    public static function addRoutes(array $routes): void
    {
        foreach ($routes as $route) {
            static::addRoute($route);
        }
    }

    public static function addRoute(RouteFile $routeFile): void
    {
        static::$routes[$routeFile->service_key . '.' . $routeFile->action_key] = $routeFile;
    }

    public static function getRoutes(): array
    {
        return static::$routes;
    }
}
