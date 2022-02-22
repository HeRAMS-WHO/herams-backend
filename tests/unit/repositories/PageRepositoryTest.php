<?php

declare(strict_types=1);

namespace prime\tests\unit\repositories;

use Codeception\Test\Unit;
use prime\models\ar\Page;
use prime\repositories\PageRepository;
use prime\values\PageId;
use prime\values\ProjectId;

/**
 * @covers \prime\repositories\PageRepository
 */
class PageRepositoryTest extends Unit
{
    public function testFindForBreadcrumb(): void
    {
        $page = Page::findOne(['id' => 12]);

        $pageRepository = new PageRepository();
        $breadcrumb = $pageRepository->retrieveForBreadcrumb(new PageId($page->id));

        $this->assertEquals($page->title, $breadcrumb->getLabel());
        $this->assertEquals(['/page/update', 'id' => $page->id], $breadcrumb->getUrl());
        $this->assertEquals(new ProjectId($page->project_id), $breadcrumb->getProjectId());
    }
}
