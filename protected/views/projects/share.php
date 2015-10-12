<?php

use \app\components\Form;
use \app\components\ActiveForm;
use app\components\Html;

/**
 * @var \prime\models\Project $project
 */

$this->title = \Yii::t('app', 'Share {projectTitle} with:', [
    'projectTitle' => $model->project->title
]);
?>

<div class="col-xs-12">
    <?php
    $form = ActiveForm::begin([
        'id' => 'share-project',
        'method' => 'POST',
        "type" => ActiveForm::TYPE_HORIZONTAL,
        'formConfig' => [
            'showLabels' => true,
            'defaultPlaceholder' => false
        ]
    ]);

    echo \app\components\Form::widget([
        'form' => $form,
        'model' => $model,
        'columns' => 1,
        "attributes" => [
            'source_id' => [
                'label' => \Yii::t('app', 'User'),
                'type' => Form::INPUT_WIDGET,
                'widgetClass' => \kartik\widgets\Select2::class,
                'options' => [
                    'data' => array_diff_key(
                        \yii\helpers\ArrayHelper::map(\prime\models\User::find()
                            ->andWhere([
                                'not',
                                [
                                    'id' => \prime\models\permissions\UserProject::find()
                                        ->andWhere(['target_id' => $model->target_id])
                                        ->select('source_id')
                                ]
                            ])
                            ->all(), 'id', 'name'),
                        [$model->project->owner_id => true]
                    )

                ]
            ],
            'permission' => [
                'label' => \Yii::t('app', 'Permission'),
                'type' => Form::INPUT_DROPDOWN_LIST,
                'items' => \prime\models\permissions\UserProject::permissionLabels()
            ],
            'actions' => [
                'type' => Form::INPUT_RAW,
                'value' =>
                    Html::submitButton(\Yii::t('app', 'Submit'), ['class' => 'btn btn-primary col-xs-12'])
            ]
        ]
    ]);

    $form->end();
    ?>
</div>

