<?php
declare(strict_types=1);

use prime\models\ar\Project;
use prime\models\forms\Export;
use prime\widgets\menu\ProjectTabMenu;
use prime\widgets\Section;
use yii\web\View;

/**
 * @var View $this
 * @var Export $model
 * @var Project $subject
 */

$this->params['breadcrumbs'][] = [
    'label' => $subject->title,
    'url' => ['project/workspaces', 'id' => $subject->id]
];

$this->title = \Yii::t('app', 'Export data from project {project}', ['project' => $subject->title]);

$this->beginBlock('tabs');
echo ProjectTabMenu::widget([
    'project' => $subject,
]);
$this->endBlock();

Section::begin()
    ->withHeader('Export data');

echo $this->render('//shared/exportform', ['model' => $model]);

Section::end();
