<?php

declare(strict_types=1);

namespace prime\interfaces\response;

use herams\common\values\WorkspaceId;
use prime\interfaces\BreadcrumbInterface;

interface ResponseForBreadcrumbInterface extends BreadcrumbInterface
{
    public function getWorkspaceId(): WorkspaceId;
}
