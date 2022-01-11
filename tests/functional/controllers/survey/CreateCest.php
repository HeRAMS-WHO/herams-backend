<?php

declare(strict_types=1);

namespace prime\tests\functional\controllers\survey;

use prime\tests\FunctionalTester;

/**
 * @covers \prime\controllers\survey\Create
 */
class CreateCest
{
    public function testPageLoad(FunctionalTester $I): void
    {
        $I->amLoggedInAs(TEST_ADMIN_ID);
        $I->amOnPage(['/survey/create']);
        $I->seeResponseCodeIsSuccessful();
    }
}
