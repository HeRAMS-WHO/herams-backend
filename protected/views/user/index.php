<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use kartik\grid\ActionColumn;
use kartik\grid\GridView;
use prime\models\ar\Permission;
use prime\widgets\menu\TabMenu;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use prime\helpers\Icon;
use yii\web\View;

/**
 * @var View $this
 * @var ActiveDataProvider $dataProvider
 * @var UserSearch $searchModel
 */
$this->title = \Yii::t('app', 'Administration');

$tabs = [
    [
        'url' => ['project/index'],
        'title' => \Yii::t('app', 'Projects')
    ]
];

if (\Yii::$app->user->can(Permission::PERMISSION_ADMIN)) {
    $tabs[] =
        [
            'url' => ['user/index'],
            'title' => \Yii::t('app', 'Users')
        ];
    $tabs[] =
        [
            'url' => ['admin/share'],
            'title' => \Yii::t('app', 'Global permissions')
        ];
    $tabs[] =
        [
            'url' => ['admin/limesurvey'],
            'title' => \Yii::t('app', 'Backend administration')
        ];
}

echo TabMenu::widget([
    'tabs' => $tabs,
    'currentPage' => $this->context->action->uniqueId
]);

echo Html::beginTag('div', ['class' => 'content']);
?>
<div class="form-content form-bg full-width">
    <?php
    echo GridView::widget([
        'dataProvider'  => $dataProvider,
        'filterModel'   => $searchModel,
        'layout'        => "{items}\n{pager}",
        'columns' => [
            'id',
            'name',
            'email:email',
            [
                'attribute' => 'created_at',

                'value' => function ($model) {
                    if (extension_loaded('intl')) {
                        return Yii::t('app', '{0, date, MMMM dd, YYYY HH:mm}', [$model->created_at]);
                    } else {
                        return date('Y-m-d G:i:s', $model->created_at);
                    }
                },
                'filter' => false
            ],
            [
                'class' => ActionColumn::class,
                'width' => '100px',
                'template' => '{impersonate}{delete}',
                'deleteOptions' => [
                    'data-method' => 'delete'
                ],
                'buttons' => [
                    'delete' => function ($url, $model) {
                        if (\Yii::$app->user->can(Permission::PERMISSION_ADMIN) && $model->id != Yii::$app->user->id) {
                            return Html::a(Icon::trash(), ['/user/delete', 'id' => $model->id], [
                                'title' => Yii::t('app', 'Delete this user'),
                                'data-confirm' => Yii::t('app', 'Are you sure you want to delete this user ?'),
                                'data-method' => 'delete',
                            ]);
                        }
                    },
                    'impersonate' => function ($url, $model) {
                        if (\Yii::$app->user->can(Permission::PERMISSION_ADMIN) && $model->id != Yii::$app->user->id) {
                            return Html::a(Icon::user(), ['/user/impersonate', 'id' => $model->id], [
                                'title' => Yii::t('app', 'Become this user'),
                                'data-confirm' => Yii::t('app', 'Are you sure you want to switch to this user for the rest of this Session?'),
                                'data-method' => 'post',
                            ]);
                        }
                    }
                ]
            ],
        ],
    ]);
    ?>
</div>
<?php
echo Html::endTag('div');