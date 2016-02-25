<?php

use \app\components\Form;
use \app\components\ActiveForm;
use app\components\Html;

/**
 * @var \prime\models\ar\Project $project
 * @var \prime\models\forms\projects\Share $model
 * @var \yii\data\ActiveDataProvider $permissionsDataProvider
 */

$this->title = \Yii::t('app', 'Share {projectTitle} with:', [
    'projectTitle' => $model->project->title
]);

$this->params['subMenu'] = [
    'items' => [
        [
            'label' => Html::submitButton(\Yii::t('app', 'Share'), ['form' => 'share-project', 'class' => 'btn btn-primary'])
        ],
    ]
];
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
            'userIds' => [
                'type' => Form::INPUT_WIDGET,
                'widgetClass' => \kartik\widgets\Select2::class,
                'options' => [
                    'data' => $model->userOptions,
                    'options' => [
                    'multiple' => true
                        ]
                ]
            ],
            'userListIds' => [
                'type' => Form::INPUT_WIDGET,
                'widgetClass' => \kartik\widgets\Select2::class,
                'options' => [
                    'data' => $model->userListOptions,
                    'options' => [
                        'multiple' => true
                    ]
                ]
            ],
            'permission' => [
                'label' => \Yii::t('app', 'Permission'),
                'type' => Form::INPUT_DROPDOWN_LIST,
                'items' => $model->permissionOptions
            ]
        ]
    ]);

    $form->end();
    ?>
    <h2><?=\Yii::t('app', 'Already shared with')?></h2>
    <?php
    echo \kartik\grid\GridView::widget([
        'dataProvider' => $permissionsDataProvider,
        'columns' => [
            [
                'label' => \Yii::t('app', 'User'),
                'value' => function($model){
                    return $model->sourceObject->name;
                }
            ],
            'permissionLabel',
            [
                'class' => \kartik\grid\ActionColumn::class,
                'template' => '{delete}',
                'buttons' => [
                    'delete' => function($url, $model, $key) use ($project) {
                        return Html::a(
                            Html::icon('trash'),
                            [
                                '/projects/share-delete',
                                'id' => $model->id
                            ],
                            [
                                'class' => 'text-danger',
                                'data-method' => 'delete',
                                'data-confirm' => \Yii::t('app', 'Are you sure you want to stop sharing <strong>{projectName}</strong> with <strong>{userName}</strong>', [
                                    'projectName' => $project->title,
                                    'userName' => $model->sourceObject->name
                                ]),
                                'title' => \Yii::t('app', 'Remove')
                            ]
                        );
                    }
                ]
            ]
        ]
    ])
    ?>
</div>

