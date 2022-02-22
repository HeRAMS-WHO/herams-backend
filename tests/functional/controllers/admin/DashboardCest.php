<?php

declare(strict_types=1);

namespace prime\tests\functional\controllers\admin;

use prime\tests\FunctionalTester;

/**
 * @covers \prime\controllers\admin\Dashboard
 * @covers \prime\controllers\AdminController
 */
class DashboardCest
{
    public function testRun(FunctionalTester $I): void
    {
        $I->amLoggedInAs(TEST_ADMIN_ID);
        $I->amOnPage(['admin/dashboard']);
        $I->seeResponseCodeIsSuccessful();
    }
}
