<?php

use app\components\Html;

/**
 * @var \yii\web\View $this
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
        'dataProvider' => new \yii\data\ActiveDataProvider([
            'query' => app()->user->identity->getUserLists()
        ]),
        'columns' => [
            'name',
            'actions' => [
                'class' => \kartik\grid\ActionColumn::class,
                'template' => '{read} {update} {remove}',
                'buttons' => [
                    'read' => function($url, $model, $key) {
                        return Html::a(
                            Html::icon('eye-open'),
                            ['/user-lists/read', 'id' => $model->id]
                        );
                    },
                    'update' => function($url, $model, $key) {
                        return Html::a(
                            Html::icon('pencil'),
                            ['/user-lists/update', 'id' => $model->id]
                        );
                    },
                    'remove' => function($url, $model, $key) {
                        return Html::a(
                            Html::icon('trash'),
                            ['/user-lists/delete', 'id' => $model->id],
                            [
                                'class' => 'text-danger',
                                'data-confirm' => \Yii::t('app', 'Are you sure you want to delete list <strong>{name}</strong>?', ['name' => $model->name]),
                                'data-method' => 'delete'
                            ]
                        );
                    }

                ]
            ]
        ]
    ]);
    ?>
</div>
