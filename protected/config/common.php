<?php
    return [
        'id' => 'prime',
        'name' => 'Prime 2.0',
        'basePath' => realpath(__DIR__ . '/../'),
        'timeZone' => 'UTC',
        'sourceLanguage' => 'en',

        'components' => [
            'user' => [
                'class' => \yii\web\User::class,
                'identityClass' => \app\models\User::class
            ],
        ]
    ];