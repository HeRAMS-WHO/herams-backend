<?php
declare(strict_types=1);

namespace prime\interfaces\response;

use prime\values\FacilityId;
use prime\values\ResponseId;
use prime\values\WorkspaceId;

interface ForBreadcrumb
{
    public function getId(): ResponseId;
    public function getTitle(): string;
    public function getWorkspaceId(): WorkspaceId;
}
