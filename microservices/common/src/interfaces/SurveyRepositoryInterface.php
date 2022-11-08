<?php

declare(strict_types=1);

namespace herams\common\interfaces;

use herams\common\values\WorkspaceId;
use prime\interfaces\survey\SurveyForSurveyJsInterface;

interface SurveyRepositoryInterface
{
    public function retrieveAdminSurveyForWorkspaceForSurveyJs(WorkspaceId $workspaceId): SurveyForSurveyJsInterface;

    public function retrieveDataSurveyForWorkspaceForSurveyJs(WorkspaceId $workspaceId): SurveyForSurveyJsInterface;
}
