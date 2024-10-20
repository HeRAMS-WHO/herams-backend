<?php

declare(strict_types=1);

namespace prime\tests\unit\models\element;

use Codeception\Test\Unit;
use herams\common\domain\element\Element;
use herams\common\values\PageId;
use prime\models\element\ElementForBreadcrumb;

/**
 * @covers \prime\models\element\ElementForBreadcrumb
 */
class ElementForBreadcrumbTest extends Unit
{
    public function testConstructor(): void
    {
        $elementId = 12345;
        $label = 'Element label';
        $pageId = 23456;

        $element = new Element();
        $element->id = $elementId;
        $element->page_id = $pageId;
        $element->setTitle($label);

        $forBreadcrumb = new ElementForBreadcrumb($element);
        $this->assertEquals([
            '/element/preview',
            'id' => $elementId,
        ], $forBreadcrumb->getUrl());
        $this->assertEquals(new PageId($pageId), $forBreadcrumb->getPageId());
        $this->assertEquals($label, $forBreadcrumb->getLabel());
    }
}
