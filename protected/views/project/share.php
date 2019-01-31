<?php

use kartik\widgets\ActiveForm;
use yii\bootstrap\Html;

/**
 * @var \prime\models\ar\Workspace $project
 * @var \prime\models\forms\Share $model
 */

$this->params['subMenu'] = [
    'items' => [
        [
            'label' => Html::submitButton(\Yii::t('app', 'Share'), ['form' => 'share-tool', 'class' => 'btn btn-primary'])
        ],
    ]
];
?>
<h1><?=\Yii::t('app', 'Share {toolName}', ['toolName' => $tool->title]) ?></h1>
<div class="col-xs-12">
    <?php
    $form = ActiveForm::begin([
        'id' => 'share-tool',
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
    <h2><?=\Yii::t('app', 'Already shared with')?></h2>
    <?php
    echo $model->renderTable('/tools/share-delete');
    ?>
</div>

