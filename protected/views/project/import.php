<?php

/** @var \prime\models\ar\Project $model */
/** @var \prime\models\ar\Project $project */

use app\components\Form;
use app\components\ActiveForm;
use prime\widgets\InlineUpload\InlineUpload;
use yii\bootstrap\ButtonGroup;
use yii\bootstrap\Html;

$this->params['breadcrumbs'][] = [
    'label' => $project->title,
    'url' => ['project/update', 'id' => $project->id]
];

$this->params['breadcrumbs'][] = [
    'label' => \Yii::t('app', 'Pages'),
    'url' => ['project/pages', 'id' => $project->id]
];

$this->params['breadcrumbs'][] = [
    'label' => \Yii::t('app', 'Import pages'),
    'url' => ['project/import-dashboard', 'id' => $project->id]
];

$this->title = \Yii::t('app', 'Import pages');

echo Html::beginTag('div', ['class' => "content no-tab"]);
?>
<div class="form-content form-bg">
    <h4><?=\Yii::t('app', 'Import Project')?></h4>
    <?php
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
        'model' => $model,
        'columns' => 1,
        "attributes" => [
            'pages' => [
                'type' => Form::INPUT_WIDGET,
                'widgetClass' => InlineUpload::class
            ],
            [
                'type' => Form::INPUT_RAW,
                'value' => ButtonGroup::widget([
                    'buttons' => [
                        Html::submitButton(\Yii::t('app', 'Import pages'), ['class' => 'btn btn-primary'])
                    ]
                ])
            ],

            ]
        ]);
    $form->end();

    ?>
</div>

<?php
echo Html::endTag('div');
?>