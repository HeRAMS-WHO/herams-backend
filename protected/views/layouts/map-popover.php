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
            echo Html::tag('p', \Yii::t('app', "The Health Resources and Services Availability Monitoring System (HeRAMS) is a collaborative approach aimed at ensuring that core information on essential health resources and services is systematically shared and readily available to decision makers at country, regional and global levels"));
        ?>
    </div>
    <div class="form">
        <?=$content; ?>
    </div>
    <?=$this->render('stats'); ?>

</div>
<?php

$this->endContent();