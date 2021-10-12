<?php
declare(strict_types=1);

namespace prime\tests\functional\controllers\workspace;

use prime\models\ar\WorkspaceForLimesurvey;
use prime\models\forms\workspace\CreateForLimesurvey;
use prime\tests\FunctionalTester;
use yii\helpers\Html;

/**
 * @covers \prime\controllers\workspace\Create
 */
class CreateCest
{
    public function _before(FunctionalTester $I)
    {
        \Yii::$app->auditService->disable();
    }

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
        $I->fillField(['name' => Html::getInputName(new CreateForLimesurvey(), 'title')], 'Cool stuff');
        $I->selectOption(['name' => Html::getInputName(new CreateForLimesurvey(), 'token')], 'token2');
        $I->click('Create workspace');
        $I->seeResponseCodeIsSuccessful();
        $I->seeRecord(WorkspaceForLimesurvey::class, [
            'title' => 'Cool stuff',
            'project_id' => $project->id,
            'token' => 'token2'
        ]);
    }

    public function testCreateNewToken(FunctionalTester $I): void
    {
        $I->amLoggedInAs(TEST_ADMIN_ID);
        $project = $I->haveProject();
        $I->amOnPage(['workspace/create', 'project_id' => $project->id]);
        $I->seeResponseCodeIs(200);
        $I->fillField(['name' => Html::getInputName(new CreateForLimesurvey(), 'title')], 'Cool stuff');
        $I->selectOption(['name' => Html::getInputName(new CreateForLimesurvey(), 'token')], '');
        $I->click('Create workspace');
        $I->seeResponseCodeIsSuccessful();
        $I->seeRecord(WorkspaceForLimesurvey::class, [
            'title' => 'Cool stuff',
            'project_id' => $project->id,
        ]);
        // Find the token.
        /** @var Token[] $tokens */
        $tokens = \Yii::$app->limesurveyDataProvider->getTokens($project->base_survey_eid);
        $I->assertCount(3, $tokens);

        $I->seeRecord(WorkspaceForLimesurvey::class, [
            'title' => 'Cool stuff',
            'project_id' => $project->id,

            'token' => array_pop($tokens)->getToken()
        ]);
    }
}
