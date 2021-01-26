<?php
declare(strict_types=1);

use kartik\builder\Form;
use kartik\form\ActiveForm;
use prime\widgets\FormButtonsWidget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * @var \prime\components\View $this
 * @var \prime\models\ar\User $model
 */
    echo Form::widget([
        'model' => $model,
        'form' => $form = ActiveForm::begin([
            'enableClientValidation' => false,
            'type' => ActiveForm::TYPE_VERTICAL,
            'formConfig' => [
                'labelSpan' => 3,

            ],
            'fieldConfig' => [
            ]
        ]),
        'columns' => 1,
        'attributes' => [
            [
                'type' => Form::INPUT_RAW,
                'value' => $form->errorSummary($model)
            ],
            'name' => [
                'type' => Form::INPUT_TEXT
            ],
            'language' => [
                'type' => Form::INPUT_DROPDOWN_LIST,
                'items' => array_merge([
                    '' => \Yii::t('app', 'Autodetected ({language}', [
                        'language' => \Yii::$app->request->getPreferredLanguage(\Yii::$app->params['languages'])
                    ])
                ], ArrayHelper::map(
                    \Yii::$app->params['languages'],
                    static function (string $language): string {
                        return $language;
                    },
                    static function (string $language): string {
                        return locale_get_display_name($language);
                    }
                ))
            ],
            FormButtonsWidget::embed([
                'buttons' => [
                    Html::submitButton(Yii::t('app', 'Update account information'), ['class' => ['btn', 'btn-primary']])
                ]
            ])
        ]
    ]);
        ActiveForm::end();
