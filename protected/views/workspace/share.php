<?php

use kartik\widgets\ActiveForm;
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
    <div class="col-xs-12 permissions-form">
        <?php
        echo Html::tag('h2', \Yii::t('app', 'Users and permissions'));
        $form = ActiveForm::begin([
            'method' => 'POST',
            "type" => ActiveForm::TYPE_HORIZONTAL,
            'formConfig' => [
                'showLabels' => true,
                'defaultPlaceholder' => false
            ]
        ]);

        echo $model->renderForm($form);
        $form->end();
        ?>
        <button type="submit" class="btn btn-primary">Share</button>
    </div>
    <div class="col-xs-12 list-shared">
        <h2><?=\Yii::t('app', 'Already shared with')?></h2>
        <?php
        echo $model->renderTable();
        ?>
    </div>
</div>

