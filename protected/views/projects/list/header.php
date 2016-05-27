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
            'label' => \Yii::t('app', 'Active projects'),
            'url' => ['/projects/list'],
        ],
        [
            'label' => \Yii::t('app', "Others projects"),
            'url' => ['/projects/list-others']
        ],
        [
            'label' => \Yii::t('app', 'Inactive projects'),
            'url' => ['/projects/list-closed']
        ],

    ]
]) . ButtonGroup::widget([
    'options' => [
        'class' => 'pull-right'
    ],
    'buttons' => [
        [
            'label' => 'New project',
            'tagName' => 'a',
            'options' => [
                'href' => Url::to(['projects/new']),
                'class' => 'btn-primary',
            ]
        ],
        [
            'label' => \Yii::t('app', 'Create'),
            'tagName' => 'a',
            'options' => [
                'href' => Url::to(['projects/create']),
                'class' => 'btn-default',
            ],
            'visible' => app()->user->can('admin')
        ],
    ]
]);