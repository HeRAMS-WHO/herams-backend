<?php

declare(strict_types=1);

namespace prime\tests\functional\controllers\session;

use prime\models\ar\User;
use prime\models\ar\WorkspaceForLimesurvey;
use prime\models\forms\projects\Token;
use prime\tests\FunctionalTester;

/**
 * @covers \prime\controllers\session\Create
 */
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
}
