<?php

use Carbon\Carbon;
use prime\helpers\Icon;
use prime\models\ar\Project;
use function iter\reduce;

$projects = Project::find()->all();
$this->beginContent('@views/layouts/map.php');


?>
<div class="popover">
    <?=$content; ?>
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
                }, 3600 * 24);
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