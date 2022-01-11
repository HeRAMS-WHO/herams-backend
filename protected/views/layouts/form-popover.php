<?php
declare(strict_types=1);
use prime\helpers\Icon;
use prime\models\ar\Project;
use prime\models\ar\User;
use yii\helpers\Html;

/**
 * @var string $content
 * @var \prime\components\View $this
 */
$this->beginContent('@views/layouts/map.php');


?>
<div class="popover" style="display: block;">
    <a href="/" style="position: absolute; right: 10px; top: 10px;"><?= Icon::close(); ?></a>
    <?=$content; ?>
    <?= $this->render('stats'); ?>
</div>
<?php

$this->endContent();