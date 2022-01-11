<?php

declare(strict_types=1);

namespace prime\controllers\facility;

use prime\interfaces\AccessCheckInterface;
use prime\objects\enums\ProjectType;
use prime\repositories\FacilityRepository;
use prime\repositories\ResponseForLimesurveyRepository;
use prime\repositories\SurveyResponseRepository;
use prime\values\FacilityId;
use yii\base\Action;

class Responses extends Action
{
    public function run(
        FacilityRepository $facilityRepository,
        ResponseForLimesurveyRepository $responseForLimesurveyRepository,
        SurveyResponseRepository $surveyResponseRepository,
        string $id
    ) {
        $facilityId = new FacilityId($id);
        $facility = $facilityRepository->retrieveForTabMenu($facilityId);

        if ($facilityRepository->isOfProjectType($facilityId, ProjectType::limesurvey())) {
            $dataProvider = $responseForLimesurveyRepository->searchInFacility($facilityId);
            $updateSituationUrl = ['copy-latest-response', 'id' => $facility->getId()];
        } else {
            $dataProvider = $surveyResponseRepository->searchDataInFacility($facilityId);
            $updateSituationUrl = ['update-situation', 'id' => $facility->getId()];
        }

        return $this->controller->render(
            'responses',
            [
                'responseProvider' => $dataProvider,
                'facility' => $facility,
                'updateSituationUrl' => $updateSituationUrl,
            ]
        );
    }
}
