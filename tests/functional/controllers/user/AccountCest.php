<?php
declare(strict_types=1);

namespace prime\tests\functional\controllers\user;

use prime\tests\FunctionalTester;
class AccountCest
{

    public function testPageLoad(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $I->amOnPage(['user/account']);
        $I->seeResponseCodeIs(200);
    }
}