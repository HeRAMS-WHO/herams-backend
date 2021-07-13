<?php
declare(strict_types=1);

namespace prime\tests\functional\controllers\user;

use prime\tests\FunctionalTester;

/**
 * @covers \prime\controllers\user\RequestAccount
 * @covers \prime\controllers\UserController
 * @covers \prime\models\forms\user\RequestAccountForm
 */
class RequestAccountCest
{
    public function test(FunctionalTester $I)
    {
        $I->amOnPage('/session/create');
        $I->fillField(['css' => '[name="RequestAccountForm[email]"]'], 'newEmail@email.com');
        $I->click('Register');
        $I->seeResponseCodeIsSuccessful();
        $I->seeEmailIsSent();
    }
}
