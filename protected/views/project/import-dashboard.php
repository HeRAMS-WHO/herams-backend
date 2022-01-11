<?php
declare(strict_types=1);
/**
 * @var \prime\models\ar\Project $model
 * @var \prime\models\ar\Project $project
 * @var \prime\components\View $this
 */

use app\components\Form;
use app\components\ActiveForm;
use prime\widgets\InlineUpload\InlineUpload;
use yii\bootstrap\ButtonGroup;
use yii\bootstrap\Html;

$this->params['breadcrumbs'][] = [
    'label' => $project->title,
    'url' => ['project/update', 'id' => $project->id]
];

$this->params['breadcrumbs'][] = [
    'label' => \Yii::t('app', 'Dashboard settings'),
    'url' => ['project/pages', 'id' => $project->id]
];


$this->title = \Yii::t('app', 'Import pages');

\prime\widgets\Section::begin(['header' => \Yii::t('app', 'Import Pages')]);
    $form = ActiveForm::begin([
        "type" => ActiveForm::TYPE_HORIZONTAL,
        'formConfig' => [
            'showLabels' => true,
            'defaultPlaceholder' => false,
            'labelSpan' => 3
        ]
    ]);

    echo Form::widget([
        'form' => $form,
        'model' => $model,
        'columns' => 1,
        "attributes" => [
            'pages' => [
                'type' => Form::INPUT_WIDGET,
                'widgetClass' => InlineUpload::class
            ],
            \prime\widgets\FormButtonsWidget::embed([
                    'buttons' => [
                        Html::submitButton(\Yii::t('app', 'Import pages'), ['class' => 'btn btn-primary'])
                    ]
            ])
        ]
    ]);
    $form->end();

    \prime\widgets\Section::end();
