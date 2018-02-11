<?php

$params_local = require(__DIR__ . '/params-local.php');

$config = [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'TF7gTB806sOPRhaZDKBSlb1jijW9S10I',
        ],
//        'cache' => [
//            'class' => yii\caching\MemCache::class,
//            'useMemcached' => true,
//        ],
//        'cache' => [
//            'class' => yii\caching\ApcCache::class,
//            'useApcu' => true,
//        ],
        'assetManager' => [
            'appendTimestamp' => true,
            // comment out the following 9 lines when deployed to production
            'converter' => [
                'class' => yii\web\AssetConverter::class,
                'forceConvert' => YII_ENV_DEV,
                'commands' => [
                    'scss' => ['css', 'sass {from} {to} --style expanded'],
                    'sass' => ['css', 'sass {from} {to} --style expanded'],
                    'ts' => ['js', 'tsc --out {to} {from}'],
                ],
            ],
        ],
        'mailer' => [
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
    ],
    'params' => $params_local,
];

return $config;
