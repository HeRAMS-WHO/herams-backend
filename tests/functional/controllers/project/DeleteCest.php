<?php
declare(strict_types=1);

namespace prime\tests\functional\controllers\project;

use prime\models\ar\Permission;
use prime\models\ar\Project;
use prime\models\ar\User;
use prime\tests\FunctionalTester;
use yii\helpers\Url;
use yii\web\Request;

/**
 * @covers \prime\actions\DeleteAction
 */
class DeleteCest
{

    public function testAccessControl(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $project = $I->haveProject();
        $user = User::findOne(['id' => TEST_USER_ID]);
        $I->createAndSetCsrfCookie('abc');
        $I->haveHttpHeader(Request::CSRF_HEADER, \Yii::$app->security->maskToken('abc'));
        $I->sendDELETE(Url::to(['/project/delete', 'id' => $project->id]));
        $I->seeResponseCodeIs(403);

        \Yii::$app->abacManager->grant($user, $project, Permission::PERMISSION_READ);
        $I->sendDELETE(Url::to(['/project/delete', 'id' => $project->id]));
        $I->seeResponseCodeIs(403);

        \Yii::$app->abacManager->grant($user, $project, Permission::PERMISSION_WRITE);
        $I->sendDELETE(Url::to(['/project/delete', 'id' => $project->id]));
        $I->seeResponseCodeIs(403);
    }

    public function testDelete(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $project = $I->haveProject();

        \Yii::$app->abacManager->grant(User::findOne(['id' => TEST_USER_ID]), $project, Permission::PERMISSION_DELETE);

        $I->stopFollowingRedirects();
        $I->createAndSetCsrfCookie('abc');
        $I->haveHttpHeader(Request::CSRF_HEADER, \Yii::$app->security->maskToken('abc'));

        $I->assertTrue(\Yii::$app->user->can(Permission::PERMISSION_DELETE, $project));
        $I->sendDELETE(Url::to(['/project/delete', 'id' => $project->id]));

        $I->seeResponseCodeIs(302);
        $I->dontSeeRecord(Project::class, ['id' => $project->id]);
    }

    public function testDeleteWithWorkspaces(FunctionalTester $I)
    {
        return;
        $I->amLoggedInAs(TEST_USER_ID);
        $project = $I->haveProject();
        $I->haveWorkspace();

        \Yii::$app->abacManager->grant(User::findOne(['id' => TEST_USER_ID]), $project, Permission::PERMISSION_ADMIN);

        $I->createAndSetCsrfCookie('abc');
        $I->haveHttpHeader(Request::CSRF_HEADER, \Yii::$app->security->maskToken('abc'));
        $I->sendDELETE(Url::to(['/project/delete', 'id' => $project->id]));
        $I->seeRecord(Project::class, ['id' => $project->id]);
        $I->seeResponseCodeIsSuccessful();
        $I->seeInSource('Deletion failed');
    }
}
