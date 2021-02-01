<?php
declare(strict_types=1);

use app\components\Form;
use app\components\ActiveForm;
use prime\widgets\FormButtonsWidget;
use prime\widgets\menu\ProjectTabMenu;
use prime\widgets\Section;
use yii\bootstrap\Html;

/**
 * @var \yii\web\View $this
 * @var \prime\models\forms\Export $model
 * @var \prime\models\ar\Project $subject
 */

$this->params['breadcrumbs'][] = [
    'label' => $subject->title,
    'url' => ['project/workspaces', 'id' => $subject->id]
];

$this->title = \Yii::t('app', 'Export data');

$this->beginBlock('tabs');
echo ProjectTabMenu::widget([
    'project' => $subject,
]);
$this->endBlock();

Section::begin()->withHeader('Export');
echo $this->render('//shared/exportform', ['model' => $model]);
Section::end();
