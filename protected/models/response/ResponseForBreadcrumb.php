<?php
declare(strict_types=1);

namespace prime\models\response;

use prime\interfaces\response\ResponseForBreadcrumbInterface;
use prime\models\ar\Response;
use prime\traits\BreadcrumbTrait;
use prime\values\WorkspaceId;

class ResponseForBreadcrumb implements ResponseForBreadcrumbInterface
{
    use BreadcrumbTrait;

    private WorkspaceId $workspaceId;

    public function __construct(Response $model)
    {
        $this->workspaceId = new WorkspaceId($model->workspace_id);
        $this->label = $model->getName();
        $this->url = ['/response/compare', 'id' => $model->id];
    }

    public function getWorkspaceId(): WorkspaceId
    {
        return $this->workspaceId;
    }
}
