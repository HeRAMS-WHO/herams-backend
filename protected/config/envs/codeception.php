<?php
return [
    'components' => [
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
            'tablePrefix' => ''
        ],
        'mailer' => [
            'class' => \yii\swiftmailer\Mailer::class,
            'useFileTransport' => true
        ],

    ]
];

