<?php
declare(strict_types=1);

namespace prime\interfaces\facility;

use prime\values\FacilityId;
use prime\values\WorkspaceId;

interface ForBreadcrumb
{
    public function getId(): FacilityId;
    public function getTitle(): string;
    public function getWorkspaceId(): WorkspaceId;
}
