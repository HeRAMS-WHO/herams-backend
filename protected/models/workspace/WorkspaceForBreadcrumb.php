<?php
declare(strict_types=1);

namespace prime\models\workspace;

use prime\interfaces\workspace\WorkspaceForBreadcrumbInterface;
use prime\models\ar\Workspace;
use prime\traits\BreadcrumbTrait;
use prime\values\ProjectId;

class WorkspaceForBreadcrumb implements WorkspaceForBreadcrumbInterface
{
    use BreadcrumbTrait;

    private ProjectId $projectId;

    public function __construct(
        Workspace $model
    ) {
        $this->label = $model->title;
        $this->projectId = new ProjectId($model->project_id);
        $this->url = ['/workspace/responses', 'id' => $model->id];
    }

    public function getProjectId(): ProjectId
    {
        return $this->projectId;
    }
}
