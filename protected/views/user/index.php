<?php

declare(strict_types=1);

use herams\common\models\Permission;
use kartik\grid\ActionColumn;
use kartik\grid\GridView;
use prime\helpers\Icon;
use prime\models\search\User;
use prime\widgets\menu\TabMenu;
use prime\widgets\Section;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\View;

/**
 * @var View $this
 * @var ActiveDataProvider $dataProvider
 * @var User $searchModel
 */
$this->title = \Yii::t('app', 'Users');

$this->beginBlock('tabs');
echo TabMenu::widget([
    'tabs' => [
        [
            'permission' => Permission::PERMISSION_ADMIN,
            'url' => ['admin/dashboard'],
            'title' => \Yii::t('app', 'Dashboard'),
        ],
        [
            'permission' => Permission::PERMISSION_ADMIN,
            'url' => ['user/index'],
            'title' => \Yii::t('app', 'Users'),
        ],
        [
            'permission' => Permission::PERMISSION_ADMIN,
            'url' => ['admin/share'],
            'title' => \Yii::t('app', 'Global permissions'),
        ],
    ],
]);
$this->endBlock();

Section::begin()
    ->withHeader(\Yii::t('app', 'Users'));

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'layout' => "{items}\n{pager}",
    'columns' => [
        'id',
        'name',
        'email:email',
        [
            'attribute' => 'created_at',
            'value' => function ($model) {
                $formattedDateTime = Yii::$app->formatter->format($model->created_at, [
                    'datetime',
                    'format' => 'MMMM dd, YYYY HH:mm',
                ]);
                return Html::encode($formattedDateTime);
            },
            'filter' => false,
        ],
        [
            'class' => ActionColumn::class,
            'width' => '100px',
            'template' => '{impersonate}{delete}',
            'deleteOptions' => [
                'data-method' => 'delete',
            ],
            'buttons' => [
                'delete' => function ($url, $model) {
                    if (\Yii::$app->user->can(Permission::PERMISSION_ADMIN) && $model->id != Yii::$app->user->id) {
                        return Html::a(Icon::trash(), [
                            '/user/delete',
                            'id' => $model->id,
                        ], [
                            'title' => Yii::t('app', 'Delete this user'),
                            'data-confirm' => Yii::t('app', 'Are you sure you want to delete this user ?'),
                            'data-method' => 'delete',
                        ]);
                    }
                },
                'impersonate' => function ($url, $model) {
                    if (\Yii::$app->user->can(Permission::PERMISSION_ADMIN) && $model->id != Yii::$app->user->id) {
                        return Html::a(Icon::user(), [
                            '/user/impersonate',
                            'id' => $model->id,
                        ], [
                            'title' => Yii::t('app', 'Become this user'),
                            'data-confirm' => Yii::t('app', 'Are you sure you want to switch to this user for the rest of this Session?'),
                            'data-method' => 'post',
                        ]);
                    }
                },
            ],

        ],
    ],
]);

Section::end();
