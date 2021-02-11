<?php
declare(strict_types=1);

namespace prime\tests\functional\controllers\user;

use prime\models\ar\User;
use prime\tests\FunctionalTester;

/**
 * @covers \prime\controllers\user\RequestReset
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
    }
}
