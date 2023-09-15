<?php

declare(strict_types=1);

namespace prime\tests\functional\rules;

use herams\common\domain\user\User;
use herams\common\models\PermissionOld;
use prime\tests\FunctionalTester;
use SamIT\abac\AuthManager;

/**
 * @covers \herams\common\rules\CreateFacilityRule
 */
class CreateFacilityCest
{
    public function testProjectManageImpliesCreateDisabled(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $project = $I->haveProject();
        $project->manage_implies_create_hf = false;
        $I->save($project);

        $workspace = $I->haveWorkspace();
        /** @var AuthManager $manager */
        $manager = \Yii::$app->abacManager;
        $user = \Yii::$app->user->identity;
        $I->assertInstanceOf(User::class, $user);

        $I->assertFalse($manager->check($user, $workspace, PermissionOld::PERMISSION_CREATE_FACILITY));

        $manager->grant($user, $workspace, PermissionOld::PERMISSION_MANAGE_WORKSPACES);

        $I->assertFalse($manager->check($user, $workspace, PermissionOld::PERMISSION_CREATE_FACILITY));
    }

    public function testProjectManageImpliesCreateEnabled(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $project = $I->haveProject();
        $project->manage_implies_create_hf = true;
        $I->save($project);

        $workspace = $I->haveWorkspace();
        /** @var AuthManager $manager */
        $manager = \Yii::$app->abacManager;
        $user = \Yii::$app->user->identity;
        $I->assertInstanceOf(User::class, $user);

        $I->assertFalse($manager->check($user, $workspace, PermissionOld::PERMISSION_CREATE_FACILITY));

        $manager->grant($user, $workspace, PermissionOld::PERMISSION_SURVEY_DATA);
        $I->assertTrue($manager->check($user, $workspace, PermissionOld::PERMISSION_CREATE_FACILITY));
    }
}
