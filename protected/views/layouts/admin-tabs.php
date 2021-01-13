<?php

declare(strict_types=1);

/**
 * @var \prime\components\View $this
 */

use prime\widgets\menu\TabMenu;
use yii\helpers\Html;

$this->beginContent('@views/layouts/admin-screen.php');

if (isset($this->blocks['tabs'])) {
    echo $this->blocks['tabs'];
} elseif (!empty($this->params['tabs'])) {
    echo TabMenu::widget(['tabs' => $this->params['tabs']]);
}
echo Html::tag('div', $content, ['class' => 'content']);


$this->endContent();
