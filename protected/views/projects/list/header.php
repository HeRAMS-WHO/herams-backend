<?php
use yii\bootstrap\ButtonGroup;
use yii\bootstrap\Nav;
use yii\helpers\Url;

return Nav::widget([
    'options' => [
        'class' => 'pull-left nav-tabs',
        'style' => ['margin-right' => '10px']
    ],
    'items' => [
        [
            'label' => \Yii::t('app', 'My workspaces'),
            'url' => ['/projects/list'],
        ],
        [
            'label' => \Yii::t('app', "Other workspaces"),
            'url' => ['/projects/list-others']
        ],
        [
            'label' => \Yii::t('app', 'Inactive workspaces'),
            'url' => ['/projects/list-closed']
        ],

    ]
]) . ButtonGroup::widget([
    'options' => [
        'class' => 'pull-right'
    ],
    'buttons' => [
        [
            'label' => \Yii::t('app', 'Create workspace'),
            'tagName' => 'a',
            'options' => [
                'href' => Url::to(['projects/create', 'toolId' => $tool->id]),
                'class' => 'btn-default',
            ],
            'visible' => app()->user->can('instantiate', ['model' => \prime\models\ar\Tool::class])
        ],
    ]
]);
