<?php

declare(strict_types=1);

use herams\common\models\Project;
use prime\models\forms\Export;
use prime\widgets\menu\ProjectTabMenu;
use prime\widgets\Section;
use yii\web\View;

/**
 * @var View $this
 * @var Export $model
 * @var Project $subject
 */

$this->title = $subject->title;

$this->beginBlock('tabs');
echo ProjectTabMenu::widget([
    'project' => $subject,
]);
$this->endBlock();

Section::begin()
    ->withHeader('Export data');

echo $this->render('//shared/exportform', [
    'model' => $model,
]);

Section::end();
