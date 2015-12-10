<?php

use app\components\Html;

/**
 * @var \yii\web\View $this
 * @var \prime\models\Country $country
 */

echo Html::tag('h1', $country->name);


foreach($country->getReportsGroupedByTool() as $toolId => $reports)
{
    echo Html::beginTag('div', ['class' => 'row']);
        echo Html::tag('div', Html::img(reset($reports)->project->tool->imageUrl, ['style' => ['width' => '100%']]), ['class' => 'col-xs-3']);
        echo Html::tag(
            'div',
            Html::tag('h4', reset($reports)->project->tool->title),
            ['class' => 'col-xs-9', 'style' => ['vertical-align' => 'top']]
        );
        /**
         * @var int $id
         * @var \prime\models\ar\Report $report
         */
    foreach($reports as $id => $report) {
            echo Html::tag(
                'div',
                Html::a($report->title, ['/reports/read', 'id' => $report->id], ['target' => '_blank']),
                ['class' => 'col-xs-12']
            );
        }
    echo Html::endTag('div');

}