<?php

declare(strict_types=1);

namespace prime\tests\functional\controllers\user;

use prime\models\ar\User;
use prime\tests\FunctionalTester;

/**
 * @covers \prime\controllers\user\Password
 * @covers \prime\controllers\UserController
 * @covers \prime\models\forms\user\UpdatePasswordForm
 */
class PasswordCest
{
    public function testPageLoad(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $I->amOnPage(['user/password']);
        $I->seeResponseCodeIs(200);
    }

    public function testUpdatePassword(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        /** @var User $user */
        $user = \Yii::$app->user->identity;
        $user->setPassword('test123');
        $I->save($user);
        $I->amOnPage(['user/password']);
        $I->seeResponseCodeIs(200);
        $I->fillField('Current password', 'test123');
        $password = 'S' . bin2hex(random_bytes(16));
        $I->fillField('New password', $password);
        $I->fillField('Repeat password', $password);
        $I->stopFollowingRedirects();
        $I->click('Update password');
        $I->seeResponseCodeIs(302);
        $user->refresh();
        $I->assertTrue(password_verify($password, $user->password_hash));
    }

    public function testValidation(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        /** @var User $user */
        $user = \Yii::$app->user->identity;
        $user->setPassword('test123');
        $I->save($user);
        $I->amOnPage(['user/password']);
        $I->seeResponseCodeIs(200);
        $I->fillField('Current password', 'test1234');
        $password = 'test12345';
        $I->fillField('New password', $password);
        $I->fillField('Repeat password', $password);
        $I->stopFollowingRedirects();
        $I->click('Update password');
        $I->seeResponseCodeIs(200);
        $user->refresh();
        $I->assertFalse(password_verify($password, $user->password_hash));
    }
}
