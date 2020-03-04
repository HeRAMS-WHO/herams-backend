<?php

use prime\helpers\Icon;
use prime\models\ar\Project;
use prime\models\ar\User;

$this->beginContent('@views/layouts/map.php');


?>
<div class="popover">
    <a href="/" style="position: absolute; right: 10px; top: 10px;"><?= Icon::close(); ?></a>
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
            foreach (Project::find()->each() as $project) {
                   $count += $project->contributorCount;
            }
            echo $count;
            ?></span>
            Contributors
        </div>
        <div class="stat">
            <?= Icon::healthFacility(); ?>
            <span>
            <?php
            echo \prime\models\ar\Response::find()->cache(600)->select('count(distinct [[workspace_id]], [[hf_id]])')->scalar();
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
            $latestResponse =  \prime\models\ar\Response::find()->orderBy(['date' => SORT_DESC])->limit(1)->one();
            if (isset($latestResponse)) {
                echo "{$latestResponse->project->title} / {$latestResponse->date}";
            } else {
                echo "No data loaded";
            }
            ?></span>
    </div>
</div>
<?php

$this->endContent();