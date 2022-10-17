<?php

declare(strict_types=1);

use prime\components\View;
use prime\helpers\Icon;
use prime\models\ar\Permission;
use prime\widgets\ButtonGroup;
use prime\widgets\menu\ProjectTabMenu;
use prime\widgets\Section;
use prime\widgets\survey\Survey;
use yii\helpers\Html;

/**
 * @var \prime\models\ar\read\Project $project
 * @var \prime\interfaces\survey\SurveyForSurveyJsInterface $survey
 * @var \prime\values\ProjectId $projectId
 * @var View $this
 */

$this->title = \Yii::t('app', "Project management");
$this->params['subject'] = $project->getTitle();

$this->beginBlock('tabs');
echo ProjectTabMenu::widget([
    'project' => $project,
]);
$this->endBlock();

Section::begin()
    ->withSubject($project)
    ->withHeader(Yii::t('app', 'Project settings'));

$survey = Survey::begin()
    ->withConfig($survey->getConfig())
    ->withDataRoute([
        '/api/project/view',
        'id' => $projectId,
    ])
    ->withExtraData([
        'id' => $projectId,
    ])
    ->withSubmitRoute([
        '/api/project/update',
        'id' => $projectId,
    ])
    ->withServerValidationRoute([
        '/api/project/validate',
        'id' => $projectId,
    ]);

Survey::end();

Section::end();

Section::begin()
    ->withHeader(Yii::t('app', 'Delete project'))
    ->withSubject($project)
    ->withPermission(Permission::PERMISSION_DELETE)
    ->forDangerousAction()
;

echo Html::tag('p', Yii::t('app', 'This will permanently delete the project and all its workspaces.'));
echo Html::tag('p', Yii::t('app', 'This action cannot be undone.'));
echo Html::tag('p', Html::tag('em', Yii::t('app', 'Are you ABSOLUTELY SURE you wish to delete this project?')));

echo ButtonGroup::widget([
    'buttons' => [
        [
            'icon' => Icon::trash(),
            'label' => Yii::t('app', 'Delete'),
            'link' => [
                'project/delete',
                'id' => $project->id,
            ],
            'style' => ButtonGroup::STYLE_DELETE,
            'linkOptions' => [
                'data-method' => 'delete',
                'title' => Yii::t('app', 'Delete project'),
                'data-confirm' => Yii::t('app', 'Are you sure you wish to remove this project from the system?'),
            ],
        ],
    ],
]);

Section::end();

Section::begin()
    ->withHeader(Yii::t('app', 'Empty project'))
    ->withSubject($project)
    ->withPermission(Permission::PERMISSION_DELETE_ALL_WORKSPACES)
    ->forDangerousAction()
;

echo Html::tag('p', Yii::t('app', 'This will permanently delete all workspaces in the project.'));
echo Html::tag('p', Yii::t('app', 'This action cannot be undone.'));
echo Html::tag('p', Html::tag('em', Yii::t('app', 'Are you ABSOLUTELY SURE you wish to delete all workspaces?')));

echo ButtonGroup::widget([
    'buttons' => [
        [
            'icon' => Icon::trash(),
            'label' => Yii::t('app', 'Delete all workspaces'),
            'link' => [
                'project/delete-workspaces',
                'id' => $project->id,
            ],
            'style' => ButtonGroup::STYLE_DELETE,
            'linkOptions' => [
                'data-method' => 'delete',
                'title' => Yii::t('app', 'Delete all workspaces in project'),
                'data-confirm' => Yii::t('app', 'Are you sure you wish to remove the workspaces from the system?'),
            ],
        ],
    ],
]);

Section::end();
