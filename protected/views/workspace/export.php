<?php
declare(strict_types=1);

/**
 * @var \yii\web\View $this
 * @var \prime\models\forms\Export $model
 * @var \prime\models\ar\Workspace $subject
 */

$this->params['breadcrumbs'][] = [
    'label' => $subject->project->title,
    'url' => ['project/workspaces', 'id' => $subject->project->id]
];
$this->params['breadcrumbs'][] = [
    'label' => $subject->title,
    'url' => ['workspace/limesurvey', 'id' => $subject->id]
];

$this->title = \Yii::t('app', 'Export data from workspace {workspace}', ['workspace' => $subject->title]);

echo $this->render('//shared/exportform', ['model' => $model]);
