<?php

declare(strict_types=1);

namespace prime\controllers\facility;

use prime\components\LimesurveyDataProvider;
use prime\objects\enums\ProjectType;
use prime\repositories\FacilityRepository;
use prime\repositories\ResponseForLimesurveyRepository;
use prime\values\FacilityId;
use yii\base\Action;

/*
 * We have 2 routes for updating the situation:
 * - copy-latest-response for limesurvey
 * - update-situation for surveyJs
 *
 * TODO Limesurvey deprecation: remove action
 */
class CopyLatestResponse extends Action
{
    public function run(
        FacilityRepository $facilityRepository,
        LimesurveyDataProvider $limesurveyDataProvider,
        ResponseForLimesurveyRepository $responseRepository,
        string $id
    ) {
        $facilityId = new FacilityId($id);

        if ($facilityRepository->isOfProjectType($facilityId, ProjectType::limesurvey())) {
            $facility = $facilityRepository->retrieveForResponseCopy($facilityId);
            $currentResponse = $responseRepository->retrieveForSurvey($facility->getLastResponseId());
            // Post to LS so it duplicates the response.
            $newExternalId = $limesurveyDataProvider->copyResponse($currentResponse->getExternalResponseId());

            $newInternalId = $responseRepository->duplicate($currentResponse->getId());

            $responseRepository->updateExternalId($newInternalId, $newExternalId);
        } else {
            return $this->controller->redirect(['update-situation', 'id' => $id]);
        }
        return $this->controller->redirect(['responses', 'id' => $facilityId]);
    }
}
