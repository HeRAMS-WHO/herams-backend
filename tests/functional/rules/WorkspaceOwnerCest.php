<?php

declare(strict_types=1);

namespace prime\tests\functional\rules;

use prime\helpers\ProposedGrant;
use prime\models\ar\Permission;
use prime\models\ar\User;
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
        $workspace = $I->haveWorkspaceForLimesurvey();
        /** @var AuthManager $manager */
        $manager = \Yii::$app->abacManager;

        $user = \Yii::$app->user->identity;
        $I->assertInstanceOf(User::class, $user);
        $manager->grant($user, $workspace, Permission::PERMISSION_SURVEY_DATA);
        $manager->grant($user, $workspace, Permission::PERMISSION_EXPORT);
        $manager->grant($user, $workspace, Permission::PERMISSION_SHARE);

        foreach (
            [
                Permission::PERMISSION_EXPORT => true,
                Permission::PERMISSION_SURVEY_DATA => true,
                Permission::PERMISSION_SHARE => false,
                Permission::PERMISSION_WRITE => false,
                Permission::PERMISSION_READ => true,
                Permission::PERMISSION_ADMIN => false,
                Permission::PERMISSION_MANAGE_WORKSPACES => false,
                Permission::PERMISSION_MANAGE_DASHBOARD => false,
            ] as $permission => $result
        ) {
            $proposedGrant = new ProposedGrant(User::findOne([
                'id' => TEST_OTHER_USER_ID,
            ]), $workspace, $permission);
            $I->assertSame($result, $manager->check($user, $proposedGrant, Permission::PERMISSION_CREATE), $result ? "Permission $permission not allowed" : "Permission $permission allowed");
        }
    }
}
