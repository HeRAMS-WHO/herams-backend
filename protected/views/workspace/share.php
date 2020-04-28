<?php

use app\components\ActiveForm;
use app\components\Form;
use prime\widgets\FormButtonsWidget;
use yii\helpers\Html;

/**
 * @var \prime\models\ar\Workspace $workspace
 * @var \prime\models\forms\Share $model
 */
$this->params['breadcrumbs'][] = [
    'label' => \Yii::t('app', 'Admin dashboard'),
    'url' => ['/admin']
];
$this->params['breadcrumbs'][] = [
    'label' => \Yii::t('app', 'Projects'),
    'url' => ['/project']
];
$this->params['breadcrumbs'][] = [
    'label' => \Yii::t('app', 'Workspaces for {project}', [
        'project' => $workspace->project->title
    ]),
    'url' => ['project/workspaces', 'id' => $workspace->project->id]
];
$this->title = \Yii::t('app', 'Share workspace {workspace}', ['workspace' => $workspace->title]);
$this->params['breadcrumbs'][] = $this->title;




?>
<div class="col-xs-12 share-form">
    <div class="col-xs-12 form-content permissions-form form-bg">
        <?php
        echo Html::tag('h3', \Yii::t('app', 'Add permissions'));
        $form = ActiveForm::begin([
            'method' => 'POST',
            "type" => ActiveForm::TYPE_HORIZONTAL,
            'formConfig' => [
                'showLabels' => true,
                'defaultPlaceholder' => false
            ]
        ]);

        echo $model->renderForm($form);
        echo Form::widget([
            'form' => $form,
            'model' => $model,
            'attributes' => [
                FormButtonsWidget::embed([
                    'buttons' => [
                        ['label' => \Yii::t('app', 'Add'), 'options' => ['class' => ['btn', 'btn-primary']]]
                    ]
                ])
            ]
        ]);
        $form->end();
        ?>
    </div>
    <div class="col-xs-12 form-content list-shared form-bg">
        <h3><?=\Yii::t('app', 'View user permissions')?></h3>
        <?php
        echo $model->renderTable();
        ?>
    </div>
</div>


