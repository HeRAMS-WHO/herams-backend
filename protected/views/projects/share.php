<?php

use \app\components\Form;
use \app\components\ActiveForm;
use app\components\Html;

/**
 * @var \prime\models\ar\Project $project
 * @var \prime\models\forms\Share $model
 */
$this->params['sectionTitle'] = 'Share workspace';
$this->params['subMenu'] = [
    'items' => [
        [
            'label' => Html::submitButton(\Yii::t('app', 'Share'), ['form' => 'share-project', 'class' => 'btn btn-primary'])
        ],
    ]
];
?>
<h1><?=\Yii::t('app', 'Share {projectName}', ['projectName' => $project->title])?></h1>
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

    echo $model->renderForm($form);

    $form->end();
    ?>
    <div class="col-xs-offset-11"><button type="submit" class="btn btn-primary" form="share-project">Share</button></div>

    <h2><?=\Yii::t('app', 'Already shared with')?></h2>
    <?php
    echo $model->renderTable('/projects/share-delete');
    ?>
</div>

