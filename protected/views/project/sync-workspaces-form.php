<?php

declare(strict_types=1);

use app\components\ActiveForm;
use app\components\Form;
use Carbon\Carbon;
use prime\assets\TimeElementBundle;
use prime\components\View;
use prime\models\ar\Project;
use prime\models\ar\WorkspaceForLimesurvey;
use prime\models\forms\project\SyncWorkspaces;
use prime\widgets\BetterSelect;
use prime\widgets\FormButtonsWidget;
use prime\widgets\Section;
use yii\helpers\Html;

/**
 * @var View $this
 * @var Project $project
 * @var SyncWorkspaces $model
 */

$this->title = $project->title;

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
                'items' => (static function (iterable $workspaces) {
                    /** @var WorkspaceForLimesurvey $workspace */
                    foreach ($workspaces as $workspace) {
                        $title = Html::tag('span', $workspace->title);

                        if (isset($workspace->latestUpdate)) {
                            $latestUpdate = Html::tag('time-ago', $workspace->latestUpdate, [
                                'datetime' => (new Carbon($workspace->latestUpdate))->toIso8601String()
                            ]);
                        } else {
                            $latestUpdate = \Yii::t('app', 'never');
                        }
                        yield $workspace->id => $title . $latestUpdate;
                    }
                })($model->workspaceOptions()),
                'options' => [
                    'style' => [
                        'column-width' => '350px',
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
