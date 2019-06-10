<?php

use yii\helpers\ArrayHelper;

$params = array_merge(
        require(__DIR__ . '/../../common/config/params.php'), require(__DIR__ . '/../../common/config/params-local.php'), require(__DIR__ . '/params.php'), require(__DIR__ . '/params-local.php')
);
$config = [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'modules' => [
        'v1' => [
            'class' => 'api\modules\v1\Module'
        ]
    ],
    'controllerNamespace' => 'api\controllers',
    'components' => [
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => false,
        ],
        'request' => [
            'enableCookieValidation' => false,
            'enableCsrfValidation' => false,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                    [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [],
        ]
        , 'response' => ['class' => \api\components\CResponse::class]
    ],
    'params' => $params,
];

/**
 * Rules for API versions
 *
 * For code cleanness and maintainability rules are grouped under each api versions inside
 * rules/v{versionNumber}.php file
 *
 * @author zeeshan
 */
$versionRules = [];
if (array_key_exists('modules', $config)) {
    foreach ((array) $config['modules'] as $module => $moduleConfig) {
        $ruleFile = sprintf('rules%s%s.php', DIRECTORY_SEPARATOR, $module);
        $ruleFile = sprintf('%s%s%s', __DIR__, DIRECTORY_SEPARATOR, $ruleFile);
        if (!file_exists($ruleFile)) {
            continue;
        }
        $versionRules = ArrayHelper::merge($versionRules, require $ruleFile);
    }
}
$versionRules = array_merge($config['components']['urlManager']['rules'], $versionRules);
$config['components']['urlManager']['rules'] = $versionRules;

return $config;
