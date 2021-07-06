<?php
declare(strict_types=1);

namespace prime\tests\unit\controllers;

use Codeception\Test\Unit;
use prime\controllers\ElementController;
use prime\objects\BreadcrumbCollection;

/**
 * @covers \prime\controllers\ElementController
 */
class ElementControllerTest extends Unit
{
    public function testBeforeActionCreate(): void
    {
        $breadcrumbCollection = $this->getMockBuilder(BreadcrumbCollection::class)->getMock();
    }
}
