<?php
declare(strict_types=1);

namespace prime\repositories;

use prime\interfaces\AccessCheckInterface;
use prime\interfaces\page\ForBreadcrumb as ForBreadcrumbInterface;
use prime\models\ar\Page;
use prime\models\ar\Permission;
use prime\models\pages\ForBreadcrumb;
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
        return new ForBreadcrumb($record);
    }
}
