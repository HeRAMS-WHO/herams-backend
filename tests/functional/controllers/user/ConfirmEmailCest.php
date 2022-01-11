<?php

declare(strict_types=1);

namespace prime\tests\functional\controllers\user;

use prime\models\ar\User;
use prime\tests\FunctionalTester;
use SamIT\Yii2\UrlSigner\UrlSigner;

/**
 * @covers \prime\controllers\user\ConfirmEmail
 * @covers \prime\controllers\UserController
 * @covers \prime\models\forms\user\ConfirmEmailForm
 */
class ConfirmEmailCest
{
    private function getSignedUrl($oldEmail, $newEmail): array
    {
        /** @var UrlSigner $urlSigner */
        $urlSigner = \Yii::$app->urlSigner;
        $url = [
            '/user/confirm-email',
            'email' => $newEmail,
            'old_hash' => password_hash($oldEmail, PASSWORD_DEFAULT)
        ];
        $urlSigner->signParams($url);
        return $url;
    }

    public function testChange(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $user = User::findOne(['id' => TEST_USER_ID]);
        $newEmail = 'testNew@test.com';
        $url = $this->getSignedUrl($user->email, $newEmail);
        $I->amOnPage($url);
        $I->seeResponseCodeIsSuccessful();

        $I->click('Apply changes');
        $I->seeResponseCodeIsSuccessful();
        $user->refresh();
        $I->assertEquals($newEmail, $user->email);
    }

    public function testAlreadyChanged(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $user = User::findOne(['id' => TEST_USER_ID]);
        $newEmail = 'testNew@test.com';
        $changedEmail = 'changedEmail@test.com';
        $url = $this->getSignedUrl($user->email, $newEmail);
        $user->updateAttributes(['email' => $changedEmail]);
        $I->amLoggedInAs(TEST_USER_ID);

        $I->amOnPage($url);
        $I->seeResponseCodeIs(410);
        $I->see('Your email address has already been changed');
    }
}
