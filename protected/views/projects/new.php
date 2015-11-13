<?php
use \yii\helpers\Html;
/** @var \yii\data\ActiveDataProvider $dataProvider */
/** @var \prime\models\ar\Tool $tool */
foreach($dataProvider->models as $tool) {
    echo Html::beginTag('div', [
        'class' => 'col-xs-12 col-md-4 col-sm-6',
        'style' => [
            'padding' => '10px'
        ]
    ]);
    echo Html::a(Html::img($tool->imageUrl, [
    ]), \yii\helpers\Url::to(['tools/read', 'id' => $tool->id]), [
        'class' => 'button-grid',
    ]);
    echo Html::endTag('div');
}

?>

