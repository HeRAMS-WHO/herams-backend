<?php
declare(strict_types=1);

namespace prime\tests\functional\controllers\user;

use prime\tests\FunctionalTester;
use SamIT\Yii2\UrlSigner\UrlSigner;

class RequestResetCest
{

    public function testPageLoad(FunctionalTester $I)
    {
        $I->amOnPage(['/user/request-reset']);
        $I->seeResponseCodeIs(200);
    }

}