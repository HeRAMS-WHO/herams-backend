<?php

declare(strict_types=1);

namespace prime\interfaces\workspace;

use prime\objects\LanguageSet;
use prime\values\ProjectId;
use prime\values\SurveyId;
use prime\values\WorkspaceId;

interface WorkspaceForNewOrUpdateFacilityInterface
{
    public function getAdminSurveyId(): SurveyId;
    public function getId(): WorkspaceId;
    public function getLanguages(): LanguageSet;
    public function getProjectId(): ProjectId;
    public function getProjectTitle(): string;
    public function getTitle(): string;
}
