<?php

declare(strict_types=1);

namespace prime\tests\functional\controllers\page;

use prime\models\ar\Page;
use prime\models\ar\Project;
use prime\tests\FunctionalTester;
use yii\helpers\Url;
use yii\web\Request;

/**
 * @covers \prime\actions\DeleteAction
 */
class DeleteCest
{
    public function _before(FunctionalTester $I)
    {
        $I->assertTrue(\Yii::$app->authManager->checkAccess(TEST_ADMIN_ID, 'admin'));
        $I->assertFalse(\Yii::$app->authManager->checkAccess(TEST_USER_ID, 'admin'));
    }

    public function testAccessControl(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_ADMIN_ID);
        $I->createAndSetCsrfCookie('abc');
        $I->haveHttpHeader(Request::CSRF_HEADER, \Yii::$app->security->maskToken('abc'));
        $project = $I->haveProjectForLimesurvey();
        $parentPage = new Page();
        $parentPage->title = 'parent';
        $parentPage->project_id = $project->id;
        $I->save($parentPage);

        $I->amLoggedInAs(TEST_USER_ID);
        $I->sendDELETE(Url::to(['/page/delete', 'id' => $parentPage->id]));
        $I->seeResponseCodeIs(403);
    }

    public function testDeleteRootPageWithChildren(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_ADMIN_ID);
        $I->stopFollowingRedirects();
        $project = $I->haveProjectForLimesurvey();
        $parentPage = new Page();
        $parentPage->title = 'parent';
        $parentPage->project_id = $project->id;
        $I->save($parentPage);

        $childPage = new Page();
        $childPage->title = 'child';
        $childPage->project_id = $project->id;
        $childPage->parent_id = $parentPage->id;
        $I->save($childPage);

        $I->createAndSetCsrfCookie('abc');
        $I->haveHttpHeader(Request::CSRF_HEADER, \Yii::$app->security->maskToken('abc'));

        $I->sendDELETE(Url::to(['/page/delete', 'id' => $parentPage->id]));
        $I->seeResponseCodeIs(302);
        $I->assertFalse($childPage->refresh());
        $I->assertFalse($parentPage->refresh());
    }

    public function testDeleteSubPage(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_ADMIN_ID);
        $I->stopFollowingRedirects();
        $project = $I->haveProjectForLimesurvey();
        $parentPage = new Page();
        $parentPage->title = 'parent';
        $parentPage->project_id = $project->id;
        $I->save($parentPage);

        $childPage = new Page();
        $childPage->title = 'child';
        $childPage->project_id = $project->id;
        $childPage->parent_id = $parentPage->id;
        $I->save($childPage);

        $I->createAndSetCsrfCookie('abc');
        $I->haveHttpHeader(Request::CSRF_HEADER, \Yii::$app->security->maskToken('abc'));

        $I->sendDELETE(Url::to(['/page/delete', 'id' => $childPage->id]));
        $I->seeResponseCodeIs(302);
        $I->assertFalse($childPage->refresh());
        $I->assertTrue($parentPage->refresh());
    }
}
