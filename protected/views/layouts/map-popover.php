<?php

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

            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum sapien ex, efficitur vitae justo ut, tristique bibendum elit.
            Donec et mauris finibus, consequat ante at, convallis arcu. Integer tempor sodales libero.
        </p>
    </div>
    <div class="form">
        <?=$content; ?>
    </div>
    <div class="stats">
        <div class="stat">
            <?= \prime\helpers\Icon::clipboardList(); ?>
            <span><?= count($projects); ?></span>
            Projects
        </div>
        <div class="stat">
            <?= \prime\helpers\Icon::hospital(); ?>
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
            <?= \prime\helpers\Icon::user(); ?>
            <span><?= \prime\models\ar\User::find()->count() ?></span>
            Users
        </div>
    </div>
    <div class="status">Last updated: today</div>
</div>
<?php

$this->endContent();