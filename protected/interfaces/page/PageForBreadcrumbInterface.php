<?php

declare(strict_types=1);

namespace prime\interfaces\page;

use prime\interfaces\BreadcrumbInterface;
use prime\values\ProjectId;

interface PageForBreadcrumbInterface extends BreadcrumbInterface
{
    public function getProjectId(): ProjectId;
}
