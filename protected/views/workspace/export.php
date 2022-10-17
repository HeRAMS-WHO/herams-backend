<?php

declare(strict_types=1);

use prime\interfaces\WorkspaceForTabMenu;
use prime\models\forms\Export;
use prime\widgets\menu\WorkspaceTabMenu;
use prime\widgets\Section;
use yii\web\View;

/**
 * @var View $this
 * @var Export $model
 * @var Workspace $subject
 * @var WorkspaceForTabMenu $tabMenuModel
 */

$this->title = $tabMenuModel->title();

$this->beginBlock('tabs');
echo WorkspaceTabMenu::widget([
    'workspace' => $tabMenuModel,
]);
$this->endBlock();

Section::begin()
    ->withHeader('Export data');

echo $this->render('//shared/exportform', [
    'model' => $model,
]);

Section::end();
