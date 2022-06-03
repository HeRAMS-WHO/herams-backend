<?php

declare(strict_types=1);

namespace prime\tests\functional\controllers\user;

use Carbon\Carbon;
use prime\models\ar\User;
use prime\tests\FunctionalTester;
use SamIT\Yii2\UrlSigner\UrlSigner;

/**
 * @covers \prime\controllers\user\ResetPassword
 * @covers \prime\controllers\UserController
 * @covers \prime\models\forms\user\ResetPasswordForm
 */
class ResetPasswordCest
{
    protected function getResetSignedUrl(User $user): array
    {
        /** @var UrlSigner $urlSigner */
        $urlSigner = \Yii::$app->urlSigner;
        $url = [
            '/user/reset-password',
            'id' => $user->id,
            'crc' => crc32($user->password_hash),
        ];
        $urlSigner->signParams($url, false, Carbon::now()->addHours(4));
        return $url;
    }

    public function testReset(FunctionalTester $I)
    {
        $user = User::findOne([
            'id' => TEST_USER_ID,
        ]);
        $newPassword = 'S' . bin2hex(random_bytes(16));
        $I->amOnPage($this->getResetSignedUrl($user));
        $I->seeResponseCodeIs(200);
        $I->fillField('New password', $newPassword);
        $I->fillField('Repeat password', $newPassword);
        $I->stopFollowingRedirects();
        $I->click('Reset password');
        $I->seeResponseCodeIs(302);
        $user->refresh();
        $I->assertTrue(password_verify($newPassword, $user->password_hash));
    }

    public function testResetWithoutSignature(FunctionalTester $I)
    {
        $user = User::findOne([
            'id' => TEST_USER_ID,
        ]);
        $url = $this->getResetSignedUrl($user);
        unset($url['h']);
        $I->amOnPage($url);
        $I->seeResponseCodeIs(403);
    }

    public function testConfirmAfterChangedPassword(FunctionalTester $I)
    {
        $user = User::findOne([
            'id' => TEST_USER_ID,
        ]);
        $url = $this->getResetSignedUrl($user);
        $I->amOnPage($url);
        $I->seeResponseCodeIs(200);
        $user->setPassword('testPassword');
        $user->save();
        $I->amOnPage($url);
        $I->seeResponseCodeIs(401);
    }

    public function testNonExistingUser(FunctionalTester $I)
    {
        /** @var UrlSigner $urlSigner */
        $urlSigner = \Yii::$app->urlSigner;
        $url = [
            '/user/reset-password',
            'id' => 999999999999,
            'crc' => crc32('testPassword123'),
        ];
        $urlSigner->signParams($url, false, Carbon::now()->addHours(4));

        $I->amOnPage($url);
        $I->seePageNotFound();
    }
}
