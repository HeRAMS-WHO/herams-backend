<?php

use app\components\Html;

/**
 * @var \yii\web\View $this
 */

$this->params['subMenu']['items'] = [];

$this->params['subMenu']['items'][] = [
    'label' => \Yii::t('app', 'Create'),
    'url' => ['/user-lists/create']
];

echo \kartik\grid\GridView::widget([
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
