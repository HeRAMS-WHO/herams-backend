<?php

declare(strict_types=1);

use prime\assets\ReactAsset;
use prime\components\View;
use prime\widgets\Section;
use prime\widgets\survey\Survey;
use yii\helpers\Html;

/**
 * @var View $this
 * @var \prime\interfaces\survey\SurveyForSurveyJsInterface $survey
 * @var \herams\common\values\ProjectId $projectId
 * @var \herams\common\values\WorkspaceId $workspaceId
 */
ReactAsset::register($this);

$this->title = Yii::t('app', 'Create facility');

Section::begin()
    ->withHeader($this->title);
$surveyJS = new Survey();
$surveyJS->withConfig($survey->getConfig())
    ->withProjectId($projectId)
    ->withExtraData([
        'workspaceId' => $workspaceId,
    ])
    ->withSubmitRoute([
        '/api/facility/create',
    ])
    ->withServerValidationRoute([
        '/api/facility/validate',
        'workspace_id' => $workspaceId,
    ])
    ->withRedirectRoute([
        '/workspace/facilities',
        'id' => $workspaceId,
    ])->setSurveySettings();
$surveySettings = $surveyJS->getSurveySettings();
?>
    <div id="CreateFacility" data-survey-settings="<?= Html::encode(base64_encode($surveySettings)) ?>"
        >
    </div>
<?php

Section::end();
