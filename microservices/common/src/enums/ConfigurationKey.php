<?php

declare(strict_types=1);

namespace herams\common\enums;

enum ConfigurationKey: string
{
    case UpdateWorkspaceSurveyId = 'updateWorkspaceSurveyId';

    case CreateProjectSurveyId = 'createProjectSurveyId';

    case UpdateProjectSurveyId = 'updateProjectSurveyId';

    case CreateWorkspaceSurveyId = 'createWorkspaceSurveyId';

    case Locales = 'locales';
}
