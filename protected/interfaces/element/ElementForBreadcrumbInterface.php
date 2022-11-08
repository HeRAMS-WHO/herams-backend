<?php

declare(strict_types=1);

namespace prime\interfaces\element;

use herams\common\values\PageId;
use prime\interfaces\BreadcrumbInterface;

interface ElementForBreadcrumbInterface extends BreadcrumbInterface
{
    public function getPageId(): PageId;
}
