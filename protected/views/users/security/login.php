<div class="row">
    <div class="col-xs-12">
        <?php

        /**
         * @var \app\models\User $model
         */

        use app\components\Form;
        use app\components\Html;
        use app\components\ActiveForm;

        $form = ActiveForm::begin([
            'id' => 'login-form',
            'method' => 'POST',
            "type" => ActiveForm::TYPE_HORIZONTAL,
            'formConfig' => [
                'showLabels' => false
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
                'rememberMe' => [
                    'type' => Form::INPUT_CHECKBOX
                ],
                'actions' => [
                    'type' => Form::INPUT_RAW,
                    'value' =>
                        Html::submitButton(\Yii::t('app', 'Login'), ['class' => 'btn btn-primary col-xs-12']) .
                        ' ' .
                        Html::a(
                            \Yii::t('app', 'Can\'t access your account?'),
                            [
                                '/user/recovery/request'
                            ],
                            [
                                'class' => 'btn btn-default col-xs-12',
                                'style' => 'margin-top: 10px'
                            ]
                        ) .
                        ' ' .
                        Html::a(
                            \Yii::t('app', 'Sign up'),
                            [
                                '/user/registration/register'
                            ],
                            [
                                'class' => 'btn btn-default col-xs-12',
                                'style' => 'margin-top: 10px'
                            ]
                        ) .
                        \dektrium\user\widgets\Connect::widget([
                            'baseAuthUrl' => ['/user/security/auth'],
                        ])
                ]
            ],
            'options' => [

            ]
        ]);

        $form->end();
        ?>
    </div>
</div>