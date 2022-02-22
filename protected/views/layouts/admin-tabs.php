<?php

declare(strict_types=1);

use prime\widgets\menu\TabMenu;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var string $content
 */

$this->beginContent('@views/layouts/admin-screen.php');

if (isset($this->blocks['tabs'])) {
    echo $this->blocks['tabs'];
} elseif (!empty($this->params['tabs'])) {
    echo TabMenu::widget(['tabs' => $this->params['tabs']]);
}
echo Html::tag('div', $content, ['class' => 'content']);


$this->endContent();
