<?php
declare(strict_types=1);

use app\components\ActiveForm;
use app\components\Form;
use prime\models\ar\Permission;
use prime\widgets\FormButtonsWidget;
use prime\widgets\menu\WorkspaceTabMenu;
use prime\widgets\Section;
use yii\helpers\Html;

/**
 * @var \prime\models\ar\Workspace $workspace
 * @var \prime\models\forms\Share $model
 * @var \prime\components\View $this
 */

$this->params['breadcrumbs'][] = [
    'label' => $workspace->project->title,
    'url' => ['project/workspaces', 'id' => $workspace->project->id]
];
$this->title = \Yii::t('app', 'Workspace {workspace}', [
    'workspace' => $workspace->title,
]);

$this->beginBlock('tabs');
echo WorkspaceTabMenu::widget([
    'workspace' => $workspace,
]);
$this->endBlock();

Section::begin(['header' => \Yii::t('app', 'Add new user')]);
$form = ActiveForm::begin([
    'method' => 'POST',
    "type" => ActiveForm::TYPE_HORIZONTAL,
    'formConfig' => [
        'labelSpan' => 3
    ]
]);

echo $model->renderForm($form);
$form->end();
Section::end();
Section::begin(['header' => \Yii::t('app', 'View user permissions')]);
echo $model->renderTable();
Section::end();
