<?php
declare(strict_types=1);

use app\components\ActiveForm;
use app\components\Form;
use prime\components\View;
use prime\interfaces\WorkspaceForTabMenu;
use prime\models\ar\Permission;
use prime\models\ar\Workspace;
use prime\models\forms\Share;
use prime\widgets\FormButtonsWidget;
use prime\widgets\menu\WorkspaceTabMenu;
use prime\widgets\Section;
use yii\helpers\Html;

/**
 * @var Workspace $workspace
 * @var Share $model
 * @var WorkspaceForTabMenu $tabMenuModel
 * @var View $this
 */

$this->title = $workspace->title;

$this->beginBlock('tabs');
echo WorkspaceTabMenu::widget([
    'workspace' => $tabMenuModel,
]);
$this->endBlock();

Section::begin(['header' => \Yii::t('app', 'Add new user')]);
$form = ActiveForm::begin([
    'method' => 'POST',
    "type" => ActiveForm::TYPE_HORIZONTAL,
    'formConfig' => [
        'labelSpan' => 3
    ]
]);

echo $model->renderForm($form);
$form->end();
Section::end();
Section::begin(['header' => \Yii::t('app', 'View user permissions')]);
echo $model->renderTable();
Section::end();
