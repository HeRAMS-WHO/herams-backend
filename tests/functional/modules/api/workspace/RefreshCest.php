<?php
declare(strict_types=1);

namespace prime\tests\functional\modules\api\workspace;

use prime\components\LimesurveyDataProvider;
use prime\models\ar\Permission;
use prime\tests\FunctionalTester;
use yii\helpers\Url;
use function iter\toArrayWithKeys;

/**
 * @covers \prime\modules\Api\controllers\workspace\Import
 */
class RefreshCest
{

    public function testPermissionCheck(FunctionalTester $I): void
    {
        $workspace = $I->haveWorkspace();
        $workspace->token = 'token1';
        $I->save($workspace);
        $I->amLoggedInAs(TEST_USER_ID);
        $I->sendPost(Url::to(['/api/workspace/refresh', 'id' => $workspace->id]));
        $I->seeResponseCodeIs(403);
        $I->grantCurrentUser($workspace, Permission::PERMISSION_ADMIN);
        $I->sendPost(Url::to(['/api/workspace/refresh', 'id' => $workspace->id]));
        $I->seeResponseCodeIs(200);
    }
}
