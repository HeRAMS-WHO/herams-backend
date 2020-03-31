<?php

use app\components\ActiveForm;
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
    <div class="col-xs-12 col-md-8 col-lg-6 permissions-form form-bg">
        <?php
        echo Html::tag('h2', \Yii::t('app', 'Add permissions'));
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
    <div class="col-xs-12 list-shared form-bg">
        <h2><?=\Yii::t('app', 'View user permissions')?></h2>
        <?php
        echo $model->renderTable();
        ?>
    </div>
</div>

