<?php


namespace prime\tests\functional\controllers\project;

use prime\models\ar\User;
use prime\models\permissions\Permission;
use prime\tests\FunctionalTester;

class ShareCest
{

    public function testAccessControl(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $project = $I->haveProject();

        $I->amOnPage(['project/share', 'id' => $project->id]);
        $I->seeResponseCodeIs(403);
    }

    public function testShareWithWriteAccess(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $project = $I->haveProject();
        Permission::grant(User::findOne(['id' => TEST_USER_ID]), $project, Permission::PERMISSION_WRITE);

        $I->amOnPage(['project/share', 'id' => $project->id]);
        $I->seeResponseCodeIs(403);
    }

    public function testShareWithAdminAccess(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $user1 = User::findOne(['id' => TEST_USER_ID]);
        $user2 = User::findOne(['id' => TEST_ADMIN_ID]);

        $project = $I->haveProject();
        Permission::grant($user1, $project, Permission::PERMISSION_ADMIN);

        $I->amOnPage(['project/share', 'id' => $project->id]);
        $I->seeResponseCodeIs(200);

        $I->seeElement(['id' => 'share-userids']);

        $I->selectOption(['id' => 'share-userids'], ['value' => TEST_ADMIN_ID]);

        $I->checkOption(['css' => '[name="Share[permission][]"][value=admin]']);
        $I->stopFollowingRedirects();
        $I->assertFalse(Permission::isAllowed($user2, $project, Permission::PERMISSION_ADMIN));
        $I->click('.btn-primary');
        $I->seeResponseCodeIs(302);
        $I->assertTrue(Permission::isAllowed($user2, $project, Permission::PERMISSION_ADMIN));
    }
}