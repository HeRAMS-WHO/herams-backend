<?php

declare(strict_types=1);

use yii\helpers\Html;

/**
 * @var \prime\components\View $this
 * @var string $content
 */
$this->beginContent('@views/layouts/map.php');


?>
<div class="popover session">
    <div class="intro">
        <div class='logo white'>
            <img src="../img/HeRAMS_white.svg"></img>
        </div>
        <?php echo Html::tag('header', \Yii::t('app', 'The Health Resources and Services Availability Monitoring System (HeRAMS) is a collaborative approach aimed at ensuring that core information on essential health resources and services is systematically shared and readily available to decision makers at country, regional and global levels')); ?>
        <?= $this->render('stats'); ?>
    </div>
    <div class="form">
        <?= $content; ?>
    </div>
</div>
<?php

$this->endContent();
