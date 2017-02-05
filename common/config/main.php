<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => 'localhost',
            'port' => 6379,
            'database' => 0,
        ],
        'amqp' => [
            'class' => 'webtoucher\amqp\components\Amqp',
            'host' => 'xxx',
            'port' => 5672,
            'vhost' => '/',
        ],
    ],
];
