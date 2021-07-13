<?php
declare(strict_types=1);

use kartik\builder\Form;
use kartik\form\ActiveForm;
use prime\models\forms\user\UpdateEmailForm;
use prime\widgets\FormButtonsWidget;
use prime\widgets\menu\UserTabMenu;
use prime\widgets\Section;
use yii\web\View;

/**
 * @var View $this
 * @var UpdateEmailForm $model
 */

$this->beginBlock('tabs');
echo UserTabMenu::widget([
    'user' => $model->getUser(),
]);
$this->endBlock();

$this->title = Yii::t('app', 'Email');

Section::begin()
    ->withHeader('Email');

$form = ActiveForm::begin([

]);

echo Form::widget([
    'model' => $model,
    'form' => $form,
    'attributes' => [
        'newEmail' => [
            'type' => Form::INPUT_TEXT,
        ],
        FormButtonsWidget::embed([
            'buttons' => [
                ['label' => Yii::t('app', 'Send confirmation'), 'style' => 'primary'],
            ],
        ]),
    ],
]);

ActiveForm::end();

Section::end();
