<?php

declare(strict_types=1);

namespace prime\tests\unit\repositories;

use Codeception\Test\Unit;
use prime\helpers\ModelHydrator;
use prime\models\ar\Element;
use prime\repositories\ElementRepository;
use prime\values\ElementId;
use prime\values\PageId;

/**
 * @covers \prime\repositories\ElementRepository
 */
class ElementRepositoryTest extends Unit
{
    public function testFindForBreadcrumb(): void
    {
        $element = Element::findOne([
            'id' => 37,
        ]);

        $pageRepository = new ElementRepository(new ModelHydrator());
        $breadcrumb = $pageRepository->retrieveForBreadcrumb(new ElementId($element->id));

        $this->assertEquals($element->getTitle(), $breadcrumb->getLabel());
        $this->assertEquals([
            '/element/preview',
            'id' => $element->id,
        ], $breadcrumb->getUrl());
        $this->assertEquals(new PageId($element->page_id), $breadcrumb->getPageId());
    }
}
