<?php

declare(strict_types=1);

use herams\common\domain\user\User;
use prime\assets\ReactAsset;
use prime\widgets\menu\UserTabMenu;
use prime\widgets\Section;
use yii\helpers\Html;
use yii\web\View;

ReactAsset::register($this);

/**
 * @var View $this
 * @var User $model
 */

$this->beginBlock('tabs');
echo UserTabMenu::widget([
    'user' => $model,
]);
$this->endBlock();

$this->title = Yii::t('app', 'Profile');

$model->setOnlyFields(['id', 'email', 'name', 'language']);

$userEncoded = $model->toBase64();

Section::begin()
    ->withHeader($this->title);
?>
<!-- Mount point for the React component -->
    <div id="Profile" data-user="<?= Html::encode($userEncoded) ?>">
    </div>


<?php
//$form = ActiveForm::begin([
//
//]);
//
//echo Form::widget([
//    'model' => $model,
//    'form' => $form,
//    'attributes' => [
//        [
//            'type' => Form::INPUT_RAW,
//            'value' => $form->errorSummary($model),
//        ],
//        'name' => [
//            'type' => Form::INPUT_TEXT,
//        ],
//        'language' => [
//            'type' => Form::INPUT_DROPDOWN_LIST,
//            'items' => [
//                '' => \Yii::t('app', 'Autodetected ({language}', [
//                    'language' => \Yii::$app->languageSelector->getPreferredLanguage(\Yii::$app->request),
//                ]),
//                ...Language::toLocalizedArray(),
//            ],
//        ],
//        'newsletter_subscription' => [
//            'type' => Form::INPUT_CHECKBOX,
//        ],
//        FormButtonsWidget::embed([
//            'buttons' => [
//                Html::submitButton(Yii::t('app', 'Update profile'), [
//                    'class' => ['btn', 'btn-primary'],
//                ]),
//            ],
//        ]),
//    ],
//]);
//ActiveForm::end();

Section::end();
