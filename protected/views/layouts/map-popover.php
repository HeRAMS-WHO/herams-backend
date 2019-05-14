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
        <div class="stat">
            <?= Icon::contributors(); ?>
            <span><?=
                Permission::find()->where([
                    'target' => Workspace::class,
                    'permission' => [
                        Permission::PERMISSION_WRITE,
                        Permission::PERMISSION_ADMIN
                    ]
                ])->
                distinct()
                    ->select('source_id')
                    ->count();
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
    </div>
    <div class="status"><?= Icon::sync() ?> Latest update: <span class="value">
            <?php
            if (false !== $ts = \Yii::$app->cache->get('lastUpdatedTimestamp')) {
                $lastUpdated = Carbon::createFromTimestampUTC($ts)->diffForHumans();
            } else {
                $lastUpdated = \Yii::t('app', 'Unknown');
            }

            if (false !== $projectId = Yii::$app->cache->get('lastUpdatedProject')) {
                $lastProject = Project::findOne(['id' => $projectId])->title;
            } else {
                $lastProject = \Yii::t('app', 'Unknown');
            }

            echo "$lastProject / $lastUpdated";

            ?></span>
    </div>

</div>
<?php

$this->endContent();