<?php

declare(strict_types=1);

namespace prime\jobHandlers;

use prime\jobs\UpdateFacilityDataJob;
use prime\repositories\FacilityRepository;
use prime\repositories\SurveyResponseRepository;

class UpdateFacilityDataHandler
{
    public function __construct(
        private SurveyResponseRepository $surveyResponseRepository,
        private FacilityRepository $facilityRepository,
    ) {
    }

    public function handle(UpdateFacilityDataJob $job): void
    {
        $adminData = $this->surveyResponseRepository->getLatestAdminResponseForFacility($job->facilityId);
        $data = $this->surveyResponseRepository->getLatestDataResponseForFacility($job->facilityId);

        $facility = $this->facilityRepository->retrieveActiveRecord($job->facilityId);
        $facility->updateAttributes([
            'admin_data' => isset($adminData) ? $adminData->asArray()['data'] : null,
            'data' => isset($data) ? $data->asArray()['data'] : null,
        ]);
    }
}
