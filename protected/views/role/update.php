<?php

declare(strict_types=1);

use prime\assets\ReactAsset;
use prime\components\View;
use prime\widgets\menu\AdminTabMenu;
use prime\widgets\Section;

ReactAsset::register($this);
/**
 * @var View $this
 */
$this->title = \Yii::t('app', 'Role permission');

$this->beginBlock('tabs');
echo AdminTabMenu::widget([

]);
$this->endBlock();
Section::begin([])
?>
<div id="RoleEdit" data-role-id="<?= $id; ?>"></div>

<?php
Section::end();
?>