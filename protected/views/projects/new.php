<?php
use \yii\helpers\Html;
/** @var \yii\data\ActiveDataProvider $dataProvider */
/** @var \prime\models\Tool $tool */
foreach($dataProvider->models as $tool) {
    echo Html::beginTag('div', [
        'class' => 'col-xs-12 col-md-4 col-sm-6',
        'style' => [
            'padding' => '10px'
        ]
    ]);
    echo Html::a(Html::img($tool->imageUrl, [
        'style' => [
            'border' => '1px solid grey;',
            'width' => '100%',
            'padding' => '0px'
        ]
    ]), \yii\helpers\Url::to(['tools/read', 'id' => $tool->id]));
    echo Html::endTag('div');
}

?>

