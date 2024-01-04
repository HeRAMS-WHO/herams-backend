<?php

declare(strict_types=1);

use prime\components\View;
use prime\widgets\menu\AdminTabMenu;
use prime\widgets\Section;

/**
 * @var View $this
 */
$this->title = \Yii::t('app', 'Dashboard');

$this->beginBlock('tabs');
echo AdminTabMenu::widget([

]);
$this->endBlock();

Section::begin([
    'actions' => [
        [
            'link' => ['survey/index'],
            'label' => \Yii::t('app', 'Surveys'),
            'style' => 'default',
        ],
        [
            'link' => ['project/index'],
            'label' => \Yii::t('app', 'Projects'),
            'style' => 'default',
        ],
        [
            'link' => ['admin/project'],
            'label' => \Yii::t('app', 'Projects React'),
            'style' => 'default',
        ],
        [
            'link' => ['admin/survey'],
            'label' => \Yii::t('app', 'Surveys React'),
            'style' => 'default',
        ],
    ],
]);

Section::end();
