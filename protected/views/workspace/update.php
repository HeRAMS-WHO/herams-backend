<?php

declare(strict_types=1);

use herams\common\models\Permission;
use herams\common\values\WorkspaceId;
use prime\components\View;
use prime\helpers\Icon;
use prime\interfaces\survey\SurveyForSurveyJsInterface;
use prime\interfaces\WorkspaceForTabMenu;
use prime\widgets\ButtonGroup;
use prime\widgets\menu\WorkspaceTabMenu;
use prime\widgets\Section;
use prime\widgets\survey\Survey;
use yii\bootstrap\Html;

/**
 * @var WorkspaceId $workspaceId
 * @var WorkspaceForTabMenu $tabMenuModel
 * @var SurveyForSurveyJsInterface $survey
 * @var View $this
 * @var null|object $model
 */
assert($this instanceof View);


$this->params['subject'] = $tabMenuModel->title();
$this->title = \Yii::t('app', "Settings");
$this->beginBlock('tabs');
echo WorkspaceTabMenu::widget([
    'workspace' => $tabMenuModel,
]);
$this->endBlock();

Section::begin()
    ->withSubject($workspaceId)
    ;

$survey = Survey::begin()
    ->withConfig($survey->getConfig())
    ->withProjectId($tabMenuModel->projectId())
    ->withDataRoute([
        '/api/workspace/view',
        'id' => $workspaceId,
    ])
    ->withExtraData([
        'id' => $workspaceId,
    ])
    ->withSubmitRoute([
        '/api/workspace/update',
        'id' => $workspaceId,
    ])
    ->withServerValidationRoute([
        '/api/workspace/validate',
        'id' => $workspaceId,
    ])
;

Survey::end();

Section::end();

Section::begin()
    ->withHeader(\Yii::t('app', 'Delete workspace'))
    ->forDangerousAction();

echo Html::tag('p', \Yii::t('app', 'This will permanently delete the workspace.'));
echo Html::tag('p', \Yii::t('app', 'This action cannot be undone.'));
echo Html::tag('p', Html::tag('em', \Yii::t('app', 'Are you ABSOLUTELY SURE you wish to delete this workspace?')));

echo ButtonGroup::widget([
    'buttons' => [
        [
            'visible' => \Yii::$app->user->can(Permission::PERMISSION_DELETE, $workspaceId),
            'icon' => Icon::trash(),
            'type' => ButtonGroup::TYPE_DELETE_BUTTON,
            'label' => \Yii::t('app', 'Delete'),
            'endpoint' => [
                'api/workspace/delete',
                'id' => $workspaceId,
            ],
            'redirect' => [
                'project/workspaces',
                'id' => $tabMenuModel->projectId()->getValue(),
            ],
            'confirm' => \Yii::t('app', 'Are you sure you wish to remove this workspace from the system?'),
            'title' => \Yii::t('app', 'Delete workspace'),
        ],
    ],
]);
Section::end();
