<?php

use app\components\Form;
use app\components\ActiveForm;
use prime\widgets\FormButtonsWidget;
use prime\widgets\Section;
use yii\bootstrap\Html;
use yii\helpers\Url;
use prime\models\ar\Permission;
use prime\helpers\Icon;
use prime\widgets\menu\WorkspaceTabMenu;

/**
 * @var  \prime\components\View $this
 * @var \prime\models\ar\Workspace $model
 */
assert($this instanceof \prime\components\View);
assert($model instanceof \prime\models\ar\Workspace);


$this->params['breadcrumbs'][] = [
    'label' => $model->project->title,
    'url' => ['project/workspaces', 'id' => $model->project->id]
];

$this->title = \Yii::t('app', "Workspace {workspace}", [
    'workspace' => $model->title,
]);

$this->beginBlock('tabs');
echo WorkspaceTabMenu::widget([
    'workspace' => $model,
]);
$this->endBlock();

Section::begin([
    'header' => $this->title,
    'subject' => $model,
    'actions' => [
        [
            'icon' => Icon::trash(),
            'label' => \Yii::t('app', 'Delete'),
            'link' => ['workspace/delete', 'id' => $model->id],
            'permission' => Permission::PERMISSION_DELETE,
            'linkOptions' => [
                'data-method' => 'delete',
                'title' => \Yii::t('app', 'Delete'),
                'data-confirm' => \Yii::t('app', 'Are you sure you wish to remove this workspace from the system?'),
                'class' => 'btn btn-delete btn-icon'
            ]
        ]
    ]
]);

    $form = ActiveForm::begin([
        'method' => 'PUT',
        "type" => ActiveForm::TYPE_HORIZONTAL,
        'formConfig' => [
            'labelSpan' => 3
        ]
    ]);

    echo Form::widget([
        'form' => $form,
        'model' => $model,
        'columns' => 1,
        "attributes" => [
            'token' => [
                'type' => Form::INPUT_STATIC
            ],
            'title' => [
                'type' => Form::INPUT_TEXT,
            ],
            FormButtonsWidget::embed([
                'buttons' => [
                    Html::a(\Yii::t('app', 'Edit token'), ['workspace/configure', 'id' => $model->id], [
                    'class' => ['btn btn-default']
                    ]),
                    Html::submitButton(\Yii::t('app', 'Save'), ['class' => 'btn btn-primary']),
                ]
            ])
        ]
    ]);
    ActiveForm::end();
    Section::end();
