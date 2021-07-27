<?php
declare(strict_types=1);

namespace prime\controllers\facility;

use prime\components\LimesurveyDataProvider;
use prime\repositories\FacilityRepository;
use prime\repositories\ResponseRepository;
use prime\values\FacilityId;
use yii\base\Action;

class CopyLatestResponse extends Action
{

    public function run(
        FacilityRepository $facilityRepository,
        LimesurveyDataProvider $limesurveyDataProvider,
        ResponseRepository $responseRepository,
        string $id
    ) {
        $facilityId = new FacilityId($id);
        $facility = $facilityRepository->retrieveForResponseCopy($facilityId);

        $currentResponse = $responseRepository->retrieveForSurvey($facility->getLastResponseId());

        if ($currentResponse->usesLimeSurvey()) {
            // Post to LS so it duplicates the response.
            $newExternalId = $limesurveyDataProvider->copyResponse($currentResponse->getExternalResponseId());

            $newInternalId = $responseRepository->duplicate($currentResponse->getId());

            $responseRepository->updateExternalId($newInternalId, $newExternalId);
        } else {
            $newInternalId = $responseRepository->duplicate($currentResponse->getId());
        }
        return $this->controller->redirect(['responses', 'id' => $facilityId]);
    }
}
