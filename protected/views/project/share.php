<?php

use app\components\ActiveForm;
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

$this->title = \Yii::t('app', 'Share {project}', ['project' => $project->title]);
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="col-xs-12">
    <?php
    $form = ActiveForm::begin([
        "type" => ActiveForm::TYPE_HORIZONTAL,
        'formConfig' => [
            'showLabels' => true,
            'defaultPlaceholder' => false
        ]
    ]);

    echo $model->renderForm($form);

    ?>
    <div class="col-xs-offset-11"><button type="submit" class="btn btn-primary">Share</button></div>
    <?php
    $form->end();


    echo Html::tag('h2', \Yii::t('app', 'Already shared with'));
    echo $model->renderTable();
    ?>
</div>

