<?php

declare(strict_types=1);

namespace prime\repositories;

use prime\interfaces\page\PageForBreadcrumbInterface as ForBreadcrumbInterface;
use prime\models\ar\Page;
use prime\models\pages\PageForBreadcrumb;
use prime\values\PageId;

class PageRepository
{
    public function retrieveForBreadcrumb(PageId $id): ForBreadcrumbInterface
    {
        $record = Page::findOne([
            'id' => $id,
        ]);
        return new PageForBreadcrumb($record);
    }

    public function retrieveForDashboarding(PageId $id)
    {
    }
}
