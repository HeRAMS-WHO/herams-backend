<?php


namespace prime\tests\functional\controllers\workspace;

use prime\models\ar\WorkspaceForLimesurvey;
use prime\models\forms\projects\Token;
use prime\tests\FunctionalTester;

/**
 * @covers \prime\controllers\workspace\Import
 */
class ImportCest
{

    public function testAccessControl(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $project = $I->haveProject();
        $I->amOnPage(['workspace/import', 'project_id' => $project->id]);
        $I->seeResponseCodeIs(403);
    }

    public function testImport(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_ADMIN_ID);
        $project = $I->haveProject();

        $I->amOnPage(['workspace/import', 'project_id' => $project->id]);
        $I->seeResponseCodeIs(200);
        $I->selectOption(['name' => 'Import[titleField]'], 'token');
        $I->dontSeeElement('input', [
            'name' => 'Import[tokens][]',
            'value' => $I->haveWorkspace()->getAttribute('token')
        ]);
        $I->submitForm('form', [
            'Import[tokens]' => ['token2']
        ]);
        $I->seeResponseCodeIsSuccessful();
        $I->seeRecord(WorkspaceForLimesurvey::class, [
            'title' => 'token2',
            'project_id' => $project->id,
            'token' => 'token2'
        ]);
    }
}
