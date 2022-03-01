<?php

declare(strict_types=1);

namespace prime\repositories;

use prime\helpers\HeramsVariableSet;
use prime\interfaces\HeramsVariableSetRepositoryInterface;
use prime\values\ProjectId;

class HeramsVariableSetRepository implements HeramsVariableSetRepositoryInterface
{
    public function __construct(
        private ProjectRepository $projectRepository,
        private SurveyRepository $surveyRepository,
    ) {
    }

    public function retrieveForProject(ProjectId $projectId): HeramsVariableSet
    {
        $project = $this->projectRepository->retrieveForExport($projectId);
        return $this->surveyRepository->retrieveForDashboarding($project->getAdminSurveyId(), $project->getDataSurveyId());
    }
}
