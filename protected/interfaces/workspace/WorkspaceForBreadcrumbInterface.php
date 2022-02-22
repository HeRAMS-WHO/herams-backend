<?php

declare(strict_types=1);

namespace prime\interfaces\workspace;

use prime\interfaces\BreadcrumbInterface;
use prime\values\ProjectId;

interface WorkspaceForBreadcrumbInterface extends BreadcrumbInterface
{
    public function getProjectId(): ProjectId;
}
