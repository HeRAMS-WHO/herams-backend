<?php

declare(strict_types=1);

namespace herams\common\jobHandlers;

use herams\common\domain\facility\FacilityRepository;
use herams\common\domain\facility\FacilityTier;
use herams\common\domain\project\ProjectRepository;
use herams\common\domain\survey\SurveyRepository;
use herams\common\domain\surveyResponse\SurveyResponseRepository;
use herams\common\domain\workspace\WorkspaceRepository;
use herams\common\helpers\surveyjs\SurveyParser;
use herams\common\jobs\UpdateFacilityDataJob;
use herams\common\values\WorkspaceId;

final class UpdateFacilityDataHandler
{
    public function __construct(
        private SurveyParser $surveyParser,
        private SurveyRepository $surveyRepository,
        private WorkspaceRepository $workspaceRepository,
        private ProjectRepository $projectRepository,
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
        $projectId = $this->workspaceRepository->getProjectId($workspaceId);

        $adminSurveyId = $this->projectRepository->retrieveAdminSurveyId($projectId);
        var_dump($adminSurveyId);
        $dataSurveyId = $this->projectRepository->retrieveDataSurveyId($projectId);

        $variableSet = $this->surveyRepository->retrieveVariableSet($adminSurveyId, $dataSurveyId);

        $tierVariable = $variableSet->getFacilityTierVariable();
        if (isset($tierVariable)) {
            $tier = $tierVariable->getValue($data);
            if ($tier !== FacilityTier::Unknown) {
                $facility->tier = $tier->value;
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
