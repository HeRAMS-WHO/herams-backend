<?php

declare(strict_types=1);

namespace prime\interfaces\facility;

use prime\interfaces\BreadcrumbInterface;
use prime\values\WorkspaceId;

interface FacilityForBreadcrumbInterface extends BreadcrumbInterface
{
    public function getWorkspaceId(): WorkspaceId;
}
