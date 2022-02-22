<?php

declare(strict_types=1);

namespace prime\tests\functional\controllers\session;

use prime\models\ar\User;
use prime\models\ar\WorkspaceForLimesurvey;
use prime\models\forms\projects\Token;
use prime\tests\FunctionalTester;
use yii\web\Request;

/**
 * @covers \prime\controllers\session\Delete
 */
class DeleteCest
{
    public function testMethod(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_ADMIN_ID);
        $I->amOnPage(['session/delete']);
        $I->seeResponseCodeIs(405);
    }

    public function testLogout(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_ADMIN_ID);
        $I->stopFollowingRedirects();
        $I->createAndSetCsrfCookie('abc');
        $I->haveHttpHeader(Request::CSRF_HEADER, \Yii::$app->security->maskToken('abc'));
        $I->sendDELETE('/session/delete');
        $I->seeResponseCodeIsRedirection();
        $I->assertTrue(\Yii::$app->user->isGuest);
    }
}
