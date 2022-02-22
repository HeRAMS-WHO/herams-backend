<?php

declare(strict_types=1);

use app\components\ActiveForm;
use prime\components\View;
use prime\models\ar\Permission;
use prime\models\ar\WorkspaceForLimesurvey;
use prime\models\forms\Share;
use prime\widgets\menu\AdminTabMenu;
use prime\widgets\Section;

/**
 * @var WorkspaceForLimesurvey $workspace
 * @var Share $model
 * @var View $this
 */
$this->title = \Yii::t('app', 'Global permissions');

$this->beginBlock('tabs');
echo AdminTabMenu::widget([

]);
$this->endBlock();

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
