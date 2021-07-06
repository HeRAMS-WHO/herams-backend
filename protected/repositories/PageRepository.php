<?php
declare(strict_types=1);

namespace prime\repositories;

use prime\interfaces\AccessCheckInterface;
use prime\interfaces\page\PageForBreadcrumbInterface as ForBreadcrumbInterface;
use prime\models\ar\Page;
use prime\models\ar\Permission;
use prime\models\pages\PageForBreadcrumb;
use prime\values\PageId;

class PageRepository
{
    public function __construct(
        private AccessCheckInterface $accessCheck,
    ) {
    }

    public function retrieveForBreadcrumb(PageId $id): ForBreadcrumbInterface
    {
        $record = Page::findOne(['id' => $id]);
        return new PageForBreadcrumb($record);
    }
}
