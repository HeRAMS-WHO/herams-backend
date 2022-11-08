<?php

declare(strict_types=1);

namespace prime\interfaces\workspace;

use herams\common\values\ProjectId;
use prime\interfaces\BreadcrumbInterface;

interface WorkspaceForBreadcrumbInterface extends BreadcrumbInterface
{
    public function getProjectId(): ProjectId;
}
