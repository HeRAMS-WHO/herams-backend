<?php

declare(strict_types=1);

namespace prime\models\workspace;

use herams\common\interfaces\AccessCheckInterface;
use herams\common\models\Workspace;
use herams\common\values\ProjectId;
use herams\common\values\WorkspaceId;
use prime\traits\CanCurrentUser;

final class WorkspaceForTabMenu implements \prime\interfaces\WorkspaceForTabMenu
{
    use CanCurrentUser;

    private WorkspaceId $id;

    private ProjectId $projectId;

    public function __construct(
        private AccessCheckInterface $accessCheck,
        private Workspace $model
    ) {
        if ($model->isNewRecord) {
            throw new \InvalidArgumentException('Record must not be a new record');
        }
        $this->id = new WorkspaceId($model->id);
        $this->projectId = new ProjectId($model->project_id);
    }

    public function id(): WorkspaceId
    {
        return $this->id;
    }

    public function title(): string
    {
        return $this->model->title;
    }

    public function projectId(): ProjectId
    {
        return $this->projectId;
    }

    public function projectTitle(): string
    {
        return $this->model->project->title;
    }

    public function getFacilityCount(): int
    {
        return $this->model->facilityCount;
    }

    public function getPermissionSourceCount(): int
    {
        return $this->model->permissionSourceCount;
    }

    private function getModel(): Workspace
    {
        return $this->model;
    }
}
