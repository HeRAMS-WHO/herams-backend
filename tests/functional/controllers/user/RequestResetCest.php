<?php
declare(strict_types=1);

namespace prime\tests\functional\controllers\user;

use prime\models\ar\User;
use prime\tests\FunctionalTester;

/**
 * @covers \prime\controllers\user\RequestReset
 * @covers \prime\controllers\UserController
 * @covers \prime\models\forms\user\RequestResetForm
 */
class RequestResetCest
{
    public function testRequest(FunctionalTester $I)
    {
        $I->amOnPage(['/user/request-reset']);
        $I->seeResponseCodeIs(200);
        $user = User::findOne(['id' => TEST_USER_ID]);
        $I->fillField('Email', $user->email);
        $I->click('Request password reset');
        $I->seeEmailIsSent();

        $I->amOnPage(['/user/request-reset']);
        $I->seeResponseCodeIs(200);
        $I->fillField('Email', $user->email);
        $I->click('Request password reset');
        $I->seeResponseCodeIs(200);
        $I->see('Too many attempts, try again in 120 seconds');
    }

    public function testUnknownUser(FunctionalTester $I)
    {
        $I->amOnPage(['/user/request-reset']);
        $I->seeResponseCodeIs(200);
        $I->fillField('Email', 'unknown@test.com');
        $I->click('Request password reset');
        $I->dontSeeEmailIsSent();
        $I->see('This user is not known or not yet verified');
    }
}
