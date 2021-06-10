<?php
declare(strict_types=1);

namespace prime\models\workspace;

use prime\interfaces\AccessCheckInterface;
use prime\models\ar\Workspace;
use prime\objects\LanguageSet;
use prime\traits\CanCurrentUser;
use prime\values\ProjectId;
use prime\values\WorkspaceId;

final class WorkspaceForTabMenu implements \prime\interfaces\WorkspaceForTabMenu
{
    use CanCurrentUser;

    private WorkspaceId $id;
    private string $title;
    private ProjectId $projectId;
    private string $projectTitle;
    private LanguageSet $languages;
    private int $facilityCount;
    private int $responseCount;
    private int $permissionSourceCount;

    public function __construct(
        private AccessCheckInterface $accessCheck,
        private Workspace $model
    ) {
        $this->id = new WorkspaceId($model->id);
        $this->title = $model->title;
        $this->projectId = new ProjectId($model->tool_id);
        $this->projectTitle = $model->project->title;
        $this->responseCount = $model->responseCount;
        $this->facilityCount = $model->facilityCount;
        $this->permissionSourceCount = $model->permissionSourceCount;
    }

    public function id(): WorkspaceId
    {
        return $this->id;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function projectId(): ProjectId
    {
        return $this->projectId;
    }

    public function projectTitle(): string
    {
        return $this->projectTitle;
    }

    public function languages(): LanguageSet
    {
        return $this->languages;
    }

    public function getFacilityCount(): int
    {
        return $this->facilityCount;
    }

    public function getResponseCount(): int
    {
        return $this->responseCount;
    }

    public function getPermissionSourceCount(): int
    {
        return $this->permissionSourceCount;
    }

    private function getModel(): object
    {
        return $this->model;
    }
}
