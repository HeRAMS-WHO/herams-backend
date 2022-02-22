<?php

declare(strict_types=1);

namespace prime\tests\functional\controllers\site;

use prime\tests\FunctionalTester;

/**
 * @covers \prime\controllers\site\Maintenance
 * @covers \prime\controllers\SiteController
 */
final class MaintenanceCest
{
    public function testRouteResponse(FunctionalTester $I): void
    {
        $I->amOnPage(['site/maintenance']);
        $I->seeResponseCodeIs(503);
    }
}
