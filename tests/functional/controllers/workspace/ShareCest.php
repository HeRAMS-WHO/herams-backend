<?php


namespace prime\tests\functional\controllers\workspace;

use prime\models\ar\Permission;
use prime\models\ar\User;
use prime\tests\FunctionalTester;

/**
 * @covers \prime\controllers\workspace\Share
 */
class ShareCest
{

    public function testAccessControl(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $project = $I->haveProject();
        $workspace = $I->haveWorkspace();

        $I->amOnPage(['workspace/share', 'id' => $workspace->id]);
        $I->seeResponseCodeIs(403);

        \Yii::$app->abacManager->grant(User::findOne(['id' => TEST_USER_ID]), $workspace, Permission::PERMISSION_WRITE);

        $I->amOnPage(['workspace/share', 'id' => $workspace->id]);
        $I->seeResponseCodeIs(403);
    }

    /**
     * @param FunctionalTester $I
     * @skip
     */
    public function testShareWithProjectWriteAccess(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $project = $I->haveProject();
        $workspace = $I->haveWorkspace();
        $user1 = User::findOne(['id' => TEST_USER_ID]);
        $user2 = User::findOne(['id' => TEST_ADMIN_ID]);
        \Yii::$app->abacManager->grant(User::findOne(['id' => TEST_USER_ID]), $project, Permission::PERMISSION_WRITE);

        $I->amOnPage(['workspace/share', 'id' => $workspace->id]);
        $I->seeResponseCodeIsSuccessful();

        $I->seeElement(['id' => 'share-userids']);
        $I->selectOption(['id' => 'share-userids'], ['value' => TEST_ADMIN_ID]);

        $I->checkOption(['css' => '[name="Share[permission][]"][value=admin]']);
        $I->stopFollowingRedirects();
        $I->assertFalse(\Yii::$app->abacManager->check($user2, $workspace, Permission::PERMISSION_ADMIN));
        $I->click('.btn-primary');
        $I->canSeeResponseCodeIs(302);
        $I->assertTrue(\Yii::$app->abacManager->check($user2, $workspace, Permission::PERMISSION_ADMIN));
    }
}