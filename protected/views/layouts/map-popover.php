<?php

use prime\helpers\Icon;
use prime\models\ar\Project;
use prime\models\ar\User;
use yii\helpers\Html;

$this->beginContent('@views/layouts/map.php');


?>
<div class="popover">
    <a href="/" style="position: absolute; right: 10px; top: 10px;"><?= Icon::close(); ?></a>
    <div class="intro">
        <?php
            echo Html::img('@web/img/HeRAMS.png');
            echo Html::tag('p', \Yii::t('app', "The Health Resources and Services Availability Monitoring System is a collaborative process for the monitoring of essential health resources and services in support to the identification of needs, gaps and priorities"));
        ?>
    </div>
    <div class="form">
        <?=$content; ?>
    </div>
    <?=$this->render('stats'); ?>

</div>
<?php

$this->endContent();