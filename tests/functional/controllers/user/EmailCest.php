<?php

declare(strict_types=1);

namespace prime\tests\functional\controllers\user;

use Carbon\Carbon;
use prime\models\ar\User;
use prime\tests\FunctionalTester;
use SamIT\Yii2\UrlSigner\UrlSigner;

/**
 * @covers \prime\controllers\user\Email
 * @covers \prime\controllers\UserController
 * @covers \prime\models\forms\user\UpdateEmailForm
 */
class EmailCest
{
    protected function getUpdateSignedUrl(string $oldEmail, string $newEmail): array
    {
        /** @var UrlSigner $urlSigner */
        $urlSigner = \Yii::$app->urlSigner;
        $url = [
            '/user/confirm-email',
            'email' => $newEmail,
            'old_hash' => password_hash($oldEmail, PASSWORD_DEFAULT),
        ];
        $urlSigner->signParams($url, false, Carbon::now()->addHours(3));
        return $url;
    }

    public function testConfirm(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        /** @var User $user */
        $user = \Yii::$app->user->identity;
        $newEmail = 'newEmail@test.com';
        $I->amOnPage($this->getUpdateSignedUrl($user->email, $newEmail));
        $I->seeResponseCodeIs(200);
        $I->stopFollowingRedirects();
        $I->click('Apply changes');
        $I->seeResponseCodeIs(302);
        $user->refresh();
        $I->assertEquals($user->email, $newEmail);
    }

    public function testConfirmWithoutSignature(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        /** @var User $user */
        $user = \Yii::$app->user->identity;
        $newEmail = 'newEmail@test.com';
        $url = $this->getUpdateSignedUrl($user->email, $newEmail);
        unset($url['h']);
        $I->amOnPage($url);
        $I->seeResponseCodeIs(403);
    }

    public function testConfirmWithChangedEmail(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        /** @var User $user */
        $user = \Yii::$app->user->identity;
        $newEmail = 'newEmail@test.com';
        $url = $this->getUpdateSignedUrl($user->email, $newEmail);
        $url['email'] = 'newChangedEmail@test.com';
        $I->amOnPage($url);
        $I->seeResponseCodeIs(403);
    }

    public function testPageLoad(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $I->stopFollowingRedirects();
        $I->amOnPage(['user/email']);
        $I->seeResponseCodeIs(200);
    }

    public function testSubmitRequest(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $I->amOnPage(['user/email']);
        $I->seeResponseCodeIs(200);
        $newEmail = 'newEmail@test.com';
        $I->fillField('New email address', $newEmail);
        $I->stopFollowingRedirects();
        $I->click('Send confirmation');
        $I->seeResponseCodeIs(302);
        $I->seeEmailIsSent();
    }
}
