<?php
declare(strict_types=1);

namespace prime\interfaces\workspace;

use prime\values\ProjectId;
use prime\values\WorkspaceId;

interface ForBreadcrumb
{
    public function getId(): WorkspaceId;
    public function getProjectId(): ProjectId;
    public function getTitle(): string;
}
