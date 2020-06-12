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
        <div class='logo white'>
           <object type="image/svg+xml" data="../img/HeRAMS_white.svg"></object>
        </div>
        <?php echo Html::tag('header', \Yii::t('app', 'The Health Resources and Services Availability Monitoring System (HeRAMS) is a collaborative approach aimed at ensuring that core information on essential health resources and services is systematically shared and readily available to decision makers at country, regional and global levels')); ?>
        <?= $this->render('stats'); ?>
    </div>
    <div class="form">
        <?=$content; ?>
    </div>
</div>
<?php

$this->endContent();