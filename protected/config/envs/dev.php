<?php
return [
    'components' => [
        'urlManager' => CONSOLE ? ['hostInfo' => 'https://prime.projects.sam-it.eu'] : [],
        'db' => [
            'class' => \yii\db\Connection::class,
            'charset' => 'utf8',
            'dsn' => 'mysql:host=localhost;dbname=who;',
            'password' => 'z2P6NUSj3YfcfVH4',
            'username' => 'who',
            'enableSchemaCache' => true,
            'schemaCache' => 'cache',
            'enableQueryCache' => true,
            'queryCache' => 'cache',
            'tablePrefix' => 'prime2_',
        ]
    ]
];

