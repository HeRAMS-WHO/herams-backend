<?php


namespace prime\tests\functional\controllers\workspace;

use prime\models\ar\Project;
use prime\models\ar\User;
use prime\models\permissions\Permission;
use prime\tests\FunctionalTester;
use yii\helpers\Json;

class LimesurveyCest
{

    public function testAccessControl(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $workspace = $I->haveWorkspace();

        $I->amOnPage(['workspace/limesurvey', 'id' => $workspace->id]);
        $I->seeResponseCodeIs(403);

        Permission::grant(User::findOne(['id' => TEST_USER_ID]), $workspace, Permission::PERMISSION_WRITE);
        $I->amOnPage(['workspace/limesurvey', 'id' => $workspace->id]);
        $I->seeResponseCodeIs(403);
    }

    public function testAccessControlWithWriteAccess(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $project = $I->haveProject();
        $workspace = $I->haveWorkspace();
        Permission::grant(User::findOne(['id' => TEST_USER_ID]), $project, Permission::PERMISSION_WRITE);

        $I->amOnPage(['workspace/limesurvey', 'id' => $workspace->id]);
        $I->seeResponseCodeIs(200);
    }

    public function testAccessControlWithAdminAccess(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $project = $I->haveProject();
        $workspace = $I->haveWorkspace();
        Permission::grant(User::findOne(['id' => TEST_USER_ID]), $project, Permission::PERMISSION_ADMIN);

        $I->amOnPage(['workspace/limesurvey', 'id' => $workspace->id]);
        $I->seeResponseCodeIs(200);
    }

    public function testNotFound(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $I->amOnPage(['workspace/limesurvey', 'id' => 12345]);
        $I->seeResponseCodeIs(404);
    }

    public function testLimesurvey(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $workspace = $I->haveWorkspace();
        Permission::grant(User::findOne(['id' => TEST_USER_ID]), $workspace, Permission::PERMISSION_ADMIN);

        $I->amOnPage(['workspace/limesurvey', 'id' => $workspace->id]);
        $I->seeResponseCodeIs(200);

        $I->seeElement('iframe');
    }
}