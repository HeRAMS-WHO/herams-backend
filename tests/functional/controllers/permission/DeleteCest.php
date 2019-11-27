<?php


namespace prime\tests\functional\controllers\permission;

use prime\models\ar\Project;
use prime\models\ar\User;
use prime\models\permissions\Permission;
use prime\tests\FunctionalTester;
use SamIT\abac\AuthManager;
use yii\helpers\Url;
use yii\web\Request;

class DeleteCest
{

    public function testAccessControl(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $project = $I->haveProject();
        $user = User::findOne(['id' => TEST_USER_ID]);
        $I->createAndSetCsrfCookie('abc');
        $I->haveHttpHeader(Request::CSRF_HEADER, \Yii::$app->security->maskToken('abc'));

        \Yii::$app->abacManager->grant($user, $project, Permission::PERMISSION_READ);

        $I->assertFalse(\Yii::$app->user->can(Permission::PERMISSION_ADMIN, $project));

        $permission = Permission::findOne([
            'target_id' => $project->id,
            'target'=> get_class($project)
        ]);

        $I->sendDELETE(Url::to(['/permission/delete', 'id' => $permission->id, 'redirect' => '/']));
        $I->seeResponseCodeIs(403);
        /** @var AuthManager $manager */
        $manager = \Yii::$app->abacManager;
        $manager->grant($user, $project, Permission::PERMISSION_ADMIN);
        $I->assertTrue(\Yii::$app->user->can(Permission::PERMISSION_ADMIN, $project));
        $I->sendDELETE(Url::to(['/permission/delete', 'id' => $permission->id, 'redirect'=> '/']));
        $I->seeResponseCodeIs(200);
        $I->dontSeeRecord(Permission::class, [
            'id' => $permission->id
        ]);
        $I->assertFalse($permission->refresh());

    }

    public function testAccessControlImplicit(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $project = $I->haveProject();
        $workspace = $I->haveWorkspace();
        $user = User::findOne(['id' => TEST_USER_ID]);
        $I->createAndSetCsrfCookie('abc');
        $I->haveHttpHeader(Request::CSRF_HEADER, \Yii::$app->security->maskToken('abc'));

        \Yii::$app->abacManager->grant($user, $workspace, Permission::PERMISSION_WRITE);
        $permission = Permission::findOne([
            'target_id' => $workspace->id,
            'target'=> get_class($workspace)
        ]);

        $I->sendDELETE(Url::to(['/permission/delete', 'id' => $permission->id, 'redirect' => '/']));
        $I->seeResponseCodeIs(403);

        /** @var AuthManager $manager */
        $manager = \Yii::$app->abacManager;
        $manager->grant($user, $project, Permission::PERMISSION_ADMIN);

        $I->sendDELETE(Url::to(['/permission/delete', 'id' => $permission->id, 'redirect'=> '/']));
        $I->seeResponseCodeIs(200);
        $I->assertFalse($permission->refresh());

    }

}