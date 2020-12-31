<?php
declare(strict_types=1);

use app\components\ActiveForm;
use app\components\Form;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * @var \prime\components\View $this
 * @var \prime\models\ar\Facility $facility
 */
$this->params['breadcrumbs'][] = [
    'label' => \Yii::t('app', 'Admin dashboard'),
    'url' => ['/admin']
];
$this->title = Yii::t('app', 'Create facility');

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
    'model' => $facility,
    "attributes" => [
        'name' => [
            'type' => Form::INPUT_TEXT,
        ],
        'alternative_name' => [
            'type' => Form::INPUT_TEXT,
        ],
        \prime\widgets\FormButtonsWidget::embed([
            'buttons' => [Html::submitButton()]
        ])
    ],
]);

$languageMap = ArrayHelper::map(
    \Yii::$app->params['languages'],
    static function (string $language): string {
        return $language;
    },
    static function (string $language): string {
        return locale_get_display_name($language);
    }
);
$this->registerJsFile('/js/components/validation-message.js', ['type' => 'module']);
$this->registerJsFile('/js/components/localizable-text.js', ['type' => 'module']);
echo Html::beginTag('localizable-input', [
    'id' => 'tester',
    'name' => 'tester',
    'value' => json_encode(['en-US' => 'englishtxt'], JSON_THROW_ON_ERROR),
    'class' => 'row',
    'languages' => $languageMap
]);
echo Html::endTag('localizable-input');

$this->registerCss(':invalid:not(:focus-within) { border: 5px solid yellow; }');
//echo Html::tag('input', '', ['id' => 'testinput',  'pattern' => '\\d+']);
echo Html::tag('validation-message', '', ['for' => 'tester',]);
$form->end();


$this->registerJsFile('/js/components/tabbed-content.js', ['type' => 'module']);

?>
<style>
    /*tabbed-content {*/
    /*    display: inherit;*/
    /*    background-color: #ff0000;*/
    /*}*/

    /*tabbed-content::part(content) {*/
    /*    min-height: 100px;*/
    /*    padding-right: 50px;*/
    /*    background-color: purple;*/
    /*}*/
</style>


<tabbed-content>

<span slot="header" style="background-color: blue;"><a>Label 1</a></span>
<div slot="content">Cool stuff</div>

    <a slot="header">Label 2</a>
<div slot="content">Cool stuff2</div>


    <a slot="header">Label 3</a>
<div slot="content">Cool stuff 3</div>


    <a slot="header">Label 4</a>
<div slot="content">Cool stuff4 </div>
</tabbed-content>
