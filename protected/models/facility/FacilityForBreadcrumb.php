<?php

declare(strict_types=1);

namespace prime\models\facility;

use prime\interfaces\facility\FacilityForBreadcrumbInterface;
use prime\models\ar\Facility;
use prime\models\ar\ResponseForLimesurvey;
use prime\traits\BreadcrumbTrait;
use prime\values\WorkspaceId;

class FacilityForBreadcrumb implements FacilityForBreadcrumbInterface
{
    use BreadcrumbTrait;

    private WorkspaceId $workspaceId;

    public function __construct(
        Facility|ResponseForLimesurvey $model
    ) {
        if ($model instanceof ResponseForLimesurvey) {
            $this->label = (string) $model->hf_id;
            $this->url = ['/facility/responses', 'id' => $model->hf_id];
            $this->workspaceId = new WorkspaceId($model->workspace_id);
        } elseif ($model instanceof Facility) {
            $this->label = $model->name;
            $this->url = ['/facility/responses', 'id' => $model->id];
            $this->workspaceId = new WorkspaceId($model->workspace_id);
        }
    }

    public function getWorkspaceId(): WorkspaceId
    {
        return $this->workspaceId;
    }
}
