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
use yii\base\InvalidArgumentException;
use yii\web\ForbiddenHttpException;
use yii\web\HttpException;

class AdminResponses extends Action
{
    public function run(
        FacilityRepository $facilityRepository,
        SurveyResponseRepository $surveyResponseRepository,
        string $id
    ) {
        $facilityId = new FacilityId($id);
        $facility = $facilityRepository->retrieveForTabMenu($facilityId);

        if ($facilityRepository->isOfProjectType($facilityId, ProjectType::limesurvey())) {
            throw new ForbiddenHttpException('Limesurvey projects do not have admin responses.');
        } else {
            $dataProvider = $surveyResponseRepository->searchAdminInFacility($facilityId);
        }

        return $this->controller->render(
            'admin-responses',
            [
                'responseProvider' => $dataProvider,
                'facility' => $facility
            ]
        );
    }
}
