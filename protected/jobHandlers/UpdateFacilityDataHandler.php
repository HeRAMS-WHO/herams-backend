<?php

declare(strict_types=1);

namespace prime\jobHandlers;

use herams\common\domain\facility\FacilityRepository;
use herams\common\domain\survey\SurveyRepository;
use herams\common\domain\surveyResponse\SurveyResponseRepository;
use herams\common\helpers\surveyjs\FacilityTierVariable;
use herams\common\helpers\surveyjs\SurveyParser;
use herams\common\jobs\UpdateFacilityDataJob;
use herams\common\values\WorkspaceId;

final class UpdateFacilityDataHandler
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

        foreach ($variableSet->getVariables() as $variable) {
            if ($variable instanceof FacilityTierVariable) {
//                var_dump($variable->getValue($adminData));
//                die();
            }
        }

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
