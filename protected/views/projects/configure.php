<?php

use \app\components\Form;
use \app\components\ActiveForm;
use app\components\Html;

/**
 * @var \yii\web\View $this
 * @var \prime\models\forms\projects\Token $token
 *
 */

$this->title = Yii::t('app', 'Update workspace token');
$this->params['breadcrumbs'][] = [
    'label' => \Yii::t('app', 'Back to project overview'),
    'url' => ['projects/overview', 'pid' => $model->tool_id]
];
$this->params['breadcrumbs'][] = [
    'label' => \Yii::t('app', 'Manage workspaces'),
    'url' => ['projects/list', 'toolId' => $model->tool_id]
];
$this->params['breadcrumbs'][] = [
    'label' => \Yii::t('app', 'Update workspace'),
    'url' => ['projects/update', 'id' => $model->id]
];


ob_start();
$form = ActiveForm::begin([
    'id' => 'update-project',
    'method' => 'PUT',
    "type" => ActiveForm::TYPE_HORIZONTAL,
    'formConfig' => [
        'showLabels' => true,
        'defaultPlaceholder' => false
    ]
]);

foreach($token->attributes() as $attribute) {
    if ($token->isAttributeSafe($attribute)) {
        echo $form->field($token, $attribute);
    } else {
        echo $form->field($token, $attribute)->textInput([
            'readonly' => true
        ]);
    }
}

echo \yii\bootstrap\ButtonGroup::widget([
    'options' => [
        'class' => 'pull-right'
    ],
    'buttons' => [
        Html::submitButton(\Yii::t('app', 'Save'), ['form' => 'update-project', 'class' => 'btn btn-primary'])
    ]
]);
$form->end();

