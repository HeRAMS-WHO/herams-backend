<?php

use Carbon\Carbon;
use prime\helpers\Icon;
use prime\models\ar\Project;
use yii\helpers\Html;
use function iter\reduce;

$projects = Project::find()->all();
$this->beginContent('@views/layouts/map.php');


?>
<div class="popover">
    <div class="intro">
        <?=Html::img('@web/img/HeRAMS.png'); ?>
        <p>
            The Health Resources and Services Availability Monitoring System is a collaborative process for the
            monitoring of essential health resources and services in support to the identification of needs,
            gaps and priorities
        </p>
    </div>
    <div class="form">
        <?=$content; ?>
    </div>
    <div class="stats">
        <div class="stat">
            <?= \prime\helpers\Icon::project(); ?>
            <span><?= count($projects); ?></span>
            Projects
        </div>
        <div class="stat">
            <?= \prime\helpers\Icon::healthFacility(); ?>
            <span><?php
                echo \Yii::$app->cache->getOrSet('totalFacilityCount', function() use ($projects) {
                    return reduce(function (?int $accumulator, Project $project, string $key) {
                        return $accumulator + \iter\count($project->getHeramsResponses());
                    }, $projects);
                });
                ?></span>
            Health Facilities
        </div>
        <div class="stat">
            <?= \prime\helpers\Icon::users(); ?>
            <span><?= \prime\models\ar\User::find()->count() ?></span>
            Users
        </div>
    </div>
    <div class="status"><?= Icon::sync() ?> Last updated: <span class="value"><?=$projects[0]->title . ' / ' . Carbon::now()->subHour(mt_rand(1, 100))->diffForHumans() ?></span></div>
</div>
<?php

$this->endContent();