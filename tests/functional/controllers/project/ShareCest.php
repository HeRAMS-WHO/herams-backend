<?php


namespace prime\tests\functional\controllers\project;

use prime\models\ar\User;
use prime\models\permissions\Permission;
use prime\tests\FunctionalTester;
use SamIT\abac\AuthManager;

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
        \Yii::$app->abacManager->grant(User::findOne(['id' => TEST_USER_ID]), $project, Permission::PERMISSION_WRITE);

        $I->amOnPage(['project/share', 'id' => $project->id]);
        $I->seeResponseCodeIs(403);
    }

}