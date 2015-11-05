<?php

use \app\components\Html;

/**
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $countriesDataProvider
 */

$this->params['subMenu']['items'] = [];

?>
<div class="col-xs-12">
    <?php
    echo \kartik\grid\GridView::widget([
        'dataProvider' => $countriesDataProvider,
        'columns' => [
            'name',
            'actions' => [
                'class' => \kartik\grid\ActionColumn::class,
                'template' => '{update}',
                'buttons' => [
                    'update' => function($url, $model, $key) {
                        return Html::a(
                            Html::icon('pencil'),
                            ['/countries/update', 'id' => $model->id]
                        );
                    }
                ]
            ]
        ]
    ]);
    ?>
</div>
