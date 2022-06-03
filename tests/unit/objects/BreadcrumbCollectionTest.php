<?php

declare(strict_types=1);

namespace prime\tests\unit\objects;

use Codeception\Test\Unit;
use prime\objects\Breadcrumb;
use prime\objects\BreadcrumbCollection;

/**
 * @covers \prime\objects\BreadcrumbCollection
 */
class BreadcrumbCollectionTest extends Unit
{
    public function test(): void
    {
        $breadcrumbs = [];
        for ($i = 0; $i < 3; $i++) {
            $breadcrumbs[] = (new Breadcrumb())->setLabel((string) $i);
        }

        $breadcrumbCollection = new BreadcrumbCollection($breadcrumbs);

        $this->assertEquals(0, $breadcrumbCollection->key());
        $this->assertEquals('0', $breadcrumbCollection->current()->getLabel());
        $breadcrumbCollection->next();
        $this->assertEquals('1', $breadcrumbCollection->current()->getLabel());
        $this->assertTrue($breadcrumbCollection->valid());
        $breadcrumbCollection->next();
        $breadcrumbCollection->next();
        $this->assertFalse($breadcrumbCollection->valid());
        $breadcrumbCollection->add((new Breadcrumb())->setLabel('3'), 0);
        $this->assertFalse($breadcrumbCollection->valid());
        $breadcrumbCollection->add((new Breadcrumb())->setLabel('4'));
        $this->assertTrue($breadcrumbCollection->valid());

        $breadcrumbCollection->rewind();
        $this->assertEquals('3', $breadcrumbCollection->current()->getLabel());
    }
}
