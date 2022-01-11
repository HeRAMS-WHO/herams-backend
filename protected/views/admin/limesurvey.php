<?php

declare(strict_types=1);

use prime\components\View;
use prime\widgets\menu\AdminTabMenu;

/**
 * @var View $this
 */

$this->title = \Yii::t('app', 'Backend administration');

$this->beginBlock('tabs');
echo AdminTabMenu::widget([

]);
$this->endBlock();

echo $this->render('@views/shared/limesurvey-iframe');
