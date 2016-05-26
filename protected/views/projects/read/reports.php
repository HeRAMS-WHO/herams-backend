<?php

use \app\components\Html;

/**
 * @var \yii\web\View $this
 * @var \prime\models\ar\Project $model
 * @var \prime\models\ar\Tool $tool
 */

$items = [];
foreach($tool->generators as $generator) {
    $items[] = [
        'label' => \Yii::t('app', '{generator}', ['generator' => ucfirst($generator)]),
        'url' => [
            'reports/preview',
            'projectId' => $model->id,
            'reportGenerator' => $generator
        ]
    ];
}
//
if($model->userCan(\prime\models\permissions\Permission::PERMISSION_WRITE, app()->user->identity) && !empty($items)) {
    $caption = \yii\bootstrap\ButtonDropdown::widget([
        'label' => \Yii::t('app', 'Preview report using'),
        'containerOptions' => [
            'class' => 'pull-right'
        ],
        'options' => [
            'class' => 'btn-primary'
        ],
        'dropdown' => [
            'items' => $items

        ]

    ]);
}

echo \kartik\grid\GridView::widget([
    'caption' => Yii::t('app', "Published reports") . (isset($caption) ? $caption : ''),
    'dataProvider' =>
        new \yii\data\ActiveDataProvider([
            'query' => $model->getReports()
        ]),
    'columns' => [
        'id',
        'name',
        'published',
        'actions' => [
            'class' => \kartik\grid\ActionColumn::class,
            'template' => '{read}',
            'buttons' => [
                'read' => function($url, $model, $key) {
                    return Html::a(
                        Html::icon('eye-open'),
                        [
                            'reports/read',
                            'id' => $model->id
                        ],
                        [
                            'target' => '_blank'
                        ]
                    );
                }
            ]
        ]
    ]
]);