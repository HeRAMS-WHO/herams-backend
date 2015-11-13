<?php

use \app\components\Html;

/**
 * @var \yii\web\View $this
 * @var \prime\models\ar\Project $model
 */

$items = [];
foreach($model->tool->getGenerators() as $generator => $class) {
    $items[] = [
        'label' => \Yii::t('app', '{generator}', ['generator' => ucfirst($generator)]),
        'url' => [
            'reports/preview',
            'projectId' => $model->id,
            'reportGenerator' => $generator
        ]
    ];
}

if($model->userCan(\prime\models\permissions\Permission::PERMISSION_WRITE) && !empty($items)) {
    ?>
    <div class="dropdown">
        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
            <?=\Yii::t('app', 'Preview report using')?>
            <span class="caret"></span>
        </button>
        <?php
            echo \yii\bootstrap\Dropdown::widget(
            [
                'items' => $items
            ]
        );
    ?>
    </div>
    <?php
}

echo \kartik\grid\GridView::widget([
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