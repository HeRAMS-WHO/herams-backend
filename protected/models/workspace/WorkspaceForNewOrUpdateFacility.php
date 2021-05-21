<?php
declare(strict_types=1);

namespace prime\models\workspace;

use prime\values\ProjectId;
use prime\values\WorkspaceId;

final class WorkspaceForNewOrUpdateFacility implements \prime\interfaces\WorkspaceForNewOrUpdateFacility
{

    public function __construct(
        private WorkspaceId $id,
        private string $title,
        private ProjectId $projectId,
        private string $projectTitle
    ) {
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
}
