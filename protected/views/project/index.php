<?php

use kartik\grid\ActionColumn;
use kartik\grid\GridView;
use prime\helpers\Icon;
use prime\models\ar\Project;
use prime\models\permissions\Permission;
use yii\bootstrap\ButtonGroup;
use yii\bootstrap\Html;
use yii\helpers\Url;

$this->params['breadcrumbs'][] = [
    'label' => \Yii::t('app', 'Admin dashboard'),
    'url' => ['/admin']
];
$this->title = \Yii::t('app', 'Projects');
$this->params['breadcrumbs'][] = [
    'label' => $this->title
];
/**
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $projectProvider
 */
    echo GridView::widget([
        'caption' => ButtonGroup::widget([
            'options' => [
                'class' => 'pull-right',
                'style' => [
                    'margin-bottom' => '10px'
                ]
            ],
            'buttons' => [
                [
                    'label' => \Yii::t('app', 'Create project'),
                    'tagName' => 'a',
                    'options' => [
                        'href' => Url::to(['project/create']),
                        'class' => 'btn-default',
                    ],
                    'visible' => app()->user->can(Permission::PERMISSION_ADMIN)
                ],
            ]
        ]),
        'dataProvider' => $projectProvider,
        'layout' => "{items}\n{pager}",
        'columns' => [
            'id',
            'title',
            [
                'label' => \Yii::t('app', '# Workspaces'),
                'attribute'  => 'workspaceCount',
            ],
            [
                'label' => \Yii::t('app', '# Contributors'),
                'attribute' => 'contributorCount'
            ],
            [
                'label' => \Yii::t('app', '# Health facilities'),
                'attribute' => 'facilityCount'
            ],
            [
                'label' => \Yii::t('app', '# Responses'),
                'attribute' => 'responseCount'
            ],
            'actions' => [
                'class' => ActionColumn::class,
                'width' => '100px',
                'template' => '{view} {workspaces} {update} {share} {remove}',
                'buttons' => [
                    'workspaces' => function($url, Project $model, $key) {
                        $result = Html::a(
                            Icon::list(),
                            ['project/workspaces', 'id' => $model->id],
                            ['title' => 'Workspaces']

                        );
                        return $result;
                    },
                    'view' => function($url, Project $model, $key) {
                        if (!empty($model->pages)
                            && app()->user->can(Permission::PERMISSION_READ, $model)
                        ) {
                            $result = Html::a(
                                Icon::project(),
                                ['project/view', 'id' => $model->id],
                                ['title' => \Yii::t('app', 'Project dashboard')]

                            );
                            return $result;
                        }

                    },
                    'update' => function($url, Project $model, $key) {
                        if(app()->user->can(Permission::PERMISSION_ADMIN, $model)) {
                            return Html::a(
                                Icon::edit(),
                                ['project/update', 'id' => $model->id], [
                                    'title' => \Yii::t('app', 'Edit')
                                ]
                            );

                        }

                    },
                    'check' => function($url, Project $model, $key) {
                        if(app()->user->can(Permission::PERMISSION_ADMIN, $model)) {
                            return Html::a(
                                Icon::checkSquare(),
                                ['project/check', 'id' => $model->id], [
                                    'title' => \Yii::t('app', 'Check data')
                                ]
                            );

                        }

                    },
                    'share' => function($url, Project $model, $key) {
                        if(app()->user->can(Permission::PERMISSION_ADMIN, $model)) {
                            $result = Html::a(
                                Icon::share(),
                                ['project/share', 'id' => $model->id], [
                                    'title' => \Yii::t('app', 'Share')
                                ]
                            );
                            return $result;
                        }

                    },
                    'remove' => function($url, Project $model, $key) {
                        if(
                            app()->user->can(Permission::PERMISSION_ADMIN)
                            && $model->canBeDeleted()
                        ) {
                            return Html::a(
                                Icon::delete(),
                                ['project/delete', 'id' => $model->id],
                                [
                                    'data-method' => 'delete',
                                    'data-confirm' => \Yii::t('app', 'Are you sure you wish to remove this project from the system?')
                                ]
                            );
                        }
                    },
                ]
            ]
        ]
    ]);