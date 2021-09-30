<?php
declare(strict_types=1);

namespace prime\tests\functional\modules\api\user;

use prime\models\ar\Favorite;
use prime\models\ar\Permission;
use prime\models\ar\WorkspaceForLimesurvey;
use prime\tests\FunctionalTester;
use yii\helpers\Url;

/**
 * Class WorkspacesCest
 * @package prime\tests\functional\modules\api\user
 * @covers \prime\modules\Api\controllers\user\Workspaces
 */
class WorkspacesCest
{

    public function testPrecondition(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $I->assertFalse(\Yii::$app->user->can(Permission::PERMISSION_ADMIN));
        $I->assertTrue(\YiI::$app->user->can(Permission::PERMISSION_MANAGE_FAVORITES, \Yii::$app->user->identity));
    }

    public function testCreate(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);

        $I->assertSame(0, (int) Favorite::find()->count());
        $I->sendPUT(Url::to(['/api/user/workspaces', 'id' => TEST_USER_ID, 'target_id' => 123123]));
        $I->seeResponseCodeIs(422);
        $I->assertSame(0, (int) Favorite::find()->count());

        $workspace = $I->haveWorkspace();
        $I->assertSame(0, (int) Favorite::find()->count());
        $I->sendPUT(Url::to(['/api/user/workspaces', 'id' => TEST_USER_ID, 'target_id' => $workspace->id]));
        $I->seeResponseCodeIs(201);
        $I->assertSame(1, (int) Favorite::find()->count());
    }

    public function testDelete(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);

        $I->assertSame(0, (int) Favorite::find()->count());
        $I->sendDELETE(Url::to(['/api/user/workspaces', 'id' => TEST_USER_ID, 'target_id' => 123123]));
        $I->seeResponseCodeIs(200);
        $I->assertSame(0, (int) Favorite::find()->count());

        $workspace = $I->haveWorkspace();
        codecept_debug('Created WS with id: ' . $workspace->id);
        $favorite = new Favorite();
        $favorite->target_class = WorkspaceForLimesurvey::class;
        $favorite->target_id = $workspace->id;
        $favorite->user_id = TEST_USER_ID;
        $I->save($favorite);

        $I->assertSame(1, (int) Favorite::find()->count());
        $I->sendDELETE(Url::to(['/api/user/workspaces', 'id' => TEST_USER_ID, 'target_id' => $workspace->id]));
        $I->seeResponseCodeIs(200);
        $I->assertSame(0, (int) Favorite::find()->count());
    }
}
