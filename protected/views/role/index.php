<?php

declare(strict_types=1);

use prime\components\View;
use prime\widgets\menu\AdminTabMenu;
use prime\assets\ReactAsset;
use prime\widgets\Section;

ReactAsset::register($this);
/**
 * @var View $this
 */
$this->title = \Yii::t('app', 'Roles');

$this->beginBlock('tabs');
echo AdminTabMenu::widget([

]);
$this->endBlock();
Section::begin([]);
?>
<div id="RolesList"></div>

<?php
Section::end();
?>