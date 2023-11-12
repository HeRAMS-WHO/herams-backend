<?php

declare(strict_types=1);

use prime\components\View;
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

$this->title = 'View Admin Situation';


$surveyConfig = $survey->getConfig();

Section::begin();
$surveyJS = new Survey();
$surveyJS->withConfig($survey->getConfig())
    ->withDataRoute([
        '/api/facility/view-situation',
        'id' => $cid,
    ], ['data'])
    ->withProjectId($projectId)
    ->inDisplayMode()->setSurveySettings();

$surveySettings = $surveyJS->getSurveySettings();

?>
    <!-- Mount point for the React component -->
    <div id="ViewAdminSituation" data-config="<?= Html::encode(base64_encode($surveySettings)) ?>">
    </div>
<?php

Section::end();
