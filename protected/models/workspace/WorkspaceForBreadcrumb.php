<?php
declare(strict_types=1);

namespace prime\models\workspace;

use prime\interfaces\AccessCheckInterface;
use prime\interfaces\workspace\WorkspaceForBreadcrumbInterface;
use prime\models\ar\Project;
use prime\models\ar\Workspace;
use prime\objects\LanguageSet;
use prime\traits\BreadcrumbTrait;
use prime\traits\CanCurrentUser;
use prime\values\ProjectId;
use prime\values\WorkspaceId;

class WorkspaceForBreadcrumb implements WorkspaceForBreadcrumbInterface
{
    use BreadcrumbTrait;

    private ProjectId $projectId;

    public function __construct(
        Workspace $model
    ) {
        $this->label = $model->title;
        $this->projectId = new ProjectId($model->tool_id);
        $this->url = ['/workspace/responses', 'id' => $model->id];
    }

    public function getProjectId(): ProjectId
    {
        return $this->projectId;
    }
}
