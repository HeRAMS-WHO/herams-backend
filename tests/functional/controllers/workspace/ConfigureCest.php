<?php


namespace prime\tests\functional\controllers\workspace;

use prime\models\ar\Permission;
use prime\models\ar\User;
use prime\tests\FunctionalTester;

/**
 * @covers \prime\controllers\workspace\Configure
 */
class ConfigureCest
{

    public function testAccessControl(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $workspace = $I->haveWorkspace();

        $I->amOnPage(['workspace/configure', 'id' => $workspace->id]);
        $I->seeResponseCodeIs(403);
    }


    public function testAccessControlWithAdminAccess(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $project = $I->haveProject();
        $workspace = $I->haveWorkspace();
        \Yii::$app->abacManager->grant(User::findOne(['id' => TEST_USER_ID]), $project, Permission::PERMISSION_ADMIN);

        $I->amOnPage(['workspace/configure', 'id' => $workspace->id]);
        $I->seeResponseCodeIs(200);
    }

    public function testNotFound(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $I->amOnPage(['workspace/configure', 'id' => 12345]);
        $I->seeResponseCodeIs(404);
    }

    public function testConfigure(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $workspace = $I->haveWorkspace();
        \Yii::$app->abacManager->grant(User::findOne(['id' => TEST_USER_ID]), $workspace, Permission::PERMISSION_ADMIN);

        $I->assertTrue(\Yii::$app->user->can(Permission::PERMISSION_ADMIN, $workspace));
        $I->amOnPage(['workspace/configure', 'id' => $workspace->id]);
        $I->seeResponseCodeIs(200);

        $I->fillField('First Name', 'Nice!');
        $I->click('Update token');
        $I->seeResponseCodeIsSuccessful();
        $workspace->refresh();
        $I->assertSame('Nice!', $workspace->getToken()->getFirstName());
    }
}
