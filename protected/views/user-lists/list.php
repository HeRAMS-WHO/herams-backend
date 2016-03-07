<?php

use app\components\Html;
use prime\models\ar\Setting;

/**
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $userListsDataProvider
 */

$this->params['subMenu']['items'] = [];

?>
<div class="col-xs-12">
    <?php
    $header = \Yii::t('app', 'Your user lists') . \yii\bootstrap\ButtonGroup::widget([
            'options' => [
                'class' => 'pull-right'
            ],
            'buttons' => [
                [
                    'label' => 'New user list',
                    'tagName' => 'a',
                    'options' => [
                        'href' => \yii\helpers\Url::to(['/user-lists/create']),
                        'class' => 'btn-primary'
                    ]
                ]
            ]
        ]);
    echo \kartik\grid\GridView::widget([
        'caption' => $header,
        'dataProvider' => $userListsDataProvider,
        'columns' => [
            'name',
            [
                'attribute' => 'user.name',
                'label' => \Yii::t('app', 'Owner')
            ],
            'actions' => [
                'class' => \kartik\grid\ActionColumn::class,
                'width' => '120px',
                'template' => '{read} {mail} {update} {share} {remove}',
                'buttons' => [
                    'read' => function($url, $model, $key) {
                        return Html::a(
                            Html::icon(Setting::get('icons.read')),
                            ['/user-lists/read', 'id' => $model->id]
                        );
                    },
                    'mail' => function($url, \prime\models\ar\UserList $model, $key) {
                        $bcc = implode(';', \yii\helpers\ArrayHelper::getColumn($model->users, 'email'));
                        return Html::a(
                            Html::icon('envelope'),
                            "mailto:" . app()->user->identity->email .  '?bcc=' . rawurlencode($bcc)
                            , [
                                'target' => '_blank'
                            ]
                        );
                    },
                    'share' => function($url, $model, $key) {
                        $result = '';
                        /** @var \prime\models\ar\UserList $model */
                        if($model->userCan(\prime\models\permissions\Permission::PERMISSION_SHARE)) {
                            $result = Html::a(
                                Html::icon(Setting::get('icons.share')),
                                ['/user-lists/share', 'id' => $model->id],
                                [
                                    'title' => \Yii::t('app', 'Share')
                                ]
                            );
                        }
                        return $result;
                    },
                    'update' => function($url, $model, $key) {
                        $result = '';
                        /** @var \prime\models\ar\UserList $model */
                        if($model->userCan(\prime\models\permissions\Permission::PERMISSION_WRITE)) {
                            $result = Html::a(
                                Html::icon(Setting::get('icons.update')),
                                ['/user-lists/update', 'id' => $model->id]
                            );
                        }
                        return $result;
                    },
                    'remove' => function($url, $model, $key) {
                        $result = '';
                        /** @var \prime\models\ar\UserList $model */
                        if($model->userCan(\prime\models\permissions\Permission::PERMISSION_SHARE)) {
                            $result = Html::a(
                                Html::icon(Setting::get('icons.remove')),
                                ['/user-lists/delete', 'id' => $model->id],
                                [
                                    'class' => 'text-danger',
                                    'data-confirm' => \Yii::t(
                                        'app',
                                        'Are you sure you want to delete list <strong>{name}</strong>?',
                                        ['name' => $model->name]
                                    ),
                                    'data-method' => 'delete'
                                ]
                            );
                        }
                        return $result;
                    }

                ]
            ]
        ]
    ]);
    ?>
</div>
