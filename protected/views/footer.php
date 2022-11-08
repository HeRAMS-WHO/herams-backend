<?php

use herams\common\models\Project;
use prime\helpers\Icon;
use yii\helpers\Html;

echo Html::beginTag('div', [
    'class' => 'footer',
]);
// Render all statistics.
/**
 * @var Project[] $projects
 * @var \prime\components\View $this
 */
$projects = Project::find()->withFields('contributorPermissionCount', 'facilityCount', 'latestDate', 'workspaceCount')->all();
$stats[] = [
    'icon' => Icon::project(),
    'count' => count($projects),
    'subject' => \Yii::t('app', 'HeRAMS projects'),
];
$stats[] = [
    'icon' => Icon::contributors(),
    'count' =>
        \iter\reduce(function (int $accumulator, Project $project) {
            return $accumulator + $project->contributorCount;
        }, $projects, 0),
    'subject' => \Yii::t('app', 'Contributors'),
];

$stats[] = [
    'icon' => Icon::healthFacility(),
    'count' => \iter\reduce(function (int $accumulator, Project $project) {
        return $accumulator + $project->facilityCount;
    }, $projects, 0),

    'subject' => \Yii::t('app', 'Health facilities'),
];

echo Html::beginTag('div', [
    'class' => 'stats',
]);
foreach ($stats as $stat) {
    echo Html::beginTag('div');
    echo $stat['icon'];
    echo Html::tag('div', $stat['count'], [
        'class' => 'counter',
    ]);
    echo Html::tag('div', $stat['subject'], [
        'class' => 'subject',
    ]);
    echo Html::endTag('div');
}
echo Html::endTag('div');

if (! empty($projects)) {
    $latest = $projects[0];

    foreach ($projects as $project) {
        if ($project->latestDate > $latest->latestDate) {
            $latest = $project;
        };
    }
    $localizedDate = \Yii::$app->formatter->asDate($latest->latestDate, 'short');
    $status = "{$latest->title} / {$localizedDate}";
} else {
    $status = \Yii::t('app', "No data loaded");
}

echo Html::beginTag('div', [
    'class' => 'status',
    'title' => $status,
]);
    echo Icon::recycling() . ' ';
    echo \Yii::t('app', 'Latest update') . ': ';
    echo Html::tag('span', $status, [
        'class' => 'value',
    ]);
echo Html::endTag('div');

echo Html::a(Icon::chevronLeft(), '#', [
    'class' => 'left',
    'id' => 'footer-left',
]);
echo Html::a(Icon::chevronRight(), '#', [
    'class' => 'right',
    'id' => 'footer-right',
]);

echo Html::endTag('div');
$this->registerJs(
    <<<JS
try {
    const footer = document.querySelector('.footer .stats');
    let timer;
    const moveRight = function() {
        footer.append(footer.firstChild);
        clearTimeout(timer);
        timer = setTimeout(moveRight, 5000);
    };
    timer = setTimeout(moveRight, 5000);
    
    document.getElementById('footer-right').addEventListener('click', moveRight);
    document.getElementById('footer-left').addEventListener('click', function() {
        footer.prepend(footer.lastChild);
        clearTimeout(timer);
        timer = setTimeout(moveRight, 5000);
    });
} catch (error) {
    console.error("Error in footer JS", error);
}

JS
);


echo Html::endTag('div');
