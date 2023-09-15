<?php

declare(strict_types=1);

namespace prime\tests\functional\controllers\workspace;

use herams\common\domain\user\User;
use herams\common\models\PermissionOld;
use prime\tests\FunctionalTester;
use yii\mail\MessageInterface;

/**
 * @covers \prime\controllers\workspace\Share
 * @covers \prime\controllers\WorkspaceController
 */
class ShareCest
{
    public function testAccessControl(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $workspace = $I->haveWorkspace();

        $I->amOnPage([
            'workspace/share',
            'id' => $workspace->id,
        ]);
        $I->seeResponseCodeIs(403);
    }

    public function testShareWithWriteAccess(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $workspace = $I->haveWorkspace();
        $I->grantCurrentUser($workspace, PermissionOld::PERMISSION_WRITE);

        $I->amOnPage([
            'workspace/share',
            'id' => $workspace->id,
        ]);
        $I->seeResponseCodeIs(403);
    }

    public function testLeadPermission(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_ADMIN_ID);
        $workspace = $I->haveWorkspace();
        $user = User::findOne([
            'id' => TEST_USER_ID,
        ]);

        $I->amOnPage([
            'workspace/share',
            'id' => $workspace->id,
        ]);
        $I->seeResponseCodeIs(200);
        $I->see(\Yii::t('app', 'Workspace owner'));

        $I->amLoggedInAs(TEST_USER_ID);
        $I->grantCurrentUser($workspace, PermissionOld::ROLE_LEAD);
        $I->amOnPage([
            'project/workspaces',
            'id' => $workspace->project->id,
        ]);
        $I->seeResponseCodeIs(200);
        $I->see($user->name, 'table tr td');
    }

    public function testNoGrantablePermissions(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $workspace = $I->haveWorkspace();
        $I->grantCurrentUser($workspace->project, PermissionOld::PERMISSION_ADMIN);

        $I->amOnPage([
            'workspace/share',
            'id' => $workspace->id,
        ]);
        $I->seeResponseCodeIs(200);

        $toUser = User::findOne([
            'id' => TEST_OTHER_USER_ID,
        ]);

        $I->submitForm(
            'form',
            [
                'Share[userIdsAndEmails][]' => $toUser->email,
                'Share[permissions][]' => PermissionOld::PERMISSION_SHARE,
            ]
        );
        $I->seeResponseCodeIs(200);
        $I->dontSeeEmailIsSent();

        // Check implicitly that permission was granted
        $I->amLoggedInAs($toUser->id);
        $I->stopFollowingRedirects();
        $I->amOnPage([
            'workspace/share',
            'id' => $workspace->id,
        ]);
        $I->seeResponseCodeIsRedirection();
    }

    public function testNotFound(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_ADMIN_ID);
        $I->amOnPage([
            'workspace/share',
            'id' => 99999999,
        ]);
        $I->seePageNotFound();
    }

    public function testShareWithInviteUser(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $workspace = $I->haveWorkspace();
        $I->grantCurrentUser($workspace->project, PermissionOld::PERMISSION_ADMIN);

        $I->amOnPage([
            'workspace/share',
            'id' => $workspace->id,
        ]);
        $I->seeResponseCodeIs(200);

        $toEmail = 'test-random@test.com';
        $I->submitForm(
            'form',
            [
                'Share[userIdsAndEmails][]' => $toEmail,
                'Share[permissions][]' => PermissionOld::PERMISSION_SUPER_SHARE,
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
        $workspace = $I->haveWorkspace();
        $I->grantCurrentUser($workspace->project, PermissionOld::PERMISSION_ADMIN);

        $I->amOnPage([
            'workspace/share',
            'id' => $workspace->id,
        ]);
        $I->seeResponseCodeIs(200);

        $toUser = User::findOne([
            'id' => TEST_OTHER_USER_ID,
        ]);

        $I->submitForm(
            'form',
            [
                'Share[userIdsAndEmails][]' => $toUser->email,
                'Share[permissions][]' => PermissionOld::PERMISSION_EXPORT,
            ]
        );
        $I->seeResponseCodeIs(200);
        $I->dontSeeEmailIsSent();

        // Check implicitly that permission was granted
        $I->amLoggedInAs($toUser->id);
        $I->amOnPage([
            'workspace/export',
            'id' => $workspace->id,
        ]);
        $I->seeResponseCodeIs(200);
    }
}
