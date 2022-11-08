<?php

declare(strict_types=1);

namespace prime\interfaces\page;

use herams\common\values\ProjectId;
use prime\interfaces\BreadcrumbInterface;

interface PageForBreadcrumbInterface extends BreadcrumbInterface
{
    public function getProjectId(): ProjectId;
}
