<?php

declare(strict_types=1);

namespace prime\tests\functional\controllers\element;

use prime\models\ar\Permission;
use prime\tests\FunctionalTester;
use yii\helpers\Url;
use yii\web\Request;

/**
 * @covers \prime\actions\DeleteAction
 */
class DeleteCest
{
    public function testDeleteButtonOnPageUpdatePage(FunctionalTester $I): void
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $element = $I->haveElement();
        $I->grantCurrentUser($element->project, Permission::PERMISSION_MANAGE_DASHBOARD);

        $I->amOnPage(['page/update', 'id' => $element->page_id]);
        $I->seeResponseCodeIs(200);

        $I->seeElement('[href="' . Url::to(['element/delete', 'id' => $element->id]) . '"]');
    }

    public function testDelete(FunctionalTester $I): void
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $element = $I->haveElement();

        // Somehow this is needed to have an active controller
        $I->createAndSetCsrfCookie('abc');
        $I->haveHttpHeader(Request::CSRF_HEADER, \Yii::$app->security->maskToken('abc'));
        $I->amOnPage(['/']);

        $I->sendDelete(Url::to(['element/delete', 'id' => $element->id]));
        $I->seeResponseCodeIs(403);

        $I->grantCurrentUser($element->project, Permission::PERMISSION_MANAGE_DASHBOARD);
        $I->sendDelete(Url::to(['element/delete', 'id' => $element->id]));
        $I->seeResponseCodeIs(200);
    }
}
