<?php

declare(strict_types=1);

use prime\assets\ReactAsset;
use prime\components\View;
use prime\helpers\Icon;
use prime\interfaces\FacilityForTabMenu;
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
//$this->params['subject'] = Icon::healthFacility() . $tabMenuModel->getTitle();
$this->title = 'Edit Situation';


$surveyConfig = $survey->getConfig();

Section::begin()
    ->withHeader(Yii::t('app', 'Edit Situation'));
$surveyJS = new Survey();
$surveyJS->withConfig($surveyConfig)
    ->withDataRoute([
        '/api/facility/view-situation',
        'id' => $cid,
    ], ['data'])
    ->withExtraData([
        'surveyResponseId' => $surveyResponseId,
        'facilityId' => $facilityId,
        'response_type' => 'situation',
        'response_id' => $cid, //respose id for validation
    ])
    ->withSubmitRoute([
        'edit-situation',
        'id' => $facilityId,
    ])
    ->withProjectId($projectId)
    ->withSubmitRoute([
        'api/facility/save-situation',
        'id' => $cid,
    ])
    ->withRedirectRoute([
        'facility/responses',
        'id' => $facilityId,
    ])
    ->withServerValidationRoute([
        'api/facility/validate-situation',
        'id' => $facilityId,

    ])->setSurveySettings();
$surveySettings = $surveyJS->getSurveySettings();

?>
    <div id="EditSituation" data-survey-settings="<?= Html::encode(base64_encode($surveySettings)) ?>"
    >
    </div>
<?php

Section::end();
