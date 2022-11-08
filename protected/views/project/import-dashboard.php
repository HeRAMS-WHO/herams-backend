<?php

declare(strict_types=1);

use app\components\Form;
use herams\common\models\Project;
use prime\components\ActiveForm;
use prime\components\View;
use prime\widgets\InlineUpload\InlineUpload;
use prime\widgets\menu\ProjectTabMenu;
use prime\widgets\Section;
use yii\bootstrap\Html;

/**
 * @var Project $model
 * @var Project $project
 * @var View $this
 */

$this->title = $project->title;

$this->beginBlock('tabs');
echo ProjectTabMenu::widget([
    'project' => $project,
]);
$this->endBlock();

Section::begin()
    ->withHeader(\Yii::t('app', 'Import Pages'));

$form = ActiveForm::begin([
    "type" => ActiveForm::TYPE_HORIZONTAL,
    'formConfig' => [
        'showLabels' => true,
        'defaultPlaceholder' => false,
        'labelSpan' => 3,
    ],
]);

echo Form::widget([
    'form' => $form,
    'model' => $model,
    'columns' => 1,
    "attributes" => [
        'pages' => [
            'type' => Form::INPUT_WIDGET,
            'widgetClass' => InlineUpload::class,
        ],
        \prime\widgets\FormButtonsWidget::embed(
            [
                'buttons' => [
                    Html::submitButton(\Yii::t('app', 'Import pages'), [
                        'class' => 'btn btn-primary',
                    ]),
                ],
            ]
        ),

    ],
]);
$form->end();

Section::end();
