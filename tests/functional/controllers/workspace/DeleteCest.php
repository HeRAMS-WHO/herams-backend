<?php

namespace prime\tests\functional\controllers\workspace;

use prime\models\ar\Permission;
use prime\models\ar\User;
use prime\models\ar\WorkspaceForLimesurvey;
use prime\tests\FunctionalTester;
use yii\helpers\Url;
use yii\web\Request;

/**
 * @coversNothing
 */
class DeleteCest
{
    public function testAccessControl(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $project = $I->haveProjectForLimesurvey();
        $workspace = $I->haveWorkspaceForLimesurvey();
        $user = User::findOne(['id' => TEST_USER_ID]);
        $I->createAndSetCsrfCookie('abc');
        $I->haveHttpHeader(Request::CSRF_HEADER, \Yii::$app->security->maskToken('abc'));
        $I->sendDELETE(Url::to(['/workspace/delete', 'id' => $workspace->id]));
        $I->seeResponseCodeIs(403);
    }

    public function testDelete(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $workspace = $I->haveWorkspaceForLimesurvey();

        \Yii::$app->abacManager->grant(User::findOne(['id' => TEST_USER_ID]), $workspace, Permission::PERMISSION_DELETE);

        $I->stopFollowingRedirects();
        $I->createAndSetCsrfCookie('abc');
        $I->haveHttpHeader(Request::CSRF_HEADER, \Yii::$app->security->maskToken('abc'));
        $I->sendDELETE(Url::to(['/workspace/delete', 'id' => $workspace->id]));

        $I->seeResponseCodeIs(302);
        $I->dontSeeRecord(WorkspaceForLimesurvey::class, ['id' => $workspace->id]);
    }
}
