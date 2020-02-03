<?php


namespace prime\tests\functional\controllers\workspace;

use prime\models\ar\Project;
use prime\models\ar\Response;
use prime\models\ar\User;
use prime\models\permissions\Permission;
use prime\tests\FunctionalTester;
use yii\helpers\Json;

class ExportCest
{
    public function testNotFound(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $I->amOnPage(['workspace/export', 'id' => 12345]);
        $I->seeResponseCodeIs(404);
    }

    public function testDownload(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $workspace = $I->haveWorkspace();
        $response = new Response();
        $response->workspace_id = $workspace->id;
        $response->date = '2020-02-03';
        $response->hf_id = 'abcdef';
        $response->survey_id = 12345;
        $response->id = 1;
        $I->save($response);
        \Yii::$app->abacManager->grant(User::findOne(['id' => TEST_USER_ID]), $workspace, Permission::PERMISSION_EXPORT);

        $I->amOnPage(['workspace/export', 'id' => $workspace->id]);
        $I->seeResponseCodeIs(200);

        $I->seeResponseCodeIsSuccessful();
    }
}