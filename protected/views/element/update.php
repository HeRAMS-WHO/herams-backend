<?php

declare(strict_types=1);

use prime\components\ActiveForm;
use app\components\Form;
use kartik\select2\Select2;
use prime\components\View;
use prime\models\ar\Element as ARElement;
use prime\models\ar\Page;
use prime\models\ar\Project;
use prime\models\forms\Element as FormElement;
use prime\widgets\ButtonGroup;
use prime\widgets\FormButtonsWidget;
use prime\widgets\Section;
use yii\bootstrap\Html;
use yii\helpers\Json;
use yii\helpers\Url;

/**
 * @var ARElement|FormElement $model
 * @var Project $project
 * @var View $this
 * @var Page $page
 * @var string $url
 */

$this->title = $model->isNewRecord
    ? \Yii::t('app', 'Create element')
    : \Yii::t('app', 'Update element');

Section::begin()
    ->withHeader($this->title);

$form = ActiveForm::begin();

echo Form::widget([
    'form' => $form,
    'model' => $model,
    "attributes" => [
        'transpose' => [
            'type' => Form::INPUT_RADIO_BUTTON_GROUP,
            'items' => [
                1 => \Yii::t('app', 'Yes'),
                0 => \Yii::t('app', 'No')
            ],
            'options' => [
                'class' => 'btn-radio',
            ],
        ],
        'code' => [
            'type' => Form::INPUT_WIDGET,
            'widgetClass' => Select2::class,
            'options' => [
                'data' => $model->codeOptions(),
            ],
        ],
        'sort' => [
            'type' => Form::INPUT_HTML5,
            'html5type' => 'number'
        ],
        'width' => [
            'type' => Form::INPUT_HTML5,
            'html5type' => 'number',
        ],
        'height' => [
            'type' => Form::INPUT_HTML5,
            'html5type' => 'number'
        ],
        'reasonCode' => [
            'type' => Form::INPUT_WIDGET,
            'widgetClass' => Select2::class,
            'options' => [
                'data' => $model->codeOptions(),
            ],
            'visible' => $model->isAttributeSafe('reasonCode')
        ],
        'groupCode' => [
            'type' => Form::INPUT_WIDGET,
            'widgetClass' => Select2::class,
            'options' => [
                'data' => $model->codeOptions(),
            ],
            'visible' => $model->isAttributeSafe('groupCode')
        ],
        'title' => [
            'type' => Form::INPUT_TEXT,
            'options' => [
                'placeholder' => $model->getTitlePlaceHolder(),
            ],
            'visible' => $model->isAttributeSafe('title')
        ],
        'markerRadius' => [
            'type' => Form::INPUT_HTML5,
            'html5type' => 'number',
            'options' => [
                'placeholder' => \prime\widgets\map\DashboardMap::DEFAULT_MARKER_RADIUS
            ],
            'visible' => $model->isAttributeSafe('markerRadius')
        ],
        'chartType' => [
            'type' => Form::INPUT_DROPDOWN_LIST,
            'items' => $model->isAttributeSafe('chartType') ? $model->chartTypeOptions() : [],
            'visible' => $model->isAttributeSafe('chartType')
        ],
    ]
]);

$url = Json::encode($url);
$this->registerJs(
<<<JS
$('#element-code, #element-transpose').on('change', function(e) {
    // Refresh page on change.
    window.location.href = ${url}.replace("__value__", e.target.value).replace("__key__", e.target.name);
});

$('form').on('change', function() {
    $('#preview-frame').attr('src', '/element/{$model->id}/preview?' + $('form').serialize());
});
JS
);

$attributes = [];
foreach ($model->colorAttributes() as $attribute) {
    $attributes[$attribute] = [
        'type' => Form::INPUT_HTML5,
        'html5type' => 'color',
    ];
}

$this->registerCss(<<<CSS
.columns .form-group {
    break-inside: avoid;
}

.columns label {
    display: inline-block;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
CSS
);

echo Html::beginTag('div', ['style' => ['column-width' => '250px'], 'class' => ['columns']]);
if (!empty($attributes)) {
    echo Form::widget([
        'form' => $form,
        'model' => $model,
        'attributes' => $attributes
    ]);
}
echo Html::endTag('div');

echo Form::widget([
    'form' => $form,
    'model' => $model,
    'attributes' => [
        FormButtonsWidget::embed([
            'buttons' => [
                [
                    'label' => $this->title,
                    'type' => ButtonGroup::TYPE_SUBMIT,
                    'style' => 'primary',
                    'buttonOptions' => [
                        'name' => 'action',
                        'value' => 'refresh'
                    ],
                ],
                [
                    'label' => $model->isNewRecord
                        ? \Yii::t('app', 'Create element & go back', ['action' => $this->title])
                        : \Yii::t('app', 'Update element & go back', ['action' => $this->title]),
                    'type' => ButtonGroup::TYPE_SUBMIT,
                    'style' => 'default',
                    'buttonOptions' => [
                        'name' => 'action',
                        'value' => 'dashboard'
                    ],
                ],
                [
                    'label' => \Yii::t('app', 'Discard & go back'),
                    'type' => ButtonGroup::TYPE_LINK,
                    'link' => Yii::$app->request->referrer,
                    'style' => 'white',
                ],
            ],
        ]),
    ],
]);

ActiveForm::end();

Section::end();

Section::begin();

if (isset($model->id)) {
    $url = ['element/preview'];
    foreach ($model->safeAttributes() as $attribute) {
        $url[Html::getInputName($model, $attribute)] = $model->$attribute;
    }
    $url['id'] = $model->id;
    echo Html::tag('iframe', '', [
        'id' => 'preview-frame',
        'src' => Url::to($url),
        'class' => ['form-bg'],
        'style' => [
            'border' => 'none',
            'width' => '100%',
            'height' => '100%',
            'background-color' => 'white'
        ]
    ]);
}

Section::end();
