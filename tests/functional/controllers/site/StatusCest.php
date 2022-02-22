<?php

declare(strict_types=1);

namespace prime\tests\functional\controllers\site;

use prime\tests\FunctionalTester;

/**
 * @covers \prime\controllers\site\Status
 * @covers \prime\controllers\SiteController
 */
class StatusCest
{
    public function test(FunctionalTester $I)
    {
        $I->amOnPage(['site/status']);
        $I->seeResponseCodeIsSuccessful();
    }
}
