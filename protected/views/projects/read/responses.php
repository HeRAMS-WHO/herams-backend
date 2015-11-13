<?php

/**
 * @var \yii\web\View $this
 * @var \prime\models\ar\Project $model
 */

echo \kartik\grid\GridView::widget([
    'dataProvider' => new \yii\data\ArrayDataProvider(['allModels' => iterator_to_array($model->getResponses())])
]);