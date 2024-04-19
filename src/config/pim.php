<?php

use App\QiTech\QiTechPaymentProvider;

return [
    /** string[] provider that represent the Payment Api Providers. */
    'payment_providers' => [
        QiTechPaymentProvider::class,
    ],
    'disabled_actions' => [
//        'pix' => ['create']
    ],
    'disabled_services' => [
//        'pix'
    ],
];
