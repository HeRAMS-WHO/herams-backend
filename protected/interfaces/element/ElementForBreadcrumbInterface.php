<?php

declare(strict_types=1);

namespace prime\interfaces\element;

use prime\interfaces\BreadcrumbInterface;
use prime\values\PageId;

interface ElementForBreadcrumbInterface extends BreadcrumbInterface
{
    public function getPageId(): PageId;
}
