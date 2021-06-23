<?php
declare(strict_types=1);

namespace prime\models\facility;

use prime\values\FacilityId;
use prime\values\WorkspaceId;

class ForBreadcrumb implements \prime\interfaces\facility\ForBreadcrumb
{
    public function __construct(
        private FacilityId $id,
        private string $title,
        private WorkspaceId $workspaceId,
    ) {
    }

    public function getId(): FacilityId
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
