<?php

use \app\components\Html;

/**
 * @var $projectsDataProvider \yii\data\ActiveDataProvider
 */

?>
<div class="col-xs-12">
    <?php

//    \yii\bootstrap\Button::class
    $header = Yii::t('app', 'Your projects') . \yii\bootstrap\ButtonGroup::widget([
        'options' => [
            'class' => 'pull-right'
        ],
        'buttons' => [
            [
                'label' => 'New project',
                'tagName' => 'a',
                'options' => [
                    'href' => \yii\helpers\Url::to(['projects/new']),
                    'class' => 'btn-primary'
                ]
            ],
            [
                'label' => \Yii::t('app', 'Create'),
                'tagName' => 'a',
                'options' => [
                    'href' => \yii\helpers\Url::to(['projects/create']),
                    'class' => 'btn-default'
                ],
                'visible' => app()->user->can('admin')
            ],
        ]
    ]);
    echo \kartik\grid\GridView::widget([
        'caption' => $header,
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
