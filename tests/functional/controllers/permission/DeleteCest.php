<?php

declare(strict_types=1);

namespace prime\tests\functional\controllers\permission;

use prime\models\ar\Permission;
use prime\models\ar\User;
use prime\tests\FunctionalTester;
use SamIT\abac\AuthManager;
use yii\helpers\Url;
use yii\web\Request;

/**
 * @covers \prime\controllers\permission\Delete
 */
class DeleteCest
{
    public function test(FunctionalTester $I): void
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $project = $I->haveProjectForLimesurvey();

        // Create a permission
        \Yii::$app->abacManager->grant(User::findOne(['id' => TEST_OTHER_USER_ID]), $project, Permission::PERMISSION_READ);
        $I->assertFalse(\Yii::$app->user->can(Permission::PERMISSION_ADMIN, $project));
        $permission = Permission::findOne([
            'target_id' => $project->id,
            'target' => get_class($project)
        ]);
        $I->createAndSetCsrfCookie('abc');
        $I->haveHttpHeader(Request::CSRF_HEADER, \Yii::$app->security->maskToken('abc'));

        $I->sendDELETE(Url::to(['/permission/delete', 'id' => $permission->id, 'redirect' => '/']));
        $I->seeResponseCodeIs(403);

        /** @var AuthManager $manager */
        $manager = \Yii::$app->abacManager;
        $manager->grant(\Yii::$app->user->identity, $project, Permission::PERMISSION_ADMIN);
        $I->assertTrue(\Yii::$app->user->can(Permission::PERMISSION_ADMIN, $project));
        $I->assertTrue(\Yii::$app->user->can(Permission::PERMISSION_DELETE, $project));
        $I->sendDELETE(Url::to(['/permission/delete', 'id' => $permission->id, 'redirect' => '/']));
        $I->seeResponseCodeIs(200);
        $I->dontSeeRecord(Permission::class, [
            'id' => $permission->id
        ]);
        $I->assertFalse($permission->refresh());
    }

    public function testNotFound(FunctionalTester $I): void
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $I->createAndSetCsrfCookie('abc');
        $I->haveHttpHeader(Request::CSRF_HEADER, \Yii::$app->security->maskToken('abc'));
        $I->sendDELETE(Url::to(['/permission/delete', 'id' => 999999, 'redirect' => '/']));
        $I->seePageNotFound();
    }
}
