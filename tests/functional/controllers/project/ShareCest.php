<?php

declare(strict_types=1);

namespace prime\tests\functional\controllers\project;

use prime\models\ar\Permission;
use prime\models\ar\User;
use prime\tests\FunctionalTester;
use yii\mail\MessageInterface;

/**
 * @covers \prime\controllers\project\Share
 */
class ShareCest
{
    public function testAccessControl(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $project = $I->haveProject();

        $I->amOnPage([
            'project/share',
            'id' => $project->id,
        ]);
        $I->seeResponseCodeIs(403);
    }

    public function testShareWithWriteAccess(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $project = $I->haveProject();
        $I->grantCurrentUser($project, Permission::PERMISSION_WRITE);

        $I->amOnPage([
            'project/share',
            'id' => $project->id,
        ]);
        $I->seeResponseCodeIs(403);
    }

    public function testLeadPermission(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_ADMIN_ID);
        $project = $I->haveProject();
        $user = User::findOne([
            'id' => TEST_USER_ID,
        ]);

        $I->amOnPage([
            'project/share',
            'id' => $project->id,
        ]);
        $I->seeResponseCodeIs(200);
        $I->see(\Yii::t('app', 'Project coordinator'));

        $I->amLoggedInAs(TEST_USER_ID);
        $I->grantCurrentUser($project, Permission::ROLE_LEAD);
        $I->amOnPage(['project/index']);
        $I->seeResponseCodeIs(200);
        $I->see($user->name, 'table tr td');
    }

    public function testShareWithInviteUser(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $project = $I->haveProject();
        $I->grantCurrentUser($project, Permission::PERMISSION_ADMIN);

        $I->amOnPage([
            'project/share',
            'id' => $project->id,
        ]);
        $I->seeResponseCodeIs(200);

        $toEmail = 'test-random@test.com';
        $I->submitForm(
            'form',
            [
                'Share[userIdsAndEmails][]' => $toEmail,
                'Share[permissions][]' => Permission::PERMISSION_SUPER_SHARE,
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

    public function testShareWithInviteExistingUser(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $project = $I->haveProject();
        $I->grantCurrentUser($project, Permission::PERMISSION_ADMIN);

        $I->amOnPage([
            'project/share',
            'id' => $project->id,
        ]);
        $I->seeResponseCodeIs(200);

        $toUser = User::findOne([
            'id' => TEST_OTHER_USER_ID,
        ]);

        $I->submitForm(
            'form',
            [
                'Share[userIdsAndEmails][]' => $toUser->email,
                'Share[permissions][]' => Permission::PERMISSION_SHARE,
            ]
        );
        $I->seeResponseCodeIs(200);
        $I->dontSeeEmailIsSent();

        // Check implicitly that permission was granted
        $I->amLoggedInAs($toUser->id);
        $I->amOnPage([
            'project/share',
            'id' => $project->id,
        ]);
        $I->seeResponseCodeIs(200);
    }
}
