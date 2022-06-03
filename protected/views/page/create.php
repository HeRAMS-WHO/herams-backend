<?php

declare(strict_types=1);

use app\components\Form;
use kartik\form\ActiveForm;
use kartik\helpers\Html;
use prime\components\View;
use prime\models\ar\Page;
use prime\models\ar\Project;
use prime\widgets\Section;
use yii\bootstrap\ButtonGroup;

use function iter\chain;
use function iter\toArrayWithKeys;

/**
 * @var Page $page
 * @var Project $project
 * @var View $this
 */

$this->title = \Yii::t('app', 'Create page');

Section::begin()
    ->withHeader($this->title);

$form = ActiveForm::begin([
    'id' => 'update-page',
    "type" => ActiveForm::TYPE_HORIZONTAL,
    'formConfig' => [
        'showLabels' => true,
        'defaultPlaceholder' => false,
        'labelSpan' => 3,
    ],
]);

echo Form::widget([
    'form' => $form,
    'model' => $page,
    'columns' => 1,
    "attributes" => [
        'title' => [
            'type' => Form::INPUT_WIDGET,
            'widgetClass' => \kartik\typeahead\Typeahead::class,
            'hint' => \Yii::t('app', 'If you use a predefined option it will automatically be translated'),
            'options' => [
                'useHandleBars' => false,
                'defaultSuggestions' => $page->titleOptions(),
                'dataset' => [
                    [
                        'local' => $page->titleOptions(),
                        'sufficient' => 0,
                    ],

                ],
            ],
        ],
        'parent_id' => [
            'attribute' => 'parent_id',
            'type' => Form::INPUT_DROPDOWN_LIST,
            'items' => toArrayWithKeys(
                chain([
                    '' => 'No parent',
                ], $page->parentOptions())
            ),
        ],
        'sort' => [
            'type' => Form::INPUT_TEXT,
        ],
        [
            'type' => Form::INPUT_RAW,
            'value' => ButtonGroup::widget(
                [
                    'buttons' => [
                        Html::submitButton(\Yii::t('app', 'Create page'), [
                            'class' => 'btn btn-primary',
                        ]),
                    ],
                ]
            ),
        ],
    ],
]);
ActiveForm::end();

Section::end();
