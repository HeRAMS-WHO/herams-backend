<?php
declare(strict_types=1);

use app\components\ActiveForm;
use app\components\Form;
use prime\models\ar\User;
use prime\widgets\FormButtonsWidget;
use prime\widgets\menu\UserTabMenu;
use prime\widgets\Section;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;

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

Section::begin()
    ->withHeader($this->title);

$form = ActiveForm::begin([

]);

echo Form::widget([
    'model' => $model,
    'form' => $form,
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
                Html::submitButton(Yii::t('app', 'Update profile'), ['class' => ['btn', 'btn-primary']])
            ]
        ])
    ]
]);
ActiveForm::end();

Section::end();
