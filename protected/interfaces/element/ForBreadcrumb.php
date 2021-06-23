<?php
declare(strict_types=1);

namespace prime\interfaces\element;

use prime\values\ElementId;
use prime\values\PageId;

interface ForBreadcrumb
{
    public function getId(): ElementId;
    public function getPageId(): PageId;
    public function getTitle(): string;
}
