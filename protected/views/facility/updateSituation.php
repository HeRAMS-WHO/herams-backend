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

$this->title = Yii::t('app', 'Create update Situation');

$this->beginBlock('tabs');
echo FacilityTabMenu::widget(
    [
        'facility' => $tabMenuModel,
    ]
);
$this->endBlock();

Section::begin()
    ->withHeader(Yii::t('app', 'Update Situation'));

$surveyJS = new Survey();
$surveyJS->withConfig($survey->getConfig())
    ->withDataRoute([
        '/api/facility/latest-situation',
        'id' => $facilityId,
    ], ['data'])
    ->withExtraData([
        'facilityId' => $facilityId,
        'surveyId' => $survey->getId(),
        'response_type' => 'situation',
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
        'facility/responses',
        'id' => $facilityId,
    ])
    ->withServerValidationRoute([
        'api/facility/validate-situation',
        'id' => $facilityId,

    ])
    ->deleteDate()
    ->setSurveySettings();

$settings = $surveyJS->getSurveySettings();
$deleteData = $surveyJS->getHaveToDeleteData();

?>
    <div id="UpdateSituation" data-survey-settings="<?= Html::encode(base64_encode($settings)) ?>"
         data-have-to-delete-date="<?= $deleteData ?>"
    >
    </div>
<?php


Section::end();
// $url = Url::to(['/api/facility/validate-situation', 'id' => $facilityId]);
// $script = <<< JS
//     $( document ).ready(function() {
//         $(document).on("change", "input[type=date]", function(){
//          console.log($(this).val());
//          console.log('name');
//          console.log($(this).attr('name'));
//             uri = '$url';
//             $.ajax({
//             method: "POST",
//             url: uri,
//             mode: 'cors',
//             cache: 'no-cache',
//             credentials: 'same-origin',
//             data:JSON.stringify({
//                 "response_type":"situation",
//                 "date_of_update": $(this).val()
//             }),
//             headers: {
//                 'X-CSRF-Token': window.yii.getCsrfToken(),
//                 Accept: 'application/json;indent=2',
//                 'Accept-Language': document.documentElement.lang ?? 'en',
//                 'Content-Type': 'application/json',
//             },
//             success: function(data){
//                 console.log('Success');
//                 if (data.data){
//                     $(this)
//                 }

//             },
//             error: function (data) {
//                 console.log('An error occurred.');
//             },
//             });
//         });

//     });
// JS;
// $this->registerJs($script);
