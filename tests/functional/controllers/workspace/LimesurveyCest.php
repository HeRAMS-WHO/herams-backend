<?php


namespace prime\tests\functional\controllers\workspace;

use prime\models\ar\Permission;
use prime\models\ar\Project;
use prime\models\ar\User;
use prime\tests\FunctionalTester;

/**
 * @covers \prime\controllers\workspace\Limesurvey
 */
class LimesurveyCest
{

    public function testAccessControl(FunctionalTester $I): void
    {
        $I->amLoggedInAs(TEST_USER_ID);

        $project = $I->haveProject();
        $project->visibility = Project::VISIBILITY_PRIVATE;
        $workspace = $I->haveWorkspace();


        $I->assertUserCanNot($workspace, Permission::PERMISSION_LIST_FACILITIES);

        $I->amOnPage(['workspace/limesurvey', 'id' => $workspace->id]);
        $I->seeResponseCodeIs(403);

        $I->grantCurrentUser($workspace, Permission::PERMISSION_LIST_FACILITIES);
        $I->amOnPage(['workspace/limesurvey', 'id' => $workspace->id]);
        $I->seeResponseCodeIs(200);
    }

    public function testNotFound(FunctionalTester $I): void
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $I->amOnPage(['workspace/limesurvey', 'id' => 12345]);
        $I->seeResponseCodeIs(404);
    }

    public function testIframeIsRendered(FunctionalTester $I): void
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $workspace = $I->haveWorkspace();
        $I->grantCurrentUser($workspace, Permission::PERMISSION_SURVEY_DATA);
        $I->amOnPage(['workspace/limesurvey', 'id' => $workspace->id]);
        $I->seeResponseCodeIs(200);

        $I->seeElement('iframe');
    }

    public function testNewFacilityLinkIsCorrect(FunctionalTester $I): void
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $workspace = $I->haveWorkspace();
        $I->grantCurrentUser($workspace, Permission::PERMISSION_ADMIN);
        $I->amOnPage(['workspace/limesurvey', 'id' => $workspace->id]);
        $I->seeResponseCodeIs(200);
        $I->assertStringContainsString($workspace->project->base_survey_eid, $I->grabAttributeFrom('a[target=limesurvey]', 'href'));
    }
}
