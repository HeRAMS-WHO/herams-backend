<?php

declare(strict_types=1);

namespace prime\tests\functional\rules;

use herams\common\domain\permission\ProposedGrant;
use herams\common\domain\user\User;
use herams\common\models\PermissionOld;
use prime\tests\FunctionalTester;
use SamIT\abac\AuthManager;

/**
 * @coversNothing
 */
class WorkspaceOwnerCest
{
    public function testSharing(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $workspace = $I->haveWorkspace();
        /** @var AuthManager $manager */
        $manager = \Yii::$app->abacManager;

        $user = \Yii::$app->user->identity;
        $I->assertInstanceOf(User::class, $user);
        $manager->grant($user, $workspace, PermissionOld::PERMISSION_SURVEY_DATA);
        $manager->grant($user, $workspace, PermissionOld::PERMISSION_EXPORT);
        $manager->grant($user, $workspace, PermissionOld::PERMISSION_SHARE);

        foreach (
            [
                PermissionOld::PERMISSION_EXPORT => true,
                PermissionOld::PERMISSION_SURVEY_DATA => true,
                PermissionOld::PERMISSION_SHARE => false,
                PermissionOld::PERMISSION_WRITE => false,
                PermissionOld::PERMISSION_READ => true,
                PermissionOld::PERMISSION_ADMIN => false,
                PermissionOld::PERMISSION_MANAGE_WORKSPACES => false,
                PermissionOld::PERMISSION_MANAGE_DASHBOARD => false,
            ] as $permission => $result
        ) {
            $proposedGrant = new ProposedGrant(User::findOne([
                'id' => TEST_OTHER_USER_ID,
            ]), $workspace, $permission);
            $I->assertSame($result, $manager->check($user, $proposedGrant, PermissionOld::PERMISSION_CREATE), $result ? "PermissionOld $permission not allowed" : "PermissionOld $permission allowed");
        }
    }
}
