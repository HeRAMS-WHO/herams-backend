<?php

use app\components\ActiveForm;
use app\components\Form;
use yii\helpers\Html;

/**
 * @var \prime\models\ar\Project $project
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

$this->title = \Yii::t('app', 'Manage user permissions for {project}', ['project' => $project->title]);
$this->params['breadcrumbs'][] = $this->title;

echo Html::tag('h2', \Yii::t('app', 'Add permissions'));
        $form = ActiveForm::begin([
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
                \prime\widgets\FormButtonsWidget::embed([
                    'buttons' => [
                        '<button type="submit" class="btn btn-primary">Add</button>'
                    ]
                ])
            ]
        ]);

        $form->end();
        ?>
    <div class="col-xs-12 list-shared">
        <h2><?=\Yii::t('app', 'View user permissions')?></h2>
        <?php
        echo $model->renderTable();
        ?>
    </div>


