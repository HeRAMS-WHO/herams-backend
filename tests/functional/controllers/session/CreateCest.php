<?php
declare(strict_types=1);

namespace prime\tests\functional\controllers\session;

use prime\models\ar\User;
use prime\models\ar\Workspace;
use prime\models\forms\projects\Token;
use prime\tests\FunctionalTester;

class CreateCest
{

    public function testCreate(FunctionalTester $I)
    {
        $I->stopFollowingRedirects();
        $I->amOnPage(['/']);
        $I->seeResponseCodeIs(302);
        $I->startFollowingRedirects();
        $I->amOnPage(['/']);
        $user = new User();
        $user->name = 'test';
        $user->setPassword('test123');
        $user->email = 'abc@def.nl';
        $I->save($user);
        $I->fillField(['css' => '[autocomplete=username]'], $user->email);
        $I->fillField(['css' => '[autocomplete=current-password]'], 'test123');
        $I->assertTrue(\Yii::$app->user->isGuest);
        $I->click('Log in');
        $I->seeResponseCodeIsSuccessful();
        $I->assertFalse(\Yii::$app->user->isGuest);

    }

    public function testRedirectIfAlreadyLoggedIn(FunctionalTester $I)
    {
        $I->amOnPage('/');
        $I->seeCurrentUrlEquals('/session/create');
        $I->amLoggedInAs(TEST_ADMIN_ID);
        $I->stopFollowingRedirects();
        $I->amOnPage($I->grabFromCurrentUrl());
        $I->seeResponseCodeIsRedirection();
        $I->startFollowingRedirects();
        $I->amOnPage($I->grabFromCurrentUrl());
        $I->seeCurrentUrlEquals('/');

    }
    public function testCreateNewToken(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_ADMIN_ID);
        $project = $I->haveProject();
        $I->amOnPage(['workspace/create', 'project_id' => $project->id]);
        $I->seeResponseCodeIs(200);
        $I->fillField(['name' => 'Workspace[title]'], 'Cool stuff');
        $I->selectOption(['name' => 'Workspace[token]'], '');
        $I->click('Create workspace');
        $I->seeResponseCodeIsSuccessful();
        // Find the token.
        /** @var Token[] $tokens */
        $tokens = \Yii::$app->limesurveyDataProvider->getTokens($project->base_survey_eid);
        $I->assertCount(3, $tokens);

        $I->seeRecord(Workspace::class, [
            'title' => 'Cool stuff',
            'tool_id' => $project->id,

            'token' => array_pop($tokens)->getToken()
        ]);
        $I->seeInSource(substr(json_encode('Workspace <strong>Cool stuff</strong> created'), 1, -1));
    }

}