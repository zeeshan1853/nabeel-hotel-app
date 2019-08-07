<?php

/**
 * url rules for api version 1
 * @author zeeshan
 */
use api\components\CUrlRule;

return [
        [
        'class' => CUrlRule::class,
        'controller' => 'v1/user',
        'extraPatterns' => [
            'GET test' => 'test',
            'POST register' => 'register',
            'POST login' => 'login',
            'POST forget-password' => 'forget-password',
            'GET forget' => 'forget',
            'POST reset-password' => 'reset-password',
            'GET ishtehar' => 'ishtehar'
        ],
    ],
        [
        'class' => CUrlRule::class,
        'controller' => 'v1/hotel',
        'extraPatterns' => [
            'GET list' => 'index',
            'GET detail' => 'detail',
            'GET test' => 'test',
            'POST create' => 'create',
            'GET categories' => 'categories',
            'GET liked-hotels' => 'liked-hotels',
            'POST like-hotel' => 'like-hotel',
        ],
    ],
        [
        'class' => CUrlRule::class,
        'controller' => 'v1/site',
        'extraPatterns' => [
            'POST contact' => 'index'
        ]
    ]
];

