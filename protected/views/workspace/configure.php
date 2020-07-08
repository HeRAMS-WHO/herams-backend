<?php

use app\components\ActiveForm;
use yii\bootstrap\ButtonGroup;
use yii\bootstrap\Html;

/**
 * @var \yii\web\View $this
 * @var \prime\models\forms\projects\Token $token
 *
 */

$this->params['breadcrumbs'][] = [
    'label' => \Yii::t('app', 'Admin dashboard'),
    'url' => ['/admin']
];
$this->params['breadcrumbs'][] = [
    'label' => \Yii::t('app', 'Workspaces for {project}', [
        'project' => $model->project->title
    ]),
    'url' => ['project/workspaces', 'id' => $model->project->id]
];
$this->params['breadcrumbs'][] = [
    'label' => \Yii::t('app', 'Workspace {workspace}', [
        'workspace' => $model->title,
    ]),
    'url' => ['workspace/update', 'id' => $model->id]
];

$this->title = Yii::t('app', 'Update workspace token');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="form-content form-bg">
    <h3><?=\Yii::t('app', 'Update workspace token')?></h3>
<?php
$form = ActiveForm::begin([
    'method' => 'PUT',
    "type" => ActiveForm::TYPE_HORIZONTAL,
    'formConfig' => [
        'showLabels' => true,
        'defaultPlaceholder' => false
    ]
]);
foreach ($token->attributes() as $attribute) {
    if ($token->isAttributeSafe($attribute)) {
        echo $form->field($token, $attribute);
    } else {
        echo $form->field($token, $attribute)->textInput([
            'readonly' => true
        ]);
    }
}

echo ButtonGroup::widget([
    'options' => [
        'class' => 'pull-right'
    ],
    'buttons' => [
        Html::submitButton(\Yii::t('app', 'Update token'), ['class' => 'btn btn-primary'])
    ]
]);
$form->end();
?>
</div>
