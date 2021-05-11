<?php
declare(strict_types=1);

namespace prime\tests\functional\controllers\user;

use prime\tests\FunctionalTester;

/**
 * @covers \prime\controllers\user\AccessRequests
 * @covers \prime\controllers\UserController
 */
class AccessRequestsCest
{
    public function testPageLoad(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $I->amOnPage(['user/notifications']);
        $I->seeResponseCodeIsSuccessful();
    }
}
