<?php

declare(strict_types=1);

use prime\components\View;
use prime\models\ar\Permission;
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
    ],
]);

Section::end();
