<?php

declare(strict_types=1);

namespace prime\tests\functional\controllers\admin;

use herams\common\models\Permission;
use prime\tests\FunctionalTester;
use yii\mail\MessageInterface;

/**
 * @covers \prime\controllers\admin\Share
 * @covers \prime\controllers\AdminController
 * @covers \prime\models\forms\Share
 */
class ShareCest
{
    public function testRun(FunctionalTester $I): void
    {
        $I->amLoggedInAs(TEST_ADMIN_ID);
        $I->amOnPage(['admin/share']);
        $I->seeResponseCodeIsSuccessful();
    }

    public function testInvite(FunctionalTester $I): void
    {
        $I->amLoggedInAs(TEST_ADMIN_ID);
        $I->amOnPage(['admin/share']);
        $I->seeResponseCodeIsSuccessful();

        $toEmail = 'test-random@test.com';
        $I->submitForm(
            'form',
            [
                'Share[userIdsAndEmails]' => [$toEmail, TEST_USER_ID],
                'Share[permissions][]' => Permission::PERMISSION_ADMIN,
            ]
        );
        $I->seeResponseCodeIs(200);
        $I->seeEmailIsSent();
        /** @var MessageInterface $email */
        $email = $I->grabLastSentEmail();
        $I->assertEquals([
            $toEmail => null,
        ], $email->getTo());
    }
}
