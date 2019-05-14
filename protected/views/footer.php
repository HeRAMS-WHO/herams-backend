<div class="footer">
<?php

use Carbon\Carbon;
use prime\helpers\Icon;
use prime\models\ar\Project;
use yii\helpers\Html;

echo Icon::project(['class' => 'subject']);
echo Html::tag('div', count($projects), [
    'class' => 'counter'
]);
echo Html::tag('div', 'HeRAMS projects', [
    'class' => 'subject'
]);
?>
<div class="status"><?= Icon::sync() ?> Most recently updated: <span class="value">
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
    <?php

echo Html::a(Icon::chevronLeft(), '#', ['class' => 'left']);
echo Html::a(Icon::chevronRight(), '#', ['class' => 'right']);

echo Html::endTag('div');
?>

</div>
