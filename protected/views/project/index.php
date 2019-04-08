<?php

use kartik\grid\GridView;
use prime\helpers\Icon;
use prime\models\ar\Project;
use prime\models\ar\Setting;
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
            'title',
            'workspaceCount',
            [
                'label' => 'Cache status',
                'value' => function(Project $project) {
                    $timestamp = \Yii::$app->limesurveyDataProvider->responseCacheTime($project->base_survey_eid);
                    return isset($timestamp) ? \Carbon\Carbon::createFromTimestamp($timestamp)->diffForHumans() : null;
                }
            ],
            'actions' => [
                'class' => \kartik\grid\ActionColumn::class,
                'width' => '100px',
                'template' => '{view} {update} {share} {remove} {workspaces}',
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
                                Icon::eye(),
                                ['project/view', 'id' => $model->id],
                                ['title' => 'View']

                            );
                            return $result;
                        }

                    },
                    'update' => function($url, Project $model, $key) {
                        if(app()->user->can(Permission::PERMISSION_WRITE, $model)) {
                            return Html::a(
                                Icon::edit(),
                                ['project/update', 'id' => $model->id], [
                                    'title' => \Yii::t('app', 'Edit')
                                ]
                            );

                        }

                    },
                    'share' => function($url, Project $model, $key) {
                        if(app()->user->can('share', $model)) {
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
                                    'data-confirm' => \Yii::t('app', 'Are you sure you wish to remove this tool from the system?')
                                ]
                            );
                        }
                    },
                    'deactivate' => function($url, Project $model, $key) {
                        /** @var Project $model */
                        $result = '';
                        if(app()->user->can('admin') && $model->getWorkspaceCount() > 0) {
                            $result = Html::a(
                                Html::icon(Setting::get('icons.stop')),
                                ['project/delete', 'id' => $model->id],
                                [
                                    'data-method' => 'delete',
                                    'data-confirm' => \Yii::t('app', 'Are you sure you wish to disable this tool?')
                                ]
                            );
                        }
                        return $result;
                    },
                ]
            ]
        ]
    ]);