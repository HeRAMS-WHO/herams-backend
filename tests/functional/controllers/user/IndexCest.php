<?php

declare(strict_types=1);

namespace prime\tests\functional\controllers\user;

use herams\common\domain\user\User;
use prime\tests\FunctionalTester;

/**
 * @covers \prime\controllers\user\Index
 */
class IndexCest
{
    public function testFilter(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_ADMIN_ID);
        /** @var User $user */
        $user = \Yii::$app->user->identity;
        $I->amOnPage([
            'user/index',
            'User[email]' => $user->email,
        ]);
        $I->seeResponseCodeIs(200);
        $I->see($user->id);
        $I->see($user->name);
        $I->see($user->email);
    }

    public function testPageLoad(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_ADMIN_ID);
        $I->amOnPage(['user/index']);
        $I->seeResponseCodeIs(200);
    }
}
