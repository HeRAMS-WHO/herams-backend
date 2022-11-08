<?php

declare(strict_types=1);

namespace herams\common\domain\variableSet;

use herams\common\domain\page\PageRepository;
use herams\common\domain\project\ProjectRepository;
use herams\common\domain\survey\SurveyRepository;
use herams\common\interfaces\HeramsVariableSetRepositoryInterface;
use herams\common\values\PageId;
use herams\common\values\ProjectId;
use prime\helpers\HeramsVariableSet;

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
