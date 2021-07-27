<?php
declare(strict_types=1);

namespace prime\interfaces\response;

use prime\interfaces\BreadcrumbInterface;
use prime\values\WorkspaceId;

interface ResponseForBreadcrumbInterface extends BreadcrumbInterface
{
    public function getWorkspaceId(): WorkspaceId;
}
