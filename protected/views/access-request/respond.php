<?php
declare(strict_types=1);

use app\components\ActiveForm;
use app\components\Form;
use prime\components\View;
use prime\models\ar\AccessRequest;
use prime\models\ar\Permission;
use prime\models\forms\accessRequest\Respond;
use prime\widgets\FormButtonsWidget;
use prime\widgets\Section;
use yii\helpers\Html;
use function iter\map;

/**
 * @var Respond $model
 * @var View $this
 */

$this->title = \Yii::t('app', 'Respond to: {modelName}', ['modelName' => $model->getAccessRequest()->subject]);

Section::begin()
    ->withHeader($this->title);

echo Html::tag('p', \Yii::t('app', 'Review the requested permission. Either grant permissions by selecting the permissions you think are applicable or deny the request by leaving the granted permissions empty. Explain your decision in the textarea.'));

$form = ActiveForm::begin([
    'method' => 'POST',
]);

echo Form::widget([
    'form' => $form,
    'model' => $model,
    'attributes' => [
        'subject' => [
            'type' => Form::INPUT_STATIC,
            'label' => \Yii::t('app', 'Subject'),
            'staticValue' => $model->getAccessRequest()->subject . ' (' . $model->getAccessRequest()->targetClassOptions()[$model->getAccessRequest()->target_class] . ')',
        ],
        'body' => [
            'type' => Form::INPUT_STATIC,
            'label' => \Yii::t('app', 'Body'),
            'staticValue' => $model->getAccessRequest()->body,
        ],
        'requestedPermissions' => [
            'type' => Form::INPUT_STATIC,
            'label' => \Yii::t('app', 'Requested permissions'),
            'staticValue' => Html::ul(map(static fn($permission) =>
                Permission::permissionLabels()[AccessRequest::permissionMap($model->getAccessRequest()->target)[$permission] ?? null] ?? $model->getAccessRequest()->permissionOptions()[$permission], $model->getAccessRequest()->permissions), ['style' => ['margin-top' => 0, 'padding-left' => '17px']]),
        ],
        'response' => [
            'type' => Form::INPUT_TEXTAREA,
        ],
        'permissions' => [
            'type' => Form::INPUT_CHECKBOX_LIST,
            'items' => $model->getPermissionOptions(),
        ],

        FormButtonsWidget::embed([
            'buttons' => [
                ['label' => Yii::t('app', 'Respond'), 'style' => 'primary'],
            ],
        ]),
    ]
]);

ActiveForm::end();
Section::end();
