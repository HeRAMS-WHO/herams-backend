<?php
declare(strict_types=1);
use app\components\ActiveForm;
use app\components\Form;
use prime\models\ar\Permission;
use prime\widgets\FormButtonsWidget;
use prime\widgets\menu\ProjectTabMenu;
use prime\widgets\Section;
use yii\helpers\Html;

/**
 * @var \prime\models\ar\Project $project
 * @var \prime\components\View $this
 * @var \prime\models\forms\Share $model
 */

$this->params['breadcrumbs'][] = [
    'label' => $project->title,
    'url' => ['project/workspaces', 'id' => $project->id]
];
$this->title = $project->title;

$this->params['subtitle'] = \Yii::t('app', 'Add new user');
$this->beginBlock('tabs');
echo ProjectTabMenu::widget([
    'project' => $project,
]);
$this->endBlock();

Section::begin(['header' => \Yii::t('app', 'Add new user')]);
    $form = ActiveForm::begin([
        "type" => ActiveForm::TYPE_HORIZONTAL,
        'formConfig' => [
            'labelSpan' => 3

        ]
    ]);
    echo $model->renderForm($form);
    $form->end();
    Section::end();
    Section::begin(['header' => \Yii::t('app', 'View current users')]);
    echo $model->renderTable();
    Section::end();
