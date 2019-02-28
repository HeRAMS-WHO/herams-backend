<?php

use yii\bootstrap\ButtonGroup;
use yii\bootstrap\Nav;
use yii\helpers\Url;

echo Nav::widget([
    'options' => [
        'class' => 'pull-left nav-tabs',
        'style' => ['margin-right' => '10px']
    ],
    'items' => [
        [
            'label' => \Yii::t('app', 'My workspaces'),
            'url' => ['project/workspaces', 'id' => $project->id],
        ],
        [
            'label' => \Yii::t('app', "Other workspaces"),
            'url' => ['project/workspaces', 'id' => $project->id, 'accessible' => 1]
        ],
        [
            'label' => \Yii::t('app', 'Inactive workspaces'),
            'url' => ['project/workspaces', 'id' => $project->id, 'active' => 0]
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
            'visible' => app()->user->can('instantiate', ['model' => \prime\models\ar\Project::class])
        ],
    ]
]);
