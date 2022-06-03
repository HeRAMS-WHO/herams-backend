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
 * @covers \prime\controllers\permission\Grant
 * @covers \prime\controllers\PermissionController
 */
class GrantCest
{
    public function test(FunctionalTester $I): void
    {
        $I->amLoggedInAs(TEST_ADMIN_ID);
        $project = $I->haveProjectForLimesurvey();
        $user = User::findOne([
            'id' => TEST_USER_ID,
        ]);
        $permission = Permission::PERMISSION_ADMIN;

        /** @var AuthManager $abacManager */
        $abacManager = \Yii::$app->abacManager;
        $I->assertFalse($abacManager->check($user, $project, $permission));

        $I->createAndSetCsrfCookie('abc');
        $I->haveHttpHeader(Request::CSRF_HEADER, \Yii::$app->security->maskToken('abc'));
        $I->sendPost(
            Url::to([
                '/permission/grant',
                'source_name' => User::class,
                'source_id' => $user->id,
                'target_name' => $project::class,
                'target_id' => $project->id,
                'permission' => $permission,
            ]),
        );
        $I->seeResponseCodeIsSuccessful();

        $I->assertTrue($abacManager->check($user, $project, $permission));
    }

    public function testForbidden(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $project = $I->haveProjectForLimesurvey();
        $permission = Permission::PERMISSION_ADMIN;

        $I->createAndSetCsrfCookie('abc');
        $I->haveHttpHeader(Request::CSRF_HEADER, \Yii::$app->security->maskToken('abc'));
        $I->sendPost(
            Url::to([
                '/permission/grant',
                'source_name' => User::class,
                'source_id' => TEST_OTHER_USER_ID,
                'target_name' => $project::class,
                'target_id' => $project->id,
                'permission' => $permission,
            ]),
        );
        $I->seeResponseCodeIs(403);
    }
}
