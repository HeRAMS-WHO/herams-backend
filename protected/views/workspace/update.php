<?php
declare(strict_types=1);

use app\components\ActiveForm;
use app\components\Form;
use prime\helpers\Icon;
use prime\models\ar\Permission;
use prime\widgets\FormButtonsWidget;
use prime\widgets\menu\WorkspaceTabMenu;
use prime\widgets\Section;
use yii\bootstrap\Html;

/**
 * @var  \prime\components\View $this
 * @var \prime\models\ar\Workspace $model
 * @var \prime\interfaces\WorkspaceForTabMenu $tabMenuModel
 */
assert($this instanceof \prime\components\View);
assert($model instanceof \prime\models\ar\Workspace);

$this->title = $model->title;

$this->beginBlock('tabs');
echo WorkspaceTabMenu::widget([
    'workspace' => $tabMenuModel,
]);
$this->endBlock();

Section::begin([
    'actions' => [
    ]
])
    ->withSubject($model)
    ->withHeader($this->title);

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
