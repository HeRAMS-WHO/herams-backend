<?php

declare(strict_types=1);

namespace prime\jobHandlers;

use prime\helpers\SurveyParser;
use prime\jobs\UpdateFacilityDataJob;
use prime\repositories\FacilityRepository;
use prime\repositories\SurveyRepository;
use prime\repositories\SurveyResponseRepository;
use prime\values\WorkspaceId;

class UpdateFacilityDataHandler
{
    public function __construct(
        private SurveyParser $surveyParser,
        private SurveyRepository $surveyRepository,
        private SurveyResponseRepository $surveyResponseRepository,
        private FacilityRepository $facilityRepository,
    ) {
    }

    public function handle(UpdateFacilityDataJob $job): void
    {
        $facility = $this->facilityRepository->retrieveActiveRecord($job->facilityId);

        $adminData = $this->surveyResponseRepository->getLatestAdminResponseForFacility($job->facilityId);
        if (! isset($facility, $adminData)) {
            return;
        }
        $data = $this->surveyResponseRepository->getLatestDataResponseForFacility($job->facilityId);

        $workspaceId = new WorkspaceId($this->facilityRepository->retrieveActiveRecord($job->facilityId)->workspace_id);
        $adminSurveyId = $this->surveyRepository->retrieveAdminSurveyForWorkspaceForSurveyJs($workspaceId)->getId();
        $dataSurveyId = $this->surveyRepository->retrieveDataSurveyForWorkspaceForSurveyJs($workspaceId)->getId();
        $variableSet = $this->surveyRepository->retrieveVariableSet($adminSurveyId, $dataSurveyId);

        $nameVariable = $variableSet->getVariable('name');
        $facility->name = $nameVariable->getDisplayValue($adminData)->getRawValue();

        $facility->admin_data = $adminData->allData();

        $facility->data = $data?->allData();

        if (! $facility->save()) {
            throw new \Exception(print_r([
                'message' => 'failed to save',
                'errors' => $facility->errors,
                'values' => $facility->attributes,
            ], true));
        }
    }
}
