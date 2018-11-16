<?php
/** @var \prime\models\ar\Tool $tool */
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
            'visible' => $tool->getResponses()->size() > 0 && array_key_exists('q02[SQ001', $tool->getResponses()->get(0)->getData())
        ],
    ],
    'dataProvider' => new \yii\data\ArrayDataProvider([
        'allModels' => iterator_to_array($tool->getResponses()->sort(function(\SamIT\LimeSurvey\Interfaces\ResponseInterface $r1, \SamIT\LimeSurvey\Interfaces\ResponseInterface $r2) {
            return $r1->getSubmitDate() < $r2->getSubmitDate() ? 1 : -1;
        }))
    ])
]);