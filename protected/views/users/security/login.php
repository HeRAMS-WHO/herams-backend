<div class="row">
    <div class="col-xs-12">
        <?php
        /** @var \yii\web\View $this */
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
                'labelSpan' => 0,
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
                'rememberMe' => [
                    'type' => Form::INPUT_CHECKBOX
                ],
                'actions' => [
                    'type' => Form::INPUT_RAW,
                    'value' =>
                        Html::beginTag('div', ['class' => 'form-group']) .
                        Html::submitButton(\Yii::t('app', 'Login'), ['class' => 'btn btn-primary btn-block']) .
                        Html::a(
                            \Yii::t('app', 'Can\'t access your account?'),
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
                        ) .
                        Html::endTag('div') .
                        \dektrium\user\widgets\Connect::widget([
                            'options' => [
                                'class' => 'form-group '
                            ],
                            'baseAuthUrl' => ['/user/security/auth'],

                        ])

                ]
            ],
        ]);

        $this->registerAssetBundle(\prime\assets\SocialAsset::class);
        $form->end();
        ?>
    </div>
</div>