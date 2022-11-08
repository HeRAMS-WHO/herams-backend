<?php

declare(strict_types=1);

namespace prime\interfaces\workspace;

use herams\common\values\ProjectId;
use herams\common\values\SurveyId;
use herams\common\values\WorkspaceId;
use prime\objects\LanguageSet;

interface WorkspaceForNewOrUpdateFacilityInterface
{
    public function getAdminSurveyId(): SurveyId;

    public function getId(): WorkspaceId;

    public function getLanguages(): LanguageSet;

    public function getProjectId(): ProjectId;

    public function getProjectTitle(): string;

    public function getTitle(): string;
}
