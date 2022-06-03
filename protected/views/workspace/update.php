<?php

declare(strict_types=1);

use app\components\Form;
use prime\components\ActiveForm;
use prime\components\View;
use prime\helpers\Icon;
use prime\interfaces\WorkspaceForTabMenu;
use prime\models\ar\Permission;
use prime\models\ar\WorkspaceForLimesurvey;
use prime\models\forms\workspace\Update;
use prime\models\forms\workspace\UpdateForLimesurvey;
use prime\widgets\ButtonGroup;
use prime\widgets\FormButtonsWidget;
use prime\widgets\LocalizableInput;
use prime\widgets\menu\WorkspaceTabMenu;
use prime\widgets\Section;
use yii\bootstrap\Html;

/**
 * @var Update|UpdateForLimesurvey $model
 * @var WorkspaceForTabMenu $tabMenuModel
 * @var View $this
 */
assert($this instanceof View);
assert($model instanceof Update || $model instanceof UpdateForLimesurvey);

$this->title = $model->title;

$this->beginBlock('tabs');
echo WorkspaceTabMenu::widget([
    'workspace' => $tabMenuModel,
]);
$this->endBlock();

Section::begin()
    ->withSubject($model)
    ->withHeader($this->title);

$form = ActiveForm::begin([
    'method' => 'PUT',
]);

$attributes = [];

if ($model instanceof UpdateForLimesurvey) {
    $attributes['token'] = [
        'type' => Form::INPUT_STATIC,
    ];
}

$attributes += [
    'title' => [
        'type' => Form::INPUT_TEXT,
    ],
    'i18nTitle' => [
        'type' => Form::INPUT_WIDGET,
        'widgetClass' => LocalizableInput::class,
    ],
    FormButtonsWidget::embed(
        [
            'buttons' => [
                Html::submitButton(\Yii::t('app', 'Save'), [
                    'class' => 'btn btn-primary',
                ]),
            ],
        ]
    ),
];

echo Form::widget([
    'form' => $form,
    'model' => $model,
    'attributes' => $attributes,
]);
ActiveForm::end();
Section::end();

Section::begin()
    ->withHeader(\Yii::t('app', 'Delete workspace'))
    ->forDangerousAction();

echo Html::tag('p', \Yii::t('app', 'This will permanently delete the workspace.'));
echo Html::tag('p', \Yii::t('app', 'This action cannot be undone.'));
echo Html::tag('p', Html::tag('em', \Yii::t('app', 'Are you ABSOLUTELY SURE you wish to delete this workspace?')));

echo ButtonGroup::widget([
    'buttons' => [
        [
            'visible' => \Yii::$app->user->can(Permission::PERMISSION_DELETE, $model),
            'icon' => Icon::trash(),
            'label' => \Yii::t('app', 'Delete'),
            'link' => [
                'workspace/delete',
                'id' => $model->id,
            ],
            'style' => 'delete',
            'linkOptions' => [
                'data-method' => 'delete',
                'title' => \Yii::t('app', 'Delete workspace'),
                'data-confirm' => \Yii::t('app', 'Are you sure you wish to remove this workspace from the system?'),
            ],
        ],
    ],
]);

Section::end();
