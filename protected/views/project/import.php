<?php

/** @var \prime\models\ar\Project $model */

use app\components\Form;
use kartik\grid\ActionColumn;
use kartik\grid\GridView;
use kartik\widgets\ActiveForm;
use prime\helpers\Icon;
use prime\models\ar\Page;
use prime\models\permissions\Permission;
use prime\widgets\InlineUpload\InlineUpload;
use yii\bootstrap\ButtonGroup;
use yii\bootstrap\Html;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;


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
<div class="col-xs-12">
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
