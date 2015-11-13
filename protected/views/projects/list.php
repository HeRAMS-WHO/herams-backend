<?php

use \app\components\Html;

/**
 * @var $projectsDataProvider \yii\data\ActiveDataProvider
 */

$this->params['subMenu']['items'] = [
    [
        'label' => \Yii::t('app', 'Create'),
        'url' => ['projects/create'],
        'visible' => app()->user->can('admin')
    ],
    [
        'label' => \Yii::t('app', 'New'),
        'url' => ['projects/new'],
    ]
];
?>
<div class="col-xs-12">
    <?php
    
    echo \kartik\grid\GridView::widget([
        'caption' => Yii::t('app', 'Your projects'),
        'layout' => "{items}\n{pager}",
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
                'template' => '{read} {update}',
                'buttons' => [
                    'read' => function($url, $model, $key) {
                        $result = Html::a(
                            Html::icon('eye-open'),
                            ['/projects/read', 'id' => $model->id]
                        );
                        return $result;
                    },
                    'update' => function($url, $model, $key) {
                        $result = '';
                        /** @var \prime\models\ar\Project $model */
                        if($model->userCan(\prime\models\permissions\Permission::PERMISSION_WRITE)) {
                            $result = Html::a(
                                Html::icon('pencil'),
                                ['/projects/update', 'id' => $model->id]
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
