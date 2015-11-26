<?php

use app\components\Html;

?>
<div class="row" id="header">
    <div class="col-xs-11 text-medium">
        <?=\Yii::t('ccpm', 'Cluster Coordination Performance Monitoring')?>
    </div>
    <div class='col-xs-1'>
        <?=Html::tag('img', '', ['src' => 'data:image/jpg;base64,' . base64_encode(file_get_contents(\yii\helpers\Url::to('@app/reportGenerators/ccpm/assets/img/ccpm.jpg'))), 'height' => '70px', 'style' => ['float' => 'right']])?>
    </div>
</div>