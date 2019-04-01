<?php

use kartik\widgets\ActiveForm;
use yii\bootstrap\Html;

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

$this->title = \Yii::t('app', 'Share {project}', ['project' => $project->title]);
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="col-xs-12">
    <?php
    $form = ActiveForm::begin([
        'id' => 'share-project',
        "type" => ActiveForm::TYPE_HORIZONTAL,
        'formConfig' => [
            'showLabels' => true,
            'defaultPlaceholder' => false
        ]
    ]);

    echo $model->renderForm($form);

    $form->end();
    ?>
    <div class="col-xs-offset-11"><button type="submit" class="btn btn-primary" form="share-project">Share</button></div>
    <h2><?=\Yii::t('app', 'Already shared with')?></h2>
    <?php
    echo $model->renderTable();
    ?>
</div>

