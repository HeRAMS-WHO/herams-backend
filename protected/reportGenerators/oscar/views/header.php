<?php

use app\components\Html;

/**
 * @var \prime\models\ar\Project $project
 */

?>
<div class="row header">
    <div class="col-xs-12 text-medium">
        <?=\Yii::t('oscar', 'Situation Report')?> <?=$number?><br>
    </div>
    <div class="col-xs-12">
        <small><?=\Yii::t('oscar', 'Reporting period')?>&nbsp;&nbsp;&nbsp;<?=\Yii::t('oscar', '{from} to {until}', ['from' => $from, 'until' => $until])?></small>
    </div>
</div>