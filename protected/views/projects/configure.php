<?php

use \app\components\Form;
use \app\components\ActiveForm;
use app\components\Html;

/**
 * @var \yii\web\View $this
 * @var \prime\models\forms\projects\Token $token
 *
 */

$this->title = Yii::t('app', 'Update project');

$this->params['subMenu'] = [
    'items' => [
        [
            'label' => Html::submitButton(\Yii::t('app', 'save'), ['form' => 'update-project', 'class' => 'btn btn-primary'])
        ],
    ]
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

$form->end();

