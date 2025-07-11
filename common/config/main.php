<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'timeZone' => 'Asia/Tashkent',
    'components' => [
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'cache' => [
            'class' => \yii\caching\FileCache::class,
        ],
        'telegram' => [
            'class' => 'aki\telegram\Telegram',
            'botToken' => '7617189066:AAGh9vNIuxu5wgJ7-jb8xZ4aNMeTGCx3aDw',
        ],
        'ikAmoCrm' => [
            'class' => 'common\components\AmoCrmClient',
        ],
        'ikPdf' => [
            'class' => 'common\components\Contract',
        ],
    ],
];
