<?php

use kartik\grid\ActionColumn;
use prime\helpers\Icon;
use prime\models\ar\Workspace;
use prime\models\permissions\Permission;
use yii\bootstrap\Html;

return [
    'class' => ActionColumn::class,
    'width' => '150px',
    'controller' => 'workspace',
    'template' => '{refresh} {update} {share} {delete} {export} {limesurvey} {responses}',
    'buttons' => [
        'refresh' => function ($url, Workspace $model, $key) {
            $result = '';
            if (\Yii::$app->user->can(Permission::PERMISSION_LIMESURVEY, $model)) {
                $result = Html::a(Icon::sync(), $url, [
                    'title' => \Yii::t('app', 'Refresh data from limesurvey')
                ]);
            }
            return $result;
        },
        'limesurvey' => function ($url, Workspace $model, $key) {
            $result = '';
            if (\Yii::$app->user->can(Permission::PERMISSION_LIMESURVEY, $model)) {
                $result = Html::a(
                    Icon::pencilAlt(),
                    $url,
                    [
                        'title' => \Yii::t('app', 'Data update')
                    ]
                );
            }
            return $result;
        },
        'update' => function ($url, Workspace $model, $key) {
            $result = '';
            if (\Yii::$app->user->can(Permission::PERMISSION_ADMIN, $model)) {
                $result = Html::a(
                    Icon::edit(),
                    $url,
                    [
                        'title' => \Yii::t('app', 'Update')
                    ]
                );
            }
            return $result;
        },
        'share' => function ($url, Workspace $model, $key) {
            $result = '';
            /** @var Workspace $model */
            if (\Yii::$app->user->can(Permission::PERMISSION_SHARE, $model)) {
                $result = Html::a(
                    Icon::share(),
                    $url,
                    [
                        'title' => \Yii::t('app', 'Share')
                    ]
                );
            }
            return $result;
        },
        'delete' => function ($url, Workspace $model, $key) {
            if (\Yii::$app->user->can(Permission::PERMISSION_DELETE, $model)) {
                return Html::a(
                    Icon::delete(),
                    $url,
                    [
                        'data-method' => 'delete',
                        'data-confirm' => \Yii::t('app', 'Are you sure you wish to remove this workspace from the system?')
                    ]
                );
            }
        },
        'export' => function ($url, Workspace $model, $key) {
            if (\Yii::$app->user->can(Permission::PERMISSION_EXPORT, $model)) {
                return Html::a(
                    Icon::download(),
                    $url,
                    [
                        'title' => \Yii::t('app', 'Download'),
                    ]
                );
            }
        },
        'responses' => function ($url, Workspace $model, $key) {
            if (\Yii::$app->user->can(Permission::PERMISSION_ADMIN, $model)) {
                return Html::a(
                    Icon::list(),
                    $url,
                    [
                        'title' => \Yii::t('app', 'Responses'),
                    ]
                );
            }
        }
    ]
];
