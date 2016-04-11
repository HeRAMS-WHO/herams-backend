<?php

use app\components\Html;

/**
 * @var \yii\web\View $this
 */

?>

<div class="col-xs-12" style="background-color: #a1a1a1; margin-bottom: 20px;">
    <div class="row">
        <div class="col-md-6">
            <?=Html::img('/img/who-logo-white.png', ['style' => ['height' => '60px', 'margin' => '15px']])?>
        </div>
        <div class="col-md-6 text-right" style="padding: 20px; font-size: 1.2em;">
            <?=\Yii::t('app', 'GRADED AND PROTRACTED EMERGENCIES')?>
        </div>
    </div>
</div>