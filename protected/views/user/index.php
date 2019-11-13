<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use dektrium\user\models\UserSearch;
use kartik\grid\ActionColumn;
use kartik\grid\GridView;
use prime\models\permissions\Permission;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\Pjax;

/**
 * @var View $this
 * @var ActiveDataProvider $dataProvider
 * @var UserSearch $searchModel
 */
$this->params['breadcrumbs'][] = [
    'label' => \Yii::t('app', 'Admin dashboard'),
    'url' => ['/admin']
];
$this->title = Yii::t('user', 'Manage users');
$this->params['breadcrumbs'][] = [
    'label' => $this->title
];



?>
<?php Pjax::begin() ?>

<?= GridView::widget([
    'dataProvider' 	=> $dataProvider,
    'filterModel'  	=> $searchModel,
    'layout'  		=> "{items}\n{pager}",
    'columns' => [
        'id',
        'name',
        'email:email',
        [
            'attribute' => 'created_at',
            'value' => function ($model) {
                if (extension_loaded('intl')) {
                    return Yii::t('user', '{0, date, MMMM dd, YYYY HH:mm}', [$model->created_at]);
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
                'impersonate' => function ($url, $model) {
                    if(\Yii::$app->user->can(Permission::PERMISSION_ADMIN) && $model->id != Yii::$app->user->id) {
                        return Html::a('<span class="glyphicon glyphicon-user"></span>', ['/user/impersonate', 'id' => $model->id], [
                            'title' => Yii::t('user', 'Become this user'),
                            'data-confirm' => Yii::t('user', 'Are you sure you want to switch to this user for the rest of this Session?'),
                            'data-method' => 'post',
                        ]);
                    }
                }
            ]
        ],
    ],
]); ?>

<?php Pjax::end() ?>

