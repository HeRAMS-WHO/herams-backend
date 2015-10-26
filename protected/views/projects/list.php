<?php

use \app\components\Html;

/**
 * @var $projectsDataProvider \yii\data\ActiveDataProvider
 */

$this->params['subMenu']['items'] = [];

$this->params['subMenu']['items'][] = [
    'label' => \Yii::t('app', 'Create'),
    'url' => ['projects/create'],
    'visible' => app()->user->identity->isAdmin
];
?>
<div class="col-xs-12">
    <?php
    echo \kartik\grid\GridView::widget([
        'dataProvider' => $projectsDataProvider,
        'columns' => [
            'title',
            [
                'attribute' => 'description',
                'format' => 'raw'
            ],
            'tool.title',
            'actions' => [
                'class' => \kartik\grid\ActionColumn::class,
                'template' => '{read}',
                'buttons' => [
                    'read' => function($url, $model, $key) {
                        $result = Html::a(
                            Html::icon('eye-open'),
                            ['projects/read', 'id' => $model->id]
                        );
                        return $result;
                    }
                ]
            ]
        ]
    ]);
    ?>
</div>
