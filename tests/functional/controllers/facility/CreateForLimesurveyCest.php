<?php

declare(strict_types=1);

namespace prime\tests\functional\controllers\facility;

use prime\models\ar\Permission;
use prime\models\ar\WorkspaceForLimesurvey;
use prime\tests\FunctionalTester;

/**
 * @covers \prime\controllers\facility\Create
 *
 * The rest of the process is managed via Limesurvey, this just loads the page
 */
class CreateForLimesurveyCest
{
    private function getWorkspace(FunctionalTester $I): WorkspaceForLimesurvey
    {
        $workspace = $I->haveWorkspaceForLimesurvey();
        $I->grantCurrentUser($workspace, Permission::PERMISSION_SURVEY_DATA);
        $I->grantCurrentUser($workspace, Permission::PERMISSION_CREATE_FACILITY);
        return $workspace;
    }

    public function testPageLoad(FunctionalTester $I): void
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $workspace = $this->getWorkspace($I);
        $I->amOnPage([
            'facility/create',
            'workspaceId' => $workspace->id,
        ]);
        $I->seeResponseCodeIsSuccessful();
        $I->seeElement('iframe[name="limesurvey"]');
    }
}
