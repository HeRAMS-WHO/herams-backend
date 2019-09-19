<?php

use Carbon\Carbon;
use prime\helpers\Icon;
use prime\models\ar\Project;
use prime\models\ar\User;
use prime\models\ar\Workspace;
use prime\models\permissions\Permission;
use yii\helpers\Html;
use function iter\reduce;

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
            <?= Icon::project(); ?>
            <span><?= Project::find()->count(); ?></span>
            Projects
        </div>
        <div class="stat" style="display: none;">
            <?= Icon::contributors(); ?>
            <span><?php
               $count = 0;
               /** @var Project $project */
               foreach(Project::find()->each() as $project) {
                   $count += $project->getContributorCount();
               }
               echo $count;
            ?></span>
            Contributors
        </div>
        <div class="stat">
            <?= Icon::healthFacility(); ?>
            <span>
            <?php
            echo \Yii::$app->cache->get('totalFacilityCount') ?: '?';
            ?>
            </span>
            Health Facilities
        </div>
        <div class="stat">
            <?= Icon::user(); ?>
            <span><?= User::find()->count(); ?></span>
            Users
        </div>

    </div>
    <div class="status"><?= Icon::sync() ?> Latest update: <span class="value">
            <?php
            $latestResponse =  \prime\models\ar\Response::find()->orderBy(['last_updated' => SORT_DESC])->limit(1)->one();
            if (false !== $ts = \Yii::$app->cache->get('lastUpdatedTimestamp')) {
                $lastUpdated = Carbon::createFromTimestampUTC($ts)->diffForHumans();
            } else {
                $lastUpdated = $latestResponse->last_updated;
            }

            if (false !== $projectId = Yii::$app->cache->get('lastUpdatedProject')) {
                $lastProject = Project::findOne(['id' => $projectId])->title;
            } else {
                $lastProject = Project::findOne(['base_survey_eid' => $latestResponse->survey_id])->title;
            }

            echo "$lastProject / $lastUpdated";

            ?></span>
    </div>
</div>
<?php

$this->endContent();