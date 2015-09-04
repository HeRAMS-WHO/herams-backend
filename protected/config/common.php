<?php
    return [
        'id' => 'befound',
        'name' => 'Befound Application Template',
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