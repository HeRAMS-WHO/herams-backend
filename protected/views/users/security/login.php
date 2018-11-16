<?php
    $this->title = \Yii::$app->name;
    $this->params['breadcrumbs'] = [];
?>
<div class="col-xs-12 col-md-6 col-md-offset-3">
    <?php
    /** @var \yii\web\View $this */

    use kartik\widgets\ActiveForm;
    use app\components\Form;
    use yii\bootstrap\Html;

    $form = ActiveForm::begin([
        'id' => 'login-form',
        'method' => 'POST',
        "type" => ActiveForm::TYPE_HORIZONTAL,
        'formConfig' => [
            'labelSpan' => -1,
            'showLabels' => ActiveForm::SCREEN_READER
        ]
    ]);
    echo Form::widget([
        'form' => $form,
        'model' => $model,
        'columns' => 1,
        "attributes" => [
            'login' => [
                'type' => Form::INPUT_TEXT
            ],
            'password' => [
                'type' => Form::INPUT_PASSWORD
            ],
//            'rememberMe' => [
//                'type' => Form::INPUT_CHECKBOX
//            ],
            'actions' => [
                'type' => Form::INPUT_RAW,
                'value' =>
                    Html::beginTag('div', ['class' => 'form-group']) .
                    Html::submitButton(\Yii::t('app', 'Login'), ['class' => 'btn btn-primary btn-block']) .
                    Html::a(
                        \Yii::t('app', 'Didn\'t receive confirmation message?'),
                        [
                            '/user/resend'
                        ],
                        [
                            'class' => 'btn btn-default btn-block',
                            'style' => 'margin-top: 10px'
                        ]
                    ) .
                    Html::a(
                        \Yii::t('app', 'Forgot your password?'),
                        [
                            '/user/recovery/request'
                        ],
                        [
                            'class' => 'btn btn-default btn-block',
                            'style' => 'margin-top: 10px'
                        ]
                    ) .
                    Html::a(
                        \Yii::t('app', 'Sign up'),
                        [
                            '/user/registration/register'
                        ],
                        [
                            'class' => 'btn btn-default btn-block',
                            'style' => 'margin-top: 10px'
                        ]
                    )

            ]
        ],
    ]);

    $form->end();
    ?>
</div>

