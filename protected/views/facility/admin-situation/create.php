<?php

declare(strict_types=1);

use prime\assets\ReactAsset;
use prime\components\View;
use prime\interfaces\FacilityForTabMenu;
use prime\widgets\menu\FacilityTabMenu;
use prime\widgets\Section;
use prime\widgets\survey\Survey;
use yii\helpers\Html;

/**
 * @var View $this
 * @var \herams\common\values\ProjectId $projectId
 * @var FacilityForTabMenu $tabMenuModel
 * @var \prime\models\survey\SurveyForSurveyJs $survey
 * @var \herams\common\values\FacilityId $facilityId
 */
ReactAsset::register($this);

$this->title = $tabMenuModel->getTitle();

$this->beginBlock('tabs');
echo FacilityTabMenu::widget(
    [
        'facility' => $tabMenuModel,
    ]
);
$this->endBlock();

Section::begin()
    ->withHeader(Yii::t('app', 'Create Admin Situation'));
$surveyJS = new Survey();
$surveyJS->withConfig($survey->getConfig())
    ->withDataRoute([
        '/api/facility/latest-admin-situation',
        'id' => $facilityId,
    ], ['data'])
    ->withExtraData([
        'facilityId' => $facilityId,
        'surveyId' => $survey->getId(),
        'response_type' => 'admin',
    ])
    ->withSubmitRoute([
        'update-situation',
        'id' => $facilityId,
    ])
    ->withProjectId($projectId)
    ->withSubmitRoute([
        'api/survey-response/create',
    ])
    ->withRedirectRoute([
        'facility/admin-responses',
        'id' => $facilityId,
    ])
    ->withServerValidationRoute([
        'api/facility/validate-situation',
        'id' => $facilityId,

    ])
    ->deleteDate()
    ->setSurveySettings();
$surveySettings = $surveyJS->getSurveySettings();
$surveyHaveToDeleteData = $surveyJS->getHaveToDeleteData();

?>
    <!-- Mount point for the React component -->
    <div id="CreateAdminSituation" data-survey-settings="<?= Html::encode(base64_encode($surveySettings)) ?>" data-have-to-delete-date="<?= $surveyHaveToDeleteData ?>">
    </div>
<?php

Section::end();
