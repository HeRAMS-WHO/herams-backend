<?php

/**
 * @var \yii\web\View $this
 * @var \prime\models\ar\Project $model
 */

$getter = function(\SamIT\LimeSurvey\Interfaces\ResponseInterface $response, $i, $j, \yii\grid\DataColumn $column) {
    $method = "get" . ucfirst($column->attribute);
    if (method_exists($response, $method)) {
        $result = $response->$method();
    } elseif (isset($response->getData()[$column->attribute])) {
        $result = $response->getData()[$column->attribute];
    } else {
        $result = 'uknown';
    }
    return $result;

};
echo \kartik\grid\GridView::widget([
    'columns' => [
        [
            'attribute' => 'id',
            'value' => $getter
        ],
        [
            'attribute' => 'submitDate',
            'value' => $getter
        ],
        [
            'attribute' => 'q02[SQ001]',
            'value' => $getter,
            'label' => 'Name',
            'visible' => $model->getResponses()->size() > 0 && isset($model->getResponses()[0]->getData()['q02[SQ001'])
        ],
//        [
//            'attribute' => 'q011',
//            'value' => $getter,
//            'label' => 'Name',
//            'visible' => isset($model->getResponses()[0]->getData()['q011'])
//        ]
    ],
    'dataProvider' => new \yii\data\ArrayDataProvider([
        'allModels' => iterator_to_array($model->getResponses())
    ])
]);