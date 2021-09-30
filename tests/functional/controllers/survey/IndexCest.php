<?php
declare(strict_types=1);

namespace prime\tests\functional\controllers\survey;

use prime\tests\FunctionalTester;

/**
 * @covers \prime\controllers\survey\Index
 */
class IndexCest
{
    public function testPageLoad(FunctionalTester $I): void
    {
        $I->amLoggedInAs(TEST_ADMIN_ID);
        $I->amOnPage(['/survey/index']);
        $I->seeResponseCodeIsSuccessful();
    }
}
