<?php

/** @var \prime\models\ar\Project $model */

use app\components\Form;
use app\components\ActiveForm;
use prime\widgets\InlineUpload\InlineUpload;
use yii\bootstrap\ButtonGroup;
use yii\bootstrap\Html;

$this->params['breadcrumbs'][] = [
    'label' => \Yii::t('app', 'Admin dashboard'),
    'url' => ['/admin']
];
$this->params['breadcrumbs'][] = [
    'label' => \Yii::t('app', 'Projects'),
    'url' => ['/project']
];

$this->title = 'Import pages';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-xs-12 col-md-8 col-lg-6 form-content form-bg">
    <h3><?=\Yii::t('app', 'Import Project')?></h3>
    <?php
    $form = ActiveForm::begin([
        "type" => ActiveForm::TYPE_HORIZONTAL,
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
