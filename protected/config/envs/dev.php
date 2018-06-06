<?php
return [
    'components' => [
        'urlManager' => defined('CONSOLE') && CONSOLE ? ['hostInfo' => 'https://prime.projects.sam-it.eu'] : [],
        'db' => [
            'class' => \yii\db\Connection::class,
            'charset' => 'utf8',
            'dsn' => 'mysql:host=' . getenv('DB_HOST') . ';dbname=' . getenv('MYSQL_DATABASE'),
            'password' => getenv('MYSQL_PASSWORD'),
            'username' => getenv('MYSQL_USER'),
            'enableSchemaCache' => true,
            'schemaCache' => 'cache',
            'enableQueryCache' => true,
            'queryCache' => 'cache',
            'tablePrefix' => 'prime2_'
        ],
        'assetManager' => [
            'converter' => \lucidtaz\yii2scssphp\ScssAssetConverter::class
        ],
    ]
];

