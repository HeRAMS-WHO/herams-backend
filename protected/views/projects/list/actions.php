<?php
use app\components\Html;
use prime\models\ar\Setting;
use \prime\models\permissions\Permission;
return [
    'class' => \kartik\grid\ActionColumn::class,
    'width' => '100px',
    'template' => '{read}{request} {share} {close}{open}',
    'buttons' => [
        'read' => function($url, $model, $key) {
            $result = '';
            /** @var \prime\models\ar\Project $model */
            if(!$model->isClosed && $model->userCan(Permission::PERMISSION_READ, app()->user->identity)) {
                $result = Html::a(
                    Html::icon(Setting::get('icons.read')),
                    ['/projects/read', 'id' => $model->id],
                    [
                        'title' => \Yii::t('app', 'Enter')
                    ]
                );
            }
            return $result;
        },
        'request' => function($url, \prime\models\ar\Project $model, $key) {
            $result = '';
            if (!$model->isClosed && !$model->userCan(Permission::PERMISSION_READ, app()->user->identity)) {
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
            if(!$model->isClosed && $model->userCan(Permission::PERMISSION_WRITE, app()->user->identity)) {
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
            if(!$model->isClosed && $model->userCan(Permission::PERMISSION_SHARE, app()->user->identity)) {
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
            if(!$model->isClosed && $model->userCan(Permission::PERMISSION_ADMIN, app()->user->identity)) {
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
        },
        'open' => function($url, \prime\models\ar\Project $model, $key) {
            $result = '';
            if($model->isClosed && $model->userCan(Permission::PERMISSION_ADMIN, app()->user->identity)) {
                $result = Html::a(
                    Html::icon(Setting::get('icons.open')),
                    ['/projects/re-open', 'id' => $model->id],
                    [
                        'data-confirm' => \Yii::t('app', 'Are you sure you want to re-open project <strong>{modelName}</strong>?', ['modelName' => $model->title]),
                        'data-method' => 'put',
                        'class' => 'text-danger',
                        'title' => \Yii::t('app', 'Re-open')
                    ]
                );
            }
            return $result;
        }
    ]
];