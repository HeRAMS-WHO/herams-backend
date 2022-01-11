<?php
declare(strict_types=1);

use app\components\Form;
use app\components\ActiveForm;
use prime\widgets\Section;
use yii\bootstrap\Html;
use prime\helpers\Icon;

/**
 * @var \yii\web\View $this
 * @var \prime\models\forms\workspace\Import $model
 * @var \prime\models\ar\Project $project
 */

$this->params['breadcrumbs'][] = [
    'label' => $project->title,
    'url' => ['project/workspaces', 'id' => $project->id]
];

$this->title = \Yii::t('app', 'Import workspaces');
Section::begin()->withHeader($this->title);

    $form = ActiveForm::begin([
        "type" => ActiveForm::TYPE_HORIZONTAL,
    ]);

    echo \app\components\Form::widget([
        'form' => $form,
        'model' => $model,
        'columns' => 1,
        "attributes" => [
            'titleField' => [
                'type' => Form::INPUT_DROPDOWN_LIST,
                'items' => $model->fieldOptions()
            ],
            'tokens' => [
                'type' => Form::INPUT_WIDGET,
                'widgetClass' => \prime\widgets\BetterSelect::class,
                'options' => [
                    'options' => [

                        'style' => [
                            'column-count' => 2,
                            'height' => 'auto',
                        ]
                    ],
                    'items' => $model->tokenOptions()
                ]
            ],
            \prime\widgets\FormButtonsWidget::embed([
                'buttons' => [
                    Html::submitButton(\Yii::t('app', 'Import workspaces'), ['class' => 'btn btn-primary']),
                ]
            ])

        ]
    ]);
    $form->end();
    Section::end();
