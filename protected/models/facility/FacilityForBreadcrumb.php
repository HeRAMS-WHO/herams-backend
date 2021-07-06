<?php
declare(strict_types=1);

namespace prime\models\facility;

use prime\interfaces\facility\FacilityForBreadcrumbInterface;
use prime\models\ar\Facility;
use prime\models\ar\Response;
use prime\traits\BreadcrumbTrait;
use prime\values\WorkspaceId;

class FacilityForBreadcrumb implements FacilityForBreadcrumbInterface
{
    use BreadcrumbTrait;

    private WorkspaceId $workspaceId;

    public function __construct(
        Facility|Response $model
    ) {
        if ($model instanceof Response) {
            $this->label = $model->hf_id;
            $this->url = ['/facility/responses', 'id' => $model->hf_id];
            $this->workspaceId = new WorkspaceId($model->workspace_id);
        } else {
            $this->label = $model->name;
            $this->url = ['/facility/responses', 'id' => $model->id];
            $this->workspaceId = new WorkspaceId($model->facility->workspace_id);
        }
    }

    public function getWorkspaceId(): WorkspaceId
    {
        return $this->workspaceId;
    }
}
