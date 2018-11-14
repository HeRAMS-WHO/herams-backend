<?php

use yii\helpers\Html;

/** @var \yii\data\ActiveDataProvider $dataProvider */
/** @var \yii\web\View $this */
$this->title = \Yii::t('app', "Create project");
echo Html::tag('h1', \Yii::t('app', "Choose a tool below for more information."), ['class' => 'col-md-12']);

/** @var \prime\models\ar\Tool $tool */
foreach($dataProvider->models as $tool) {
    echo Html::beginTag('div', [
        'class' => 'col-xs-12 col-md-4 col-sm-6',
        'style' => [
            'padding' => '10px'
        ]
    ]);
    echo Html::a(Html::img($tool->imageUrl, [
        'alt' => $tool->title,

    ]), \yii\helpers\Url::to(['tools/read', 'id' => $tool->id]), [
        'class' => 'button-grid',
        'title' => $tool->title

    ]);
    echo Html::endTag('div');
}

?>

