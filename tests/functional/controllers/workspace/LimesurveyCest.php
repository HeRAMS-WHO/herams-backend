<?php


namespace prime\tests\functional\controllers\workspace;

use prime\models\ar\Permission;
use prime\models\ar\User;
use prime\tests\FunctionalTester;

/**
 * @covers \prime\controllers\workspace\Limesurvey
 */
class LimesurveyCest
{

    public function testAccessControl(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $workspace = $I->haveWorkspace();

        $I->amOnPage(['workspace/limesurvey', 'id' => $workspace->id]);
        $I->seeResponseCodeIs(403);

        \Yii::$app->abacManager->grant(User::findOne(['id' => TEST_USER_ID]), $workspace, Permission::PERMISSION_READ);
        $I->amOnPage(['workspace/limesurvey', 'id' => $workspace->id]);
        $I->seeResponseCodeIs(403);
        \Yii::$app->abacManager->grant(User::findOne(['id' => TEST_USER_ID]), $workspace, Permission::PERMISSION_LIMESURVEY);
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
        \Yii::$app->abacManager->grant(User::findOne(['id' => TEST_USER_ID]), $workspace, Permission::PERMISSION_LIMESURVEY);

        $I->amOnPage(['workspace/limesurvey', 'id' => $workspace->id]);
        $I->seeResponseCodeIs(200);

        $I->seeElement('iframe');
    }
}
