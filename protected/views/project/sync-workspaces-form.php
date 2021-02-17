<?php
declare(strict_types=1);

/**
 * @var \prime\components\View $this
 * @var \prime\models\ar\Project $project
 * @var \prime\models\forms\project\SyncWorkspaces $model
 */

use app\components\ActiveForm;
use app\components\Form;
use Carbon\Carbon;
use prime\assets\TimeElementBundle;
use prime\widgets\BetterSelect;
use prime\widgets\FormButtonsWidget;
use prime\widgets\menu\ProjectTabMenu;
use prime\widgets\Section;
use yii\helpers\Html;

$this->params['breadcrumbs'][] = [
    'label' => $project->title,
    'url' => ['project/workspaces', 'id' => $project->id]
];

$this->title = \Yii::t('app', 'Sync workspaces');

TimeElementBundle::register($this);
Section::begin()->withHeader(\Yii::t('app', 'Sync workspaces'));
$this->registerCss(<<<CSS
    data > * {
        display: inline-block;
        margin-right: 20px;
        
    }
    
    data > *:first-child {
        width: 200px;
        text-overflow: ellipsis;
        display: inline-block;
    }

CSS
);

$form = ActiveForm::begin([

]);
echo Form::widget([
    'model' => $model,
    'form' => $form,
    'attributes' => [
        'workspaces' => [
            'type' => Form::INPUT_WIDGET,
            'widgetClass' => BetterSelect::class,
            'options' => [
                'items' => $model->workspaceOptions(),
                'options' => [
                    'style' => [
                        'column-width' => '350px',
                        'height' => 'auto',
                        'max-height' => '500px'
                    ]
                ]
            ]
        ],
        FormButtonsWidget::embed([
            'buttons' => [
                [
                    'type' => \prime\widgets\ButtonGroup::TYPE_SUBMIT,
                    'label' => \Yii::t('app', 'Start sync')
                ]
            ]
        ])
    ]
]);

ActiveForm::end();

Section::end();
