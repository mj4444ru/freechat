<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'freechat',
    'basePath' => dirname(__DIR__),
    'language' => 'ru-RU',
    'sourceLanguage' => 'ru-RU',
    'layout' => 'main',
    'charset' => 'utf-8',
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'cache' => [
            'class' => yii\caching\FileCache::class,
            'keyPrefix' => 'fc/',
        ],
        'cache2' => app\components\caching\LifeCache::class,
        'user' => [
            'identityClass' => app\models\User::class,
            'enableAutoLogin' => true,
            'loginUrl' => ['site/login'],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'normalizer' => yii\web\UrlNormalizer::class,
            'rules' => [
                '' => 'site/index',
                '<id:[1-9][0-9]+>' => 'site/anketa',
                '<action>' => 'site/<action>',
            ],
        ],
        'view' => [
            'class' => app\components\web\View::class,
//            'theme' => [
//                'basePath' => '@app/view/ru',
//                'baseUrl' => '@web',
//                'pathMap' => [
//                    '@app/views' => '@app/views/ru',
//                ],            ],
        ],
        'mailer' => [
            'class' => yii\swiftmailer\Mailer::class,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => yii\log\FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => require(__DIR__ . '/db.php'),
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => yii\debug\Module::class,
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
        'allowedIPs' => ['127.0.0.1', '::1', '172.*'],
    ];
    Yii::$container->set(mj4444\yii2gii\generators\model\Generator::class, [
        'requiredStrict' => true,
        'uniqueKeysWithoutValidators' => true,
        'foreignKeysWithoutValidators' => true,
        'excludedTables' => [$config['components']['db']['tablePrefix'] . 'migration'],
    ]);

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => yii\gii\Module::class,
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
        'allowedIPs' => ['127.0.0.1', '::1', '172.*'],
    ];
}

return $config;
