<?php

use app\components\ActiveForm;
use app\components\Form;
use prime\models\ar\Permission;
use prime\widgets\FormButtonsWidget;
use prime\widgets\menu\WorkspaceTabMenu;
use yii\helpers\Html;

/**
 * @var \prime\models\ar\Workspace $workspace
 * @var \prime\models\forms\Share $model
 */

$this->params['breadcrumbs'][] = [
    'label' => $workspace->project->title,
    'url' => ['project/workspaces', 'id' => $workspace->project->id]
];
$this->params['breadcrumbs'][] = [
    'label' => \Yii::t('app', 'Workspace {workspace}', [
        'workspace' => $workspace->title,
    ]),
    'url' => ['workspace/limesurvey', 'id' => $workspace->id]
];
$this->title = \Yii::t('app', 'Workspace {workspace}', [
    'workspace' => $workspace->title,
]);

echo WorkspaceTabMenu::widget([
    'workspace' => $workspace,
    'currentPage' => $this->context->action->uniqueId
]);

echo Html::beginTag('div', ['class' => "content"]);
?>

<div class="form-content form-bg">
    <?php
    echo Html::tag('h4', \Yii::t('app', 'Add new user'));
    $form = ActiveForm::begin([
        'method' => 'POST',
        "type" => ActiveForm::TYPE_HORIZONTAL,
        'formConfig' => [
            'showLabels' => true,
            'defaultPlaceholder' => false,
            'labelSpan' => 3
        ]
    ]);

    echo $model->renderForm($form);
    echo Form::widget([
        'form' => $form,
        'model' => $model,
        'attributes' => [
            FormButtonsWidget::embed([
                'options' => [
                    'class' => [
                        'pull-right'
                    ],
                ],
                'buttons' => [
                    ['label' => \Yii::t('app', 'Add'), 'options' => ['class' => ['btn', 'btn-primary']]]
                ]
            ])
        ]
    ]);
    $form->end();
    ?>
</div>
<div class="form-content form-bg full-width">
    <h4><?= \Yii::t('app', 'View user permissions') ?></h4>
    <?php
    echo $model->renderTable();
    ?>
</div>

<?php
echo Html::endTag('div');
?>