<?php

declare(strict_types=1);

namespace prime\tests\functional\controllers\user;

use prime\tests\FunctionalTester;
use SamIT\Yii2\UrlSigner\UrlSigner;

/**
 * @covers \prime\controllers\user\Create
 */
class CreateCest
{
    public function test(FunctionalTester $I)
    {
        $email = 'testnew@test.com';

        $signer = \Yii::$app->urlSigner;
        $route = ['/user/create', 'email' => $email];
        $signer->signParams($route);
        $I->amOnPage($route);
        $I->seeResponseCodeIs(200);

        $I->fillField('Name', 'Test user');
        $I->fillField('Password', 'testPassword123!');
        $I->fillField('Confirm password', 'testPassword123!');

        $I->stopFollowingRedirects();
        $I->click('Create account');

        $I->seeResponseCodeIsRedirection();
    }
}
