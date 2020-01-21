<?php
declare(strict_types=1);

namespace prime\tests\functional\controllers\user;

use prime\tests\FunctionalTester;
use SamIT\Yii2\UrlSigner\UrlSigner;

class CreateCest
{

    public function testPageLoad(FunctionalTester $I)
    {
        /** @var UrlSigner $signer */
        $signer = \Yii::$app->urlSigner;
        $I->amLoggedInAs(TEST_USER_ID);
        $route = ['/user/create', 'email' => 'test@test.n'];
        $signer->signParams($route);
        $I->amOnPage($route);
        $I->seeResponseCodeIs(200);
    }
}