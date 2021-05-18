<?php
declare(strict_types=1);

namespace prime\tests\functional\controllers\workspace;

use prime\models\ar\Workspace;
use prime\models\forms\projects\Token;
use prime\tests\FunctionalTester;

/**
 * @covers \prime\controllers\workspace\Create
 */
class CreateCest
{

    public function testAccessControl(FunctionalTester $I): void
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $project = $I->haveProject();
        $I->amOnPage(['workspace/create', 'project_id' => $project->id]);
        $I->seeResponseCodeIs(403);
    }

    public function testCreate(FunctionalTester $I): void
    {
        $I->amLoggedInAs(TEST_ADMIN_ID);
        $project = $I->haveProject();
        $I->amOnPage(['workspace/create', 'project_id' => $project->id]);
        $I->seeResponseCodeIs(200);
        $I->fillField(['name' => 'Workspace[title]'], 'Cool stuff');
        $I->selectOption(['name' => 'Workspace[token]'], 'token2');
        $I->click('Create workspace');
        $I->seeResponseCodeIsSuccessful();
        $I->seeRecord(Workspace::class, [
            'title' => 'Cool stuff',
            'tool_id' => $project->id,
            'token' => 'token2'
        ]);
    }

    public function testCreateNewToken(FunctionalTester $I): void
    {
        $I->amLoggedInAs(TEST_ADMIN_ID);
        $project = $I->haveProject();
        $I->amOnPage(['workspace/create', 'project_id' => $project->id]);
        $I->seeResponseCodeIs(200);
        $I->fillField(['name' => 'Workspace[title]'], 'Cool stuff');
        $I->selectOption(['name' => 'Workspace[token]'], '');
        $I->click('Create workspace');
        $I->seeResponseCodeIsSuccessful();
        $I->seeRecord(Workspace::class, [
            'title' => 'Cool stuff',
            'tool_id' => $project->id,
        ]);
        // Find the token.
        /** @var Token[] $tokens */
        $tokens = \Yii::$app->limesurveyDataProvider->getTokens($project->base_survey_eid);
        $I->assertCount(3, $tokens);

        $I->seeRecord(Workspace::class, [
            'title' => 'Cool stuff',
            'tool_id' => $project->id,

            'token' => array_pop($tokens)->getToken()
        ]);
    }
}
