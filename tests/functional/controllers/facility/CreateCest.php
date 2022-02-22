<?php

declare(strict_types=1);

namespace prime\tests\functional\controllers\facility;

use prime\models\ar\Facility;
use prime\models\ar\Permission;
use prime\models\ar\Workspace;
use prime\tests\FunctionalTester;
use yii\helpers\Url;

/**
 * @covers \prime\controllers\facility\Create
 */
class CreateCest
{
    private function getWorkspace(FunctionalTester $I): Workspace
    {
        $workspace = $I->haveWorkspace();
        $I->grantCurrentUser($workspace, Permission::PERMISSION_SURVEY_DATA);
        $I->grantCurrentUser($workspace, Permission::PERMISSION_CREATE_FACILITY);
        return $workspace;
    }

    public function testPageLoad(FunctionalTester $I): void
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $workspace = $this->getWorkspace($I);
        $I->amOnPage(['facility/create', 'workspaceId' => $workspace->id]);
        $I->seeResponseCodeIsSuccessful();
    }

    public function testPost(FunctionalTester $I): void
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $workspace = $this->getWorkspace($I);
        $name = 'Test facility';
        $I->dontSeeRecord(Facility::class, ['workspace_id' => $workspace->id]);
        $I->sendPostWithCsrf(Url::to(['/facility/create', 'workspaceId' => $workspace->id]), ['data' => ['name' => $name]]);
        $I->seeRecord(Facility::class, ['workspace_id' => $workspace->id, 'name' => $name]);

        $I->seeResponseCodeIsSuccessful();
    }

    public function testPostInvalid(FunctionalTester $I): void
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $workspace = $this->getWorkspace($I);
        $I->sendPostWithCsrf(Url::to(['/facility/create', 'workspaceId' => $workspace->id]), ['data' => []]);
        $I->seeResponseCodeIs(400);
    }
}
