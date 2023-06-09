<?php

declare(strict_types=1);

use herams\common\models\Permission;
use herams\common\values\FacilityId;
use prime\components\View;
use prime\helpers\Icon;
use prime\interfaces\FacilityForTabMenu;
use prime\models\forms\facility\UpdateForm;
use prime\widgets\ButtonGroup;
use prime\widgets\menu\FacilityTabMenu;
use prime\widgets\Section;
use prime\widgets\survey\Survey;
use yii\bootstrap\Html;

/**
 * @var View $this
 * @var UpdateForm $model
 * @var FacilityForTabMenu $tabMenuModel
 * @var FacilityId $id
 * @var \herams\common\values\ProjectId $projectId
 * @var \prime\interfaces\survey\SurveyForSurveyJsInterface $survey
 */
$style = <<< CSS

 
   .dangerous{  
        width: 400px;
        height: 300px !important;
        display: block !important;
        text-align: left;
        line-height: 2.5;
        margin: auto;
        flex-grow: unset !important;
        margin-top: 30px;
   }

 CSS;
$this->registerCss($style);


//$this->title = \Yii::t('app', 'Facility settings');
$this->title = $tabMenuModel->getTitle();

$this->beginBlock('tabs');
echo FacilityTabMenu::widget(
    [
        'facility' => $tabMenuModel,
    ]
);
$this->endBlock();

// Section::begin()
//     ->withHeader(Yii::t('app', 'Update facility'));

// $survey = Survey::begin()
//     ->withConfig($survey->getConfig())
//     ->withDataRoute([
//         '/api/facility/view',
//         'id' => $id,
//     ], ['admin_data'])
//     ->withProjectId($projectId)
//     ->withExtraData([
//         'facilityId' => $id,
//         'surveyId' => $survey->getId(),
//     ])
//     ->withSubmitRoute([
//         'api/survey-response/create',
//     ])
//     ->withRedirectRoute([
//         'facility/admin-responses',
//         'id' => $id,
//     ])
// ;

// Survey::end();

// Section::end();

Section::begin()
    ->withHeader(\Yii::t('app', 'Delete HSDU'))
    ->forDangerousAction();

echo Html::tag('p', \Yii::t('app', 'This will permanently delete the HSDU.'));
echo Html::tag('p', \Yii::t('app', 'This action cannot be undone.'));
echo Html::tag('p', Html::tag('em', \Yii::t('app', 'Are you ABSOLUTELY SURE you wish to delete this HSDU?')));
//echo Html::a(Icon::trash().\Yii::t('app', 'Delete'), '#', ['data-action' => 'delete','class' => 'btn btn-delete', 'data-confirm' => 'Are you sure you wish to remove this Health Facility from the system?', 'data-redirect' => '', 'id' => 'delete', 'data-herams-endpoint' => '#test']);

echo ButtonGroup::widget([
    'buttons' => [
        [
            //'visible' => \Yii::$app->user->can(Permission::PERMISSION_DELETE, $id),
            'icon' => Icon::trash(),
            'type' => ButtonGroup::TYPE_DELETE_BUTTON,
            'label' => \Yii::t('app', 'Delete'),
            'endpoint' => [
                '/api/facility/delete-facility',
                'id' => $id,
            ],
            'redirect' => [
                '/workspace/facilities',
                'id' => $workspaceId,
            ],
            'confirm' => \Yii::t('app', 'Are you sure you wish to remove this HSDU from the system?'),
            'title' => \Yii::t('app', 'Delete HSDU'),
        ],
    ],
]);
Section::end();
