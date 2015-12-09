<?php
return [
    'components' => [
        'db' => [
            'class' => \yii\db\Connection::class,
            'charset' => 'utf8',
            'dsn' => 'mysql:host=localhost;dbname=primetest;',
            'password' => 'secret',
            'username' => 'root',
            'enableSchemaCache' => true,
            'schemaCache' => 'cache',
            'enableQueryCache' => true,
            'queryCache' => 'cache',
            'tablePrefix' => ''
        ]
    ]
];

