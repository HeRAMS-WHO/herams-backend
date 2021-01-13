<?php

use app\components\Form;
use app\components\ActiveForm;
use prime\widgets\FormButtonsWidget;
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

$this->title = \Yii::t('app', 'Export data from project {project}', ['project' => $subject->title]);

echo $this->render('//shared/exportform', ['model' => $model]);
