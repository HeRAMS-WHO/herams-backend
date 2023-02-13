<?php

declare(strict_types=1);

use herams\common\models\Project;
use prime\helpers\Icon;
use yii\helpers\Html;

?>

<div class="stats">
    <?php

    $projects = Project::find()->withFields('contributorPermissionCount', 'facilityCount', 'latestDate', 'workspaceCount')->all();
$stats = [];
$stats[] = [
    'icon' => Icon::project(),
    'count' => count($projects),
    'label' => \Yii::t('app', 'Projects'),
];
$stats[] = [
    'icon' => Icon::healthFacility(),
    'count' => \iter\reduce(static function (int $accumulator, Project $project) {
        return $accumulator + $project->facilityCount;
    }, $projects, 0),
    'label' => \Yii::t('app', 'Health Facilities'),
];

$stats[] = [
    'icon' => Icon::contributors(),
    'count' => \iter\reduce(static function (int $accumulator, Project $project) {
        return $accumulator + $project->contributorCount;
    }, $projects, 0),
    'label' => \Yii::t('app', 'Contributors'),
];


foreach ($stats as $stat) {
    echo Html::beginTag('div', [
        'class' => 'stat',
    ]);
    echo $stat['icon'];
    echo Html::tag('span', $stat['count']);
    echo $stat['label'];
    echo Html::endTag('div');
}



?>
</div>
<?php
// TODO: Implement latest status when localized titles for projects work properly
return;
if (! empty($projects)) {
    $latestStatus = '';

    $latest = array_pop($projects);
    foreach ($projects as $project) {
        if ($project->latestDate > $latest->latestDate) {
            $latest = $project;
        };
    }
    $latestStatus = "{$latest->title} / {$latest->latestDate}";
} else {
    $latestStatus = \Yii::t('app', "No data loaded");
}
echo Html::tag('div', Icon::recycling() . ' ' . \Yii::t('app', 'Latest update') . Html::tag('span', $latestStatus), [
    'class' => 'status',
]);
