<?php

declare(strict_types=1);

use prime\widgets\menu\TabMenu;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var string $content
 */

$this->registerCSSFile('https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css');
$this->registerJsFile("//code.iconify.design/1/1.0.6/iconify.min.js");
$this->registerJsFile('https://cdn.jsdelivr.net/npm/flatpickr');
$this->beginContent('@views/layouts/admin-screen.php');
//
if (isset($this->blocks['tabs'])) {
    echo $this->blocks['tabs'];
} elseif (! empty($this->params['tabs'])) {
    echo TabMenu::widget([
        'tabs' => $this->params['tabs'],
    ]);
}
echo Html::beginTag('div', [
    'class' => 'content',
]);
/*echo Html::tag('h1', $this->title, [
    //    'class' => 'page-title',
]);*/

echo $content;


$this->endContent();
