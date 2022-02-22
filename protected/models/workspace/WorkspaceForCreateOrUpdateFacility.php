<?php

declare(strict_types=1);

namespace prime\models\workspace;

use prime\interfaces\workspace\WorkspaceForNewOrUpdateFacilityInterface;
use prime\objects\LanguageSet;
use prime\values\ProjectId;
use prime\values\SurveyId;
use prime\values\WorkspaceId;

/**
 * @codeCoverageIgnore Since all functions are simple getters
 */
final class WorkspaceForCreateOrUpdateFacility implements WorkspaceForNewOrUpdateFacilityInterface
{
    public function __construct(
        private SurveyId $adminSurveyId,
        private WorkspaceId $id,
        private LanguageSet $languages,
        private ProjectId $projectId,
        private string $projectTitle,
        private string $title,
    ) {
        if (count($languages) === 0) {
            throw new \Exception('Languages must not be empty');
        }
    }

    public function getAdminSurveyId(): SurveyId
    {
        return $this->adminSurveyId;
    }

    public function getId(): WorkspaceId
    {
        return $this->id;
    }

    public function getLanguages(): LanguageSet
    {
        return $this->languages;
    }

    public function getProjectId(): ProjectId
    {
        return $this->projectId;
    }

    public function getProjectTitle(): string
    {
        return $this->projectTitle;
    }

    public function getTitle(): string
    {
        return $this->title;
    }
}
