<?php
declare(strict_types=1);

use prime\interfaces\WorkspaceForTabMenu;
use prime\models\ar\Workspace;
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

$this->params['breadcrumbs'][] = [

    'label' => $tabMenuModel->projectTitle(),
    'url' => ['project/workspaces', 'id' => $tabMenuModel->projectId()]
];
$this->params['breadcrumbs'][] = [
    'label' => $tabMenuModel->title(),
    'url' => ['workspace/limesurvey', 'id' => $tabMenuModel->id()]
];

$this->beginBlock('tabs');
echo WorkspaceTabMenu::widget([
    'workspace' => $tabMenuModel,
]);
$this->endBlock();

$this->title = \Yii::t('app', 'Export data from workspace {workspace}', ['workspace' => $tabMenuModel->title()]);

Section::begin()
    ->withHeader('Export data');

echo $this->render('//shared/exportform', ['model' => $model]);

Section::end();
