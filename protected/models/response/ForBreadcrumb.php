<?php
declare(strict_types=1);

namespace prime\models\response;

use prime\models\ar\Response;
use prime\values\ResponseId;
use prime\values\WorkspaceId;

class ForBreadcrumb implements \prime\interfaces\response\ForBreadcrumb
{
    private ResponseId $id;
    private WorkspaceId $workspaceId;
    private string $title;

    public function __construct(Response $model)
    {
        $this->id = new ResponseId($model->id);
        $this->workspaceId = new WorkspaceId($model->workspace_id);
        $this->title = $model->hf_id;
    }

    public function getId(): ResponseId
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getWorkspaceId(): WorkspaceId
    {
        return $this->workspaceId;
    }
}
