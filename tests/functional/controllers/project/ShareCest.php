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

    public function testShareWithAdminAccess(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $user1 = User::findOne(['id' => TEST_USER_ID]);
        $user2 = User::findOne(['id' => TEST_ADMIN_ID]);

        $project = $I->haveProject();
        \Yii::$app->abacManager->grant($user1, $project, Permission::PERMISSION_ADMIN);
        $I->assertTrue(\Yii::$app->user->can(Permission::PERMISSION_ADMIN, $project));

        $I->amOnPage(['project/share', 'id' => $project->id]);
        $I->seeResponseCodeIs(200);

        $I->seeElement(['id' => 'share-userids']);

        $I->selectOption(['id' => 'share-userids'], ['value' => TEST_ADMIN_ID]);

        $I->checkOption(['css' => '[name="Share[permissions][]"][value=admin]']);
        $I->stopFollowingRedirects();
        $I->assertTrue(\Yii::$app->abacManager->check($user2, $project, Permission::PERMISSION_ADMIN));
        $I->click('.btn-primary');
        $I->seeResponseCodeIs(302);
        $I->assertTrue(\Yii::$app->abacManager->check($user2, $project, Permission::PERMISSION_ADMIN));
    }
}