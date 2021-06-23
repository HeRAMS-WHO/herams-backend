<?php
declare(strict_types=1);

namespace prime\interfaces\page;

use prime\values\PageId;
use prime\values\ProjectId;

interface ForBreadcrumb
{
    public function getId(): PageId;
    public function getProjectId(): ProjectId;
    public function getTitle(): string;
}
