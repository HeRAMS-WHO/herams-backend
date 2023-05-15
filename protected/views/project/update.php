<?php

declare(strict_types=1);

use herams\common\models\Permission;
use prime\components\View;
use prime\helpers\Icon;
use prime\widgets\ButtonGroup;
use prime\widgets\menu\ProjectTabMenu;
use prime\widgets\Section;
use prime\widgets\survey\SurveyFormWidget;
use yii\helpers\Html;

/**
 * @var \prime\models\ar\read\Project $project
 * @var \prime\interfaces\SurveyFormInterface $form
 * @var \herams\common\values\ProjectId $projectId
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

$survey = SurveyFormWidget::begin()
    ->withForm($form);


SurveyFormWidget::end();

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

// echo ButtonGroup::widget([
//     'buttons' => [
//         [
//             'icon' => Icon::trash(),
//             'label' => Yii::t('app', 'Delete'),
//             'link' => [
//                 'project/delete',
//                 'id' => cccc
//             ],
//             'style' => ButtonGroup::STYLE_DELETE,
//             'linkOptions' => [
//                 'data-method' => 'delete',
//                 'title' => Yii::t('app', 'Delete project'),
//                 'data-confirm' => Yii::t('app', 'Are you sure you wish to remove this project from the system?'),
//             ],
//         ],
//     ],
// ]);
echo ButtonGroup::widget([
    'buttons' => [
        [
            //'visible' => \Yii::$app->user->can(Permission::PERMISSION_DELETE, $id),
            'icon' => Icon::trash(),
            'type' => ButtonGroup::TYPE_DELETE_BUTTON,
            'label' => \Yii::t('app', 'Delete'),
            'endpoint' => [
                '/api/project/delete-project',
                'id' => $project->id,
            ],
            'redirect' => 'project/index',
            'confirm' => \Yii::t('app', 'Are you sure you wish to remove this project from the system?'),
            'title' => Yii::t('app', 'Delete project'),
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

// echo ButtonGroup::widget([
//     'buttons' => [
//         [
//             'icon' => Icon::trash(),
//             'label' => Yii::t('app', 'Delete all workspaces'),
//             'link' => [
//                 'project/delete-workspaces',
//                 'id' => $project->id,
//             ],
//             'style' => ButtonGroup::STYLE_DELETE,
//             'linkOptions' => [
//                 'data-method' => 'delete',
//                 'title' => Yii::t('app', 'Delete all workspaces in project'),
//                 'data-confirm' => Yii::t('app', 'Are you sure you wish to remove the workspaces from the system?'),
//             ],
//         ],
//     ],
// ]);
echo ButtonGroup::widget([
    'buttons' => [
        [
            //'visible' => \Yii::$app->user->can(Permission::PERMISSION_DELETE, $id),
            'icon' => Icon::trash(),
            'type' => ButtonGroup::TYPE_DELETE_BUTTON,
            'label' => \Yii::t('app', 'Delete all workspaces'),
            'endpoint' => [
                '/api/project/delete-workspaces',
                'id' => $project->id,
            ],
            'redirect' => 'project/index',
            'confirm' => Yii::t('app', 'Are you sure you wish to remove the workspaces from the system?'),
            'title' => Yii::t('app', 'Delete all workspaces in project'),
        ],
    ],
]);
Section::end();
