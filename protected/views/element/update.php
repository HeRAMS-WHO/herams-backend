<?php

/** @var \prime\models\ar\Element|\prime\models\forms\Element $model */
/** @var \prime\models\ar\Project $project */
/** @var View $this */

use app\components\Form;
use kartik\select2\Select2;
use app\components\ActiveForm;
use yii\bootstrap\ButtonGroup;
use yii\bootstrap\Html;
use yii\helpers\Url;
use yii\web\View;

$this->params['breadcrumbs'][] = [
    'label' => $project->title,
    'url' => ['project/update', 'id' => $project->id]
];

$this->params['breadcrumbs'][] = [
    'label' => \Yii::t('app', 'Dashboard settings'),
    'url' => ['project/pages', 'id' => $project->id]
];

$this->params['breadcrumbs'][] = [
    'label' => $page->title,
    'url' => ['page/update', 'id' => $page->id]
];

$this->title = $model->isNewRecord
    ? \Yii::t('app', 'Create element')
    : \Yii::t('app', 'Update element');

$this->params['breadcrumbs'][] = [
    'label' => $this->title,
    'url' => ['page/update', 'id' => $page->id]
];


echo Html::beginTag('div', ['class' => "content no-tab"]);
?>
<div class="form-content form-bg">
    <h4><?= \Yii::t('app', 'Update element'); ?></h4>
    <?php
    $form = ActiveForm::begin([
        "type" => ActiveForm::TYPE_HORIZONTAL,
        'enableClientValidation' => true,
        'formConfig' => [
            'showLabels' => true,
            'defaultPlaceholder' => false,
            'labelSpan' => 3
        ]
    ]);
    //echo Html::activeHiddenInput($model, 'referrer', ['value' => Yii::$app->request->referrer]);
    //echo $form->hiddenField($model, ['value'=> Yii::$app->request->referrer])->label(false);

    echo Form::widget([

        'form' => $form,

        'model' => $model,
        'columns' => 1,
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



    $url = \yii\helpers\Json::encode($url);
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
            'allowUnsafe' => true,
        ];
    }

    if (!empty($attributes)) {
        $form->formConfig['labelSpan'] = 8;
        echo Form::widget([
            'form' => $form,
            'model' => $model,
            'columns' => 2,
            'attributes' => $attributes
        ]);
    }
    echo ButtonGroup::widget([
        'options' => [
            'class' => [
                'pull-right',
                'buttons-row'
            ],
        ],
        'buttons' => [
            Html::submitButton(
                $this->title,
                [
                    'class' => 'btn btn-primary',
                    'name' => 'action',
                    'value' => 'refresh'
                ]
            ),
            Html::submitButton(\Yii::t('app', 'Update & go back'), [
                'class' => 'btn btn-save-back',
                'name' => 'action',
                'value' => 'dashboard'
            ]),
            Html::a(
                \Yii::t('app', 'Discard & go back'),
                Yii::$app->request->referrer,
                [
                    'class' => 'btn btn-white'
                ]
            )
        ]
    ]);
    $form->end();

    ?>
</div>
<div class='form-element <?= $model->type; ?>'>
    <?php
    if (isset($model->id)) {
        $params = ['element/preview'];
        foreach ($model->safeAttributes() as $attribute) {
            $params[Html::getInputName($model, $attribute)] = $model->$attribute;
        }
        $params['id'] = $model->id;
        echo Html::tag('iframe', '', [
            'id' => 'preview-frame',
            'src' => Url::to($params),
            'class' => ['form-bg'],
            'style' => [
                'border' => 'none',
                'width' => '100%',
                'height' => '100%',
                'background-color' => 'white'
            ]
        ]);
    }
    ?>
</div>
<?php
echo Html::endTag('div');
?>