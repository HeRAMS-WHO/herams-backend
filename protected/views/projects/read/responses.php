<?php

/**
 * @var \yii\web\View $this
 * @var \prime\models\Project $model
 */

echo \kartik\grid\GridView::widget([
    'dataProvider' => $model->getResponses()
]);