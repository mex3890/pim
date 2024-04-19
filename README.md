<p align="center">
<img src="./pim.png" height="200px">
</p>

`Payment Integration Manager`
#### Disable Payment Action

You can need disable an action key globally like `card.create` or a specific payment service action, PIM enable you to
work with two scenarios, first disable globally a Payment Action, to that just add action key in config `pim.php` like
bellow:

````php
> //pim.php

return [
    'disabled_actions' => [
        'pix' => [
            'create',
            'delete',
        ],   
    ],
];
````

Now to disable specific actions from a Payment Service, you need extend and create our new ServiceMap who list our
actions and to read this ServiceMap you need extend and create new PaymentProvider to use our custom ServiceMap with or
needed actions.

```php
<?php

use App\CustomPayment\PaymentServices\PixService\Actions\CreateAction;
use App\CustomPayment\PaymentServices\PixService\Actions\DestroyAction;
use Pim\ServiceMap;

class PixServiceMap extends ServiceMap
{
    public static function actionPrefix(): string
    {
        return 'pix';
    }

    public static function actions(): array
    {
        return [
            CreateAction::ACTION_KEY,
            DestroyAction::ACTION_KEY,
        ];
    }
}
```

```php
<?php

use App\CustomPayment\PaymentServices\PixService;
use App\CustomPayment\PaymentServices\PixService\PixServiceMap;
use Pim\DataTransferObjects\ConfigFile;
use Pim\PaymentProvider;

class CustomPaymentProvider extends PaymentProvider
{
    public static function actionMaps(): array
    {
        return [
            PixServiceMap::class,
        ];
    }
}
```

In the `PaymentProvider` you can override and add our customs `PaymentServices` like bellow:

```php
<?php

use App\CustomPayment\PaymentServices\PixService;
use App\CustomPayment\PaymentServices\PixService\PixServiceMap;
use Pim\DataTransferObjects\ConfigFile;
use Pim\PaymentProvider;

class CustomPaymentProvider extends PaymentProvider
{
    public static function paymentServices(): array
    {
        return [
            PixService::class,
        ];
    }
}
```

To disable a `PaymentService` you can add to config `pim.php` the service key that need be disabled like bellow:

```php
> //pim.php

return [
    'disabled_services' => [
        'pix',
    ],
];
```
