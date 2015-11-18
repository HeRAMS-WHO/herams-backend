<?php

use app\components\Html;

/**
 * @var \yii\web\View $this
 * @var \prime\models\ar\Project $project
 */

echo Html::tag('h1', $project->title);

echo Html::beginTag('div', ['class' => 'row']);
echo Html::tag('div', Html::img($project->tool->imageUrl, ['style' => ['width' => '100%']]), ['class' => 'col-xs-3']);
echo Html::tag(
    'div',
    Html::tag('h4', $project->tool->title),
    ['class' => 'col-xs-9', 'style' => ['vertical-align' => 'top']]
);
/**
 * @var \prime\models\ar\Report $report
 */
foreach($project->reports as $report) {
    echo Html::tag(
        'div',
        Html::a($report->title, ['/reports/read', 'id' => $report->id], ['target' => '_blank']),
        ['class' => 'col-xs-12']
    );
}
echo Html::endTag('div');