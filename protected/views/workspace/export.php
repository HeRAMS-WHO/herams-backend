<?php
declare(strict_types=1);

/**
 * @var \yii\web\View $this
 * @var \prime\models\forms\Export $model
 * @var \prime\models\ar\Workspace $subject
 */

use prime\widgets\menu\WorkspaceTabMenu;

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

\prime\widgets\Section::begin()
->withHeader('Export data')
;
echo $this->render('//shared/exportform', ['model' => $model]);

\prime\widgets\Section::end();
