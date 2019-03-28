<?php

use kartik\grid\GridView;
use prime\assets\SassAsset;
use prime\helpers\Icon;
use yii\bootstrap\Html;
use prime\models\ar\Setting;
use yii\bootstrap\ButtonGroup;
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
 * @var \yii\data\ActiveDataProvider $toolsDataProvider
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
                    'visible' => app()->user->can('tools')
                ],
            ]
        ]),
        'dataProvider' => $toolsDataProvider,
        'layout' => "{items}\n{pager}",
        'columns' => [
            'title',
            [
                'label' => 'Cache status',
                'value' => function(\prime\models\ar\Project $project) {
                    $timestamp = \Yii::$app->limesurveyDataProvider->responseCacheTime($project->base_survey_eid);
                    return isset($timestamp) ? \Carbon\Carbon::createFromTimestamp($timestamp)->diffForHumans() : null;
                }
            ],
            'actions' => [
                'class' => \kartik\grid\ActionColumn::class,
                'width' => '100px',
                'template' => '{view} {update} {share} {remove} {workspaces}',
                'buttons' => [
                    'workspaces' => function($url, $model, $key) {
                        /** @var \prime\models\ar\Project $model */
                        $result = Html::a(
                            Icon::list(),
                            ['project/workspaces', 'id' => $model->id],
                            ['title' => 'Workspaces']

                        );
                        return $result;
                    },
                    'view' => function($url, $model, $key) {
                        /** @var \prime\models\ar\Project $model */
                        $result = Html::a(
                            Icon::eye(),
                            ['project/view', 'id' => $model->id],
                            ['title' => 'View']

                        );
                        return $result;
                    },
                    'update' => function($url, $model, $key) {
                        /** @var \prime\models\ar\Project $model */
                        $result = '';
                        if(app()->user->can('admin')) {
                            $result = Html::a(
                                Icon::pencilAlt(),
                                ['project/update', 'id' => $model->id], [
                                    'title' => \Yii::t('app', 'Edit')
                                ]
                            );
                        }
                        return $result;
                    },
                    'share' => function($url, \prime\models\ar\Project $model, $key) {
                        /** @var \prime\models\ar\Project $model */
                        $result = '';
                        if(app()->user->can('share', ['model' => \prime\models\ar\Project::class, 'modelId' => $model->primaryKey])) {
                            $result = Html::a(
                                Icon::share(),
                                ['project/share', 'id' => $model->id], [
                                    'title' => \Yii::t('app', 'Share')
                                ]
                            );
                        }
                        return $result;
                    },
                    'remove' => function($url, \prime\models\ar\Project $model, $key) {
                        /** @var \prime\models\ar\Project $model */
                        $result = '';
                        if(
                            app()->user->can('admin')
                            && $model->getWorkspaceCount() == 0
                        ) {
                            $result = Html::a(
                                Html::icon(Setting::get('icons.remove')),
                                ['project/delete', 'id' => $model->id],
                                [
                                    'data-method' => 'delete',
                                    'data-confirm' => \Yii::t('app', 'Are you sure you wish to remove this tool from the system?')
                                ]
                            );
                        }
                        return $result;
                    },
                    'deactivate' => function($url, \prime\models\ar\Project $model, $key) {
                        /** @var \prime\models\ar\Project $model */
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