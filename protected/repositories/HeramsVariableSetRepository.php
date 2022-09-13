<?php

declare(strict_types=1);

namespace prime\repositories;

use prime\helpers\HeramsVariableSet;
use prime\interfaces\HeramsVariableSetRepositoryInterface;
use prime\values\PageId;
use prime\values\ProjectId;

class HeramsVariableSetRepository implements HeramsVariableSetRepositoryInterface
{
    public function __construct(
        private ProjectRepository $projectRepository,
        private PageRepository $pageRepository,
        private SurveyRepository $surveyRepository,
    ) {
    }

    public function retrieveForProject(ProjectId $projectId): HeramsVariableSet
    {
        $adminSurveyId = $this->projectRepository->retrieveAdminSurveyId($projectId);
        $dataSurveyId = $this->projectRepository->retrieveDataSurveyId($projectId);
        return $this->surveyRepository->retrieveVariableSet($adminSurveyId, $dataSurveyId);
    }

    public function retrieveForPage(PageId $pageId): HeramsVariableSet
    {
        $projectId = $this->pageRepository->retrieveProjectId($pageId);

        return $this->retrieveForProject($projectId);
    }
}
