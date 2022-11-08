<?php

declare(strict_types=1);

namespace prime\tests\functional\controllers\user;

use herams\common\models\Project;
use prime\models\ar\AccessRequest;
use prime\tests\FunctionalTester;

/**
 * @covers \prime\controllers\user\AccessRequests
 * @covers \prime\controllers\UserController
 */
class AccessRequestsCest
{
    protected function createAccessRequest(FunctionalTester $I, Project $target): AccessRequest
    {
        $accessRequest = new AccessRequest([
            'subject' => 'test',
            'body' => 'test',
            'target' => $target,
            'permissions' => [AccessRequest::PERMISSION_WRITE],
        ]);
        $I->save($accessRequest);
        return $accessRequest;
    }

    public function testOutstandingRequest(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $project = $I->haveProject();
        $accessRequest = $this->createAccessRequest($I, $project);
        $I->amOnPage(['user/access-requests']);
        $I->see($accessRequest->subject);

        $I->amLoggedInAs(TEST_OTHER_USER_ID);
        $I->amOnPage(['user/access-requests']);
        $I->dontSee($accessRequest->subject);
    }

    public function testPageLoad(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $I->amOnPage(['user/notifications']);
        $I->seeResponseCodeIsSuccessful();
    }
}
