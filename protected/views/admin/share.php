<?php
declare(strict_types=1);

use app\components\ActiveForm;
use prime\components\View;
use prime\models\ar\Permission;
use prime\models\ar\Workspace;
use prime\models\forms\Share;
use prime\widgets\Section;

/**
 * @var Workspace $workspace
 * @var Share $model
 * @var View $this
 */
$this->title = \Yii::t('app', 'Global permissions');

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

Section::begin()->withHeader(\Yii::t('app', 'Add user'));

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
ActiveForm::end();

Section::end();

Section::begin()->withHeader(\Yii::t('app', 'Already shared with'));

echo $model->renderTable();

Section::end();
