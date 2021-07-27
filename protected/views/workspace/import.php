<?php
declare(strict_types=1);

use app\components\ActiveForm;
use app\components\Form;
use prime\models\ar\Project;
use prime\models\forms\workspace\Import;
use prime\widgets\BetterSelect;
use prime\widgets\Section;
use yii\bootstrap\Html;
use yii\web\View;

/**
 * @var View $this
 * @var Import $model
 * @var Project $project
 */

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
                'widgetClass' => BetterSelect::class,
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
