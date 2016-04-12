<?php
use app\components\Html;
use prime\models\ar\Setting;
return [
    'class' => \kartik\grid\ActionColumn::class,
    'width' => '100px',
    'template' => '{read} {share} {close}',
    'buttons' => [
        'read' => function($url, $model, $key) {
            $result = '';
            /** @var \prime\models\ar\Project $model */
            if($model->userCan(\prime\models\permissions\Permission::PERMISSION_READ)) {
                $result = Html::a(
                    Html::icon(Setting::get('icons.read')),
                    ['/projects/read', 'id' => $model->id],
                    [
                        'title' => \Yii::t('app', 'Enter')
                    ]
                );
            } else {
                $result = Html::a(
                    Html::icon(Setting::get('icons.requestAccess')),
                    '#',
                    [
                        'title' => \Yii::t('app', 'Get access?'),
                        'class' => 'request-access',
                        'data-project-name' => $model->title,
                        'data-project-owner' => isset($model->owner) ? $model->owner->name : null
                    ]
                );
            }
            return $result;
        },
        'update' => function($url, $model, $key) {
            $result = '';
            /** @var \prime\models\ar\Project $model */
            if($model->userCan(\prime\models\permissions\Permission::PERMISSION_WRITE)) {
                $result = Html::a(
                    Html::icon(Setting::get('icons.update')),
                    ['/projects/update', 'id' => $model->id],
                    [
                        'title' => \Yii::t('app', 'Update')
                    ]
                );
            }
            return $result;
        },
        'share' => function($url, $model, $key) {
            $result = '';
            /** @var \prime\models\ar\Project $model */
            if($model->userCan(\prime\models\permissions\Permission::PERMISSION_SHARE)) {
                $result = Html::a(
                    Html::icon(Setting::get('icons.share')),
                    ['/projects/share', 'id' => $model->id],
                    [
                        'title' => \Yii::t('app', 'Share')
                    ]
                );
            }
            return $result;
        },
        'close' => function($url, $model, $key) {
            $result = '';
            /** @var \prime\models\ar\Project $model */
            if($model->userCan(\prime\models\permissions\Permission::PERMISSION_ADMIN)) {
                $result = Html::a(
                    Html::icon(Setting::get('icons.close')),
                    ['/projects/close', 'id' => $model->id],
                    [
                        'data-confirm' => \Yii::t('app', 'Are you sure you want to close project <strong>{modelName}</strong>?', ['modelName' => $model->title]),
                        'data-method' => 'delete',
                        'class' => 'text-danger',
                        'title' => \Yii::t('app', 'Close')
                    ]
                );
            }
            return $result;
        }
    ]
];