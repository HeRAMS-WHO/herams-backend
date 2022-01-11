<?php
declare(strict_types=1);

use prime\models\ar\Workspace;
use prime\models\forms\Export;
use prime\widgets\menu\WorkspaceTabMenu;
use prime\widgets\Section;
use yii\web\View;

/**
 * @var View $this
 * @var Export $model
 * @var Workspace $subject
 */

$this->params['breadcrumbs'][] = [
    'label' => $subject->project->title,
    'url' => ['project/workspaces', 'id' => $subject->project->id]
];
$this->params['breadcrumbs'][] = [
    'label' => $subject->title,
    'url' => ['workspace/limesurvey', 'id' => $subject->id]
];

$this->beginBlock('tabs');
echo WorkspaceTabMenu::widget([
    'workspace' => $subject,
]);
$this->endBlock();

$this->title = \Yii::t('app', 'Export data from workspace {workspace}', ['workspace' => $subject->title]);

Section::begin()
    ->withHeader('Export data');

echo $this->render('//shared/exportform', ['model' => $model]);

Section::end();
