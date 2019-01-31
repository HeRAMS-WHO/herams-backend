<?php

use yii\bootstrap\Html;
use prime\models\ar\Setting;
use yii\bootstrap\ButtonGroup;
use yii\helpers\Url;

$this->title = \Yii::t('app', 'Manage projects');

/**
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $toolsDataProvider
 */
    echo \kartik\grid\GridView::widget([
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
            'actions' => [
                'class' => \kartik\grid\ActionColumn::class,
                'width' => '100px',
                'template' => '{view} {update} {share} {remove}',
                'buttons' => [
                    'view' => function($url, $model, $key) {
                        /** @var \prime\models\ar\Tool $model */
                        $result = Html::a(
                            Html::icon('eye-open', [
                                'title' => \Yii::t('app', 'View')
                            ]),
                            ['project/view', 'id' => $model->id]
                        );
                        return $result;
                    },
                    'update' => function($url, $model, $key) {
                        /** @var \prime\models\ar\Tool $model */
                        $result = '';
                        if(app()->user->can('admin')) {
                            $result = Html::a(
                                Html::icon('pencil', [
                                    'title' => \Yii::t('app', 'Edit')
                                ]),
                                ['project/update', 'id' => $model->id]
                            );
                        }
                        return $result;
                    },
                    'share' => function($url, \prime\models\ar\Tool $model, $key) {
                        /** @var \prime\models\ar\Tool $model */
                        $result = '';
                        if(app()->user->can('share', ['model' => \prime\models\ar\Tool::class, 'modelId' => $model->primaryKey])) {
                            $result = Html::a(
                                Html::icon('share-alt', [
                                    'title' => \Yii::t('app', 'Share')
                                ]),
                                ['project/share', 'id' => $model->id]
                            );
                        }
                        return $result;
                    },
                    'remove' => function($url, \prime\models\ar\Tool $model, $key) {
                        /** @var \prime\models\ar\Tool $model */
                        $result = '';
                        if(app()->user->can('admin') && $model->getProjectCount() == 0) {
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
                    'deactivate' => function($url, \prime\models\ar\Tool $model, $key) {
                        /** @var \prime\models\ar\Tool $model */
                        $result = '';
                        if(app()->user->can('admin') && $model->getProjectCount() > 0) {
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