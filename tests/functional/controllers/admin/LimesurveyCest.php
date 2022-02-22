<?php

declare(strict_types=1);

namespace prime\tests\functional\controllers\admin;

use prime\tests\FunctionalTester;

/**
 * @covers \prime\controllers\admin\Limesurvey
 * @covers \prime\controllers\AdminController
 */
class LimesurveyCest
{
    public function testRun(FunctionalTester $I): void
    {
        $I->amLoggedInAs(TEST_ADMIN_ID);
        $I->amOnPage(['admin/limesurvey']);
        $I->seeResponseCodeIsSuccessful();
    }
}
