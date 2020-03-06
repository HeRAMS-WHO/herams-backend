<?php
declare(strict_types=1);

use prime\models\ar\Project;
use prime\helpers\Icon;
use yii\helpers\Html;
?>

<div class="stats">
    <?php
    $projects = Project::find()->withFields('contributorPermissionCount', 'facilityCount')->all();
    $stats = [];
    $stats[] = [
        'icon' => Icon::project(),
        'count' => count($projects),
        'label' => \Yii::t('app', 'Projects')
    ];
    $stats[] = [
        'icon' => Icon::healthFacility(),
        'count' =>  \iter\reduce(static function(int $accumulator, Project $project) {
            return $accumulator + $project->facilityCount;
        }, $projects, 0),
        'label' => \Yii::t('app', 'Health Facilities')
    ];

    $stats[] = [
        'icon' => Icon::contributors(),
        'count' =>\iter\reduce(static function(int $accumulator, Project $project) {
            return $accumulator + $project->contributorCount;
        }, $projects, 0),
        'label' => \Yii::t('app', 'Contributors')
    ];


    foreach($stats as $stat) {
        echo Html::beginTag('div', ['class' => 'stat']);
        echo $stat['icon'];
        echo Html::tag('span', $stat['count']);
        echo $stat['label'];
        echo Html::endTag('div');
    }

    if (!empty($projects)) {
        foreach($projects as $project) {
            if (!isset($latest) || $project->latestDate > $latest->latestDate) {
                $latest = $project;
            };
        }
        $latestResponse =  \prime\models\ar\Response::find()->orderBy(['date' => SORT_DESC])->limit(1)->one();
        $latestStatus =  "{$latest->title} / {$latest->latestDate}";
    } else {
        $latestStatus = \Yii::t('app', "No data loaded");
    }

    ?>
</div>
<div class="status"><?= Icon::sync() ?> Latest update: <span class="value"><?= $latestStatus ?></span></div>