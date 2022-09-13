<?php

declare(strict_types=1);

namespace prime\models\facility;

use prime\interfaces\facility\FacilityForBreadcrumbInterface;
use prime\models\ar\Facility;
use prime\values\WorkspaceId;
use yii\helpers\Url;

class FacilityForBreadcrumb implements FacilityForBreadcrumbInterface
{
    private WorkspaceId $workspaceId;

    private string $url;

    private string $label;

    public function __construct(
        Facility $model
    ) {
        if ($model instanceof Facility) {
            $this->label = $model->name;
            $this->url = Url::to([
                '/facility/responses',
                'id' => $model->id,
            ]);
            $this->workspaceId = new WorkspaceId($model->workspace_id);
        }
    }

    public function getWorkspaceId(): WorkspaceId
    {
        return $this->workspaceId;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getUrl(): string|null
    {
        return $this->url;
    }
}
