<?php
declare(strict_types=1);
return [
    'frontend' => [
        'chanathale/chanathale-customer/login-session-middleware' => [
            'target' => \Chanathale\ChanathaleCustomer\Middleware\LoginSessionMiddleware::class,
            'after' => [
                'typo3/cms-frontend/page-resolver'
            ]
        ],
    ]
];