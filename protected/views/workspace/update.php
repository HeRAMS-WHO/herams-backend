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
    'actions' => [
        [
            'icon' => Icon::recycling(),
            'label' => \Yii::t('app', 'Refresh workspace'),
            'link' => ['workspace/refresh', 'id' => $model->id],
            'permission' => Permission::PERMISSION_SURVEY_DATA
        ],
    ]
])->withHeader($this->title);

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
                    Html::submitButton(\Yii::t('app', 'Save'), ['class' => 'btn btn-primary']),
                ]
            ])
        ]
    ]);
    ActiveForm::end();
    Section::end();
    Section::begin()
    ->withHeader(\Yii::t('app', 'Delete workspace'))
    ->forDangerousAction()
    ;


    echo Html::tag('p', \Yii::t('app', 'This will permanently delete the workspace.'));
    echo Html::tag('p', \Yii::t('app', 'This action cannot be undone.'));
    echo Html::tag('p', Html::tag('em', \Yii::t('app', 'Are you ABSOLUTELY SURE you wish to delete this workspace?')));

    echo \prime\widgets\ButtonGroup::widget([
    'buttons' => [
        [
            'visible' => \Yii::$app->user->can(Permission::PERMISSION_DELETE, $model),
            'icon' => Icon::trash(),
            'label' => \Yii::t('app', 'Delete'),
            'link' => ['workspace/delete', 'id' => $model->id],
            'style' => 'delete',
            'linkOptions' => [
                'data-method' => 'delete',
                'title' => \Yii::t('app', 'Delete workspace'),
                'data-confirm' => \Yii::t('app', 'Are you sure you wish to remove this workspace from the system?'),
            ]
        ]
    ]
    ]);

    Section::end();
