<?php

declare(strict_types=1);

namespace prime\interfaces;

use prime\interfaces\survey\SurveyForSurveyJsInterface;
use prime\values\WorkspaceId;

interface SurveyRepositoryInterface
{
    public function retrieveAdminSurveyForWorkspaceForSurveyJs(WorkspaceId $workspaceId): SurveyForSurveyJsInterface;

    public function retrieveDataSurveyForWorkspaceForSurveyJs(WorkspaceId $workspaceId): SurveyForSurveyJsInterface;
}
