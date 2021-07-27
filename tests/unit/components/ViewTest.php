<?php
declare(strict_types=1);

namespace prime\tests\unit\components;

use Codeception\Test\Unit;
use prime\components\View;
use prime\objects\BreadcrumbCollection;

/**
 * @covers \prime\components\View
 */
class ViewTest extends Unit
{
    protected function createView(): View
    {
        return new View();
    }

    public function testHasBreadcrumbCollection(): void
    {
        $this->assertInstanceOf(BreadcrumbCollection::class, $this->createView()->getBreadcrumbCollection());
    }
}
