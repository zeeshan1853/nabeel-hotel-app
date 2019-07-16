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
            'GET forget-password' => 'forget-password',
            'GET forget' => 'forget',
            'GET ishtehar' => 'ishtehar',
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
];

