<?php

/** @var View $this */
use prime\widgets\chart\Chart;
use prime\widgets\map\Map;
use yii\web\View;


echo $this->render('view/filters', [
    'types' => $types
]);
echo \yii\helpers\Html::beginTag('div', ['class' => 'content']);
foreach($elements as $element)
{
    switch ($element['type']) {
        case 'pie':
            echo Chart::widget([
                'title' => trim(explode(':', $element['question']->getText())[0], "\n:"),
                'data' => $element['data']
            ]);
            break;
        case 'map':
            echo Map::widget([
                'options' => [
                    'class' => 'map'
                ],
                "baseLayers" => [
                    [
                        "type" => Map::TILE_LAYER,
                        "url" => "https://services.arcgisonline.com/arcgis/rest/services/World_Street_Map/MapServer/tile/{z}/{y}/{x}",
                    ]
                ],
                "data" => $element['data']

            ]);




    }
}
?>
<div class="table">
    <h1>Priority areas / Functionality</h1>
    <table>
        <thead>
        <tr><th>Name</th><th>Dysfunctionality level (%)</th><th>Main cause</th><th>(%)</th></tr>
        </thead>
        <tbody>
        <tr><td>Facility X</td><td>100</td><td>Lack of medical supplies</td><td>100</td></tr>
        <tr><td>Facility X</td><td>100</td><td>Lack of medical supplies</td><td>100</td></tr>
        <tr><td>Facility X</td><td>100</td><td>Lack of medical supplies</td><td>100</td></tr>
        <tr><td>Facility X</td><td>100</td><td>Lack of medical supplies</td><td>100</td></tr>
        <tr><td>Facility X</td><td>100</td><td>Lack of medical supplies</td><td>100</td></tr>
        </tbody>
    </table>
</div>
<?php
    echo \yii\helpers\Html::endTag('div');