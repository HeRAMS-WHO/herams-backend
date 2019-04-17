<?php


namespace prime\tests\functional\controllers\project;

use prime\models\ar\Project;
use prime\models\ar\User;
use prime\models\permissions\Permission;
use prime\tests\FunctionalTester;
use yii\helpers\Json;

class CreateCest
{

    public function testAccessControl(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $I->amOnPage(['project/create']);
        $I->seeResponseCodeIs(403);
    }

    public function testCreate(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_ADMIN_ID);
        $I->amOnPage(['project/create']);
        $I->seeResponseCodeIs(200);
        $I->fillField(['name' => 'Project[title]'], 'Cool stuff');
        $I->selectOption(['name' => 'Project[base_survey_eid]'], 11111);
        $I->click('Create project');
        $I->seeResponseCodeIsSuccessful();
        $I->seeRecord(Project::class, [
            'title' => 'Cool stuff',
            'base_survey_eid' => 11111
        ]);

        $I->seeInSource(substr(json_encode('Project <strong>Cool stuff</strong> created'), 1, -1));
    }

}