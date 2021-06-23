<?php
declare(strict_types=1);

namespace prime\models\workspace;

use prime\interfaces\AccessCheckInterface;
use prime\models\ar\Project;
use prime\models\ar\Workspace;
use prime\objects\LanguageSet;
use prime\traits\CanCurrentUser;
use prime\values\ProjectId;
use prime\values\WorkspaceId;

/**
 * @codeCoverageIgnore Since all functions are simple getters
 */
class ForBreadcrumb implements \prime\interfaces\workspace\ForBreadcrumb
{
    private WorkspaceId $id;
    private ProjectId $projectId;
    private string $title;

    public function __construct(
        Workspace $model
    ) {
        $this->id = new WorkspaceId($model->id);
        $this->projectId = new ProjectId($model->tool_id);
        $this->title = $model->title;
    }

    public function getId(): WorkspaceId
    {
        return $this->id;
    }

    public function getProjectId(): ProjectId
    {
        return $this->projectId;
    }

    public function getTitle(): string
    {
        return $this->title;
    }
}
