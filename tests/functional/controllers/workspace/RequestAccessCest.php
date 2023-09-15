<?php

declare(strict_types=1);

namespace prime\tests\functional\controllers\workspace;

use herams\common\models\PermissionOld;
use herams\common\models\Workspace;
use prime\models\ar\AccessRequest;
use prime\tests\FunctionalTester;

/**
 * @covers \prime\controllers\workspace\RequestAccess
 * @covers \prime\controllers\WorkspaceController
 * @covers \prime\models\forms\accessRequest\Create
 */
class RequestAccessCest
{
    public function testCreate(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);

        $workspace = $I->haveWorkspace();

        $I->assertUserCan($workspace->project, PermissionOld::PERMISSION_SUMMARY);
        $I->amOnPage([
            'workspace/request-access',
            'id' => $workspace->id,
        ]);

        $I->seeResponseCodeIs(200);
        $I->fillField('Subject', 'Test access request subject');
        $I->fillField('Body', 'Test access request body');
        $I->checkOption('Download data');
        $I->click('Request');

        $I->seeRecord(AccessRequest::class, [
            'target_class' => Workspace::class,
            'target_id' => $workspace->id,
        ]);
    }
}
