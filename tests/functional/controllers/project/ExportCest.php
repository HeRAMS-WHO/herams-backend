<?php

declare(strict_types=1);

namespace prime\tests\functional\controllers\project;

use herams\common\domain\user\User;
use herams\common\models\PermissionOld;
use prime\tests\FunctionalTester;

/**
 * @covers \prime\actions\ExportAction
 */
class ExportCest
{
    public function testNotFound(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $I->amOnPage([
            'project/export',
            'id' => 12345,
        ]);
        $I->seeResponseCodeIs(404);
    }

    public function testDownload(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $workspace = $I->haveProject();
        \Yii::$app->abacManager->grant(User::findOne([
            'id' => TEST_USER_ID,
        ]), $workspace, PermissionOld::PERMISSION_EXPORT);

        $I->amOnPage([
            'project/export',
            'id' => $workspace->id,
        ]);
        $I->seeResponseCodeIs(200);
        $I->click('Export CSV');
        $I->seeResponseCodeIsSuccessful();
        $I->assertSame('text/csv', $I->grabHttpHeader('Content-Type', true));
        $I->assertNotEmpty($I->grabResponse());
    }
}
