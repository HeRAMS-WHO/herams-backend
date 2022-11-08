<?php

declare(strict_types=1);

namespace herams\common\interfaces;

use herams\common\values\PageId;
use herams\common\values\ProjectId;
use prime\helpers\HeramsVariableSet;

interface HeramsVariableSetRepositoryInterface
{
    public function retrieveForProject(ProjectId $projectId): HeramsVariableSet;

    public function retrieveForPage(PageId $pageId): HeramsVariableSet;
}
