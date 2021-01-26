<?php
declare(strict_types=1);
use app\components\ActiveForm;
use prime\widgets\Section;
use yii\bootstrap\Html;
use yii\helpers\Url;
use prime\models\ar\Permission;
use prime\widgets\menu\TabMenu;

/**
 * @var \prime\models\ar\Workspace $workspace
 * @var \prime\models\forms\Share $model
 * @var \prime\components\View $this
 */
$this->title = \Yii::t('app', 'Administration');
$this->params['breadcrumbs'][] = ['label' => ""];

$tabs = [
    [
        'url' => ['admin/dashboard'],
        'title' => \Yii::t('app', 'Dashboard')
    ]
];

$this->params['tabs'] = [
    [
        'permission' => Permission::PERMISSION_ADMIN,
        'url' => ['admin/dashboard'],
        'title' => \Yii::t('app', 'Dashboard')
    ],
    [
        'permission' => Permission::PERMISSION_ADMIN,
        'url' => ['user/index'],
        'title' => \Yii::t('app', 'Users')
    ],
    [
        'permission' => Permission::PERMISSION_ADMIN,
        'url' => ['admin/share'],
        'title' => \Yii::t('app', 'Global permissions')
    ],
    [
        'permission' => Permission::PERMISSION_ADMIN,
        'url' => ['admin/limesurvey'],
        'title' => \Yii::t('app', 'Backend administration')
    ]
];

Section::begin([
    'header' => \Yii::t('app', 'Add user')
]);
$form = ActiveForm::begin([
    'method' => 'POST',
    "type" => ActiveForm::TYPE_HORIZONTAL,
    'formConfig' => [
        'showLabels' => true,
        'defaultPlaceholder' => false,
        'labelSpan' => 3
    ]
]);

echo $model->renderForm($form);
$form->end();
Section::end();
Section::begin(['header' => \Yii::t('app', 'Already shared with')]);

echo $model->renderTable();
Section::end();
