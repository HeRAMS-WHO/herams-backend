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
 * @covers \prime\controllers\permission\Revoke
 * @covers \prime\controllers\PermissionController
 */
class RevokeCest
{
    public function test(FunctionalTester $I): void
    {
        $I->amLoggedInAs(TEST_ADMIN_ID);
        $project = $I->haveProject();
        $user = User::findOne([
            'id' => TEST_USER_ID,
        ]);
        $permission = Permission::PERMISSION_ADMIN;

        /** @var AuthManager $abacManager */
        $abacManager = \Yii::$app->abacManager;

        $abacManager->grant($user, $project, $permission);
        $I->assertTrue($abacManager->check($user, $project, $permission));

        $I->createAndSetCsrfCookie('abc');
        $I->haveHttpHeader(Request::CSRF_HEADER, \Yii::$app->security->maskToken('abc'));
        $I->sendPost(
            Url::to([
                '/permission/revoke',
                'source_name' => User::class,
                'source_id' => $user->id,
                'target_name' => $project::class,
                'target_id' => $project->id,
                'permission' => $permission,
            ]),
        );
        $I->seeResponseCodeIsSuccessful();

        $I->assertFalse($abacManager->check($user, $project, $permission));
    }

    public function testForbidden(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $project = $I->haveProject();
        $user = User::findOne([
            'id' => TEST_OTHER_USER_ID,
        ]);
        $permission = Permission::PERMISSION_ADMIN;

        /** @var AuthManager $abacManager */
        $abacManager = \Yii::$app->abacManager;

        $abacManager->grant($user, $project, $permission);
        $I->assertTrue($abacManager->check($user, $project, $permission));

        $I->createAndSetCsrfCookie('abc');
        $I->haveHttpHeader(Request::CSRF_HEADER, \Yii::$app->security->maskToken('abc'));
        $I->sendPost(
            Url::to([
                '/permission/revoke',
                'source_name' => User::class,
                'source_id' => $user->id,
                'target_name' => $project::class,
                'target_id' => $project->id,
                'permission' => $permission,
            ]),
        );
        $I->seeResponseCodeIs(403);
    }
}
