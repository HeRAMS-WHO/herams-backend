<?php


namespace prime\tests\functional\controllers\workspace;

use prime\models\ar\Permission;
use prime\models\ar\User;
use prime\tests\FunctionalTester;

/**
 * @covers \prime\controllers\workspace\Update
 */
class UpdateCest
{

    public function testAccessControl(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $workspace = $I->haveWorkspace();
        $I->amOnPage(['workspace/update', 'id' => $workspace->id]);
        $I->seeResponseCodeIs(403);
    }

    public function testNotFound(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $I->amOnPage(['workspace/update', 'id' => 12345]);
        $I->seeResponseCodeIs(404);
    }

    public function testAccessControlWithWriteAccess(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $project = $I->haveProject();
        $workspace = $I->haveWorkspace();
        \Yii::$app->abacManager->grant(User::findOne(['id' => TEST_USER_ID]), $project, Permission::PERMISSION_WRITE);

        $I->amOnPage(['workspace/update', 'id' => $workspace->id]);
        $I->seeResponseCodeIs(403);
    }

    public function testAccessControlWithAdminAccess(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $project = $I->haveProject();
        $workspace = $I->haveWorkspace();
        \Yii::$app->abacManager->grant(User::findOne(['id' => TEST_USER_ID]), $project, Permission::PERMISSION_ADMIN);

        $I->amOnPage(['workspace/update', 'id' => $workspace->id]);
        $I->seeResponseCodeIs(200);
    }

    public function testUpdate(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $workspace = $I->haveWorkspace();
        \Yii::$app->abacManager->grant(User::findOne(['id' => TEST_USER_ID]), $workspace, Permission::PERMISSION_ADMIN);

        $I->amOnPage(['workspace/update', 'id' => $workspace->id]);
        $I->seeResponseCodeIs(200);

        $attributes = [
            'title' => 'test123',
        ];

        foreach($attributes as $key => $value) {
            $I->fillField(['name' => "Workspace[$key]"], $value);
        }

        $I->click('Save');
        $I->seeResponseCodeIsSuccessful();
        $workspace->refresh();
        foreach($attributes as $key => $value) {
            $I->assertEquals($value, $workspace->$key, '', 0.001);
        }

    }
}