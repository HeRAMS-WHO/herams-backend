<?php


namespace prime\tests\functional\controllers\workspace;

use prime\models\ar\Workspace;
use prime\models\forms\projects\Token;
use prime\tests\FunctionalTester;

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
        $I->uncheckOption(['css' => '[name="Import[tokens][]"][value=token1]']);
        $I->click('Import workspaces');
        $I->seeResponseCodeIsSuccessful();
        $I->seeRecord(Workspace::class, [
            'title' => 'token2',
            'tool_id' => $project->id,
            'token' => 'token2'
        ]);
    }


}