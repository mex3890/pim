<?php

namespace Pim;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Pim\DataTransferObjects\ConfigFile;
use Pim\DataTransferObjects\RouteFile;
use Pim\PaymentManagerProvider\Exceptions\InvalidServiceMap;

final class PaymentManagerProvider extends ServiceProvider
{
    private array $disabled_actions;
    private array $disabled_services;

    public function register(): void
    {
        $this->loadConfigFile(ConfigFile::make(
            __DIR__ . '/config/pim.php',
            'pim.php',
            'pim'
        ));
    }

    public function boot(): void
    {
        $this->loadPaymentProviders();

        $this->registerRoutes();
    }

    private function registerRoutes(): void
    {
        /** @var RouteFile $route */
        foreach (ActionManager::getRoutes() as $route) {
            $method = $route->method->value;
            $route_full_key = $route->service_key . '.' . $route->action_key;

            Route::$method($route->path, function (Request $request) use ($route) {
                return ActionManager::callActionsByRouteName($route->service_key, $route->action_key, $request);
            })->name($route_full_key);
        }
    }

    private function loadPaymentProviders(): void
    {
        $this->disabled_actions = Config::get('pim.disabled_actions', []);
        $this->disabled_services = Config::get('pim.disabled_services', []);

        foreach (Config::get('pim.payment_providers', []) as $payment_provider) {
            $this->loadPaymentProvider(new $payment_provider);
        }
    }

    private function loadPaymentService(PaymentService $paymentService, array $enabled_service_actions): void
    {
        if (empty($actions = $paymentService::serviceActions()) || empty($enabled_service_actions)) {
            return;
        }

        if (!empty($routes = $paymentService::customRoutes())) {
            ActionManager::addRoutes($routes);
        }

        $payment_service_key = $paymentService::serviceKey();

        foreach ($actions as $action) {
            if ($this->isActionEnabled(new $action, $payment_service_key, $enabled_service_actions)) {
                ActionManager::addAction($payment_service_key, new $action);
            }
        }
    }

    private function loadConfigFile(ConfigFile $config): void
    {
        $this->publishes([$config->origin_full_path => config_path($config->final_filename)], $config->group_key);
        $this->mergeConfigFrom($config->origin_full_path, $config->group_key);
    }

    private function extractMapActionKeys(array $service_maps): array
    {
        $enabled_actions = [];
        foreach ($service_maps as $service_map) {
            if (!((new $service_map) instanceof ServiceMap)) {
                throw new InvalidServiceMap($service_map);
            }

            $enabled_actions[$service_map::serviceKey()] = array_merge(
                $enabled_actions[$service_map::serviceKey()] ?? [],
                $service_map::actions()
            );
        }

        return $enabled_actions;
    }

    private function isActionEnabled(
        ServiceAction $action,
        string        $payment_service_key,
        array         $enabled_service_actions
    ): bool
    {
        return !in_array(
                ($action_key = $action::actionKey()),
                $this->disabled_actions[$payment_service_key] ?? []) &&
            in_array($action_key, $enabled_service_actions[$payment_service_key]);
    }

    private function loadPaymentProvider(PaymentProvider $paymentProvider): void
    {
        if (($config = $paymentProvider::configFilePath()) instanceof ConfigFile) {
            $this->loadConfigFile($config);
        }

        if (!empty($routes = $paymentProvider::customRoutes())) {
            ActionManager::addRoutes($routes);
        }

        if (!empty($payment_services = $paymentProvider::paymentServices())) {
            $enabled_service_actions = $this->extractMapActionKeys($paymentProvider::actionMaps());

            foreach ($payment_services as $payment_service) {
                if (!in_array($payment_service::serviceKey(), $this->disabled_services)) {
                    $this->loadPaymentService(new $payment_service, $enabled_service_actions);
                }
            }
        }
    }
}
