<?php

declare(strict_types=1);

namespace prime\tests\functional\controllers\mail;

use prime\models\ar\User;
use prime\tests\FunctionalTester;

/**
 * @covers \prime\controllers\mail\Webhook
 * @covers \prime\controllers\MailController
 */
class WebhookCest
{
    public function testSubscribeCall(FunctionalTester $I)
    {
        $user = User::findOne([
            'id' => TEST_USER_ID,
        ]);
        $user->updateAttributes([
            'newsletter_subscription' => false,
        ]);

        $I->sendPost('mail/webhook', [
            'type' => 'subscribe',
            'data' => [
                'email' => $user->email,

            ],
        ]);
        $I->seeResponseCodeIsSuccessful();

        $user->refresh();
        $I->assertTrue($user->newsletter_subscription == true);
    }

    public function testUnsubscribeCall(FunctionalTester $I)
    {
        $user = User::findOne([
            'id' => TEST_USER_ID,
        ]);
        $I->assertTrue($user->newsletter_subscription == true);

        $I->sendPost('mail/webhook', [
            'type' => 'unsubscribe',
            'data' => [
                'email' => $user->email,

            ],
        ]);
        $I->seeResponseCodeIsSuccessful();

        $user->refresh();
        $I->assertTrue($user->newsletter_subscription == false);
    }
}
