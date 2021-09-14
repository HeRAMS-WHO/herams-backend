<?php
declare(strict_types=1);

namespace prime\tests\functional\controllers\accessRequest;

use prime\models\ar\AccessRequest;
use prime\models\ar\Permission;
use prime\models\ar\Project;
use prime\models\ar\User;
use prime\models\ar\Workspace;
use prime\tests\FunctionalTester;

/**
 * @covers \prime\controllers\accessRequest\Index
 * @covers \prime\controllers\AccessRequestController
 * @covers \prime\models\search\AccessRequest
 */
class IndexCest
{
    protected function createAccessRequest(FunctionalTester $I, Project|Workspace $target, ?string $response): AccessRequest
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

    public function testAccessRequestHistory(FunctionalTester $I)
    {
        $project = $I->haveProject();

        $I->amLoggedInAs(TEST_USER_ID);
        $accessRequest = $this->createAccessRequest($I, $project, 'History');

        $I->amLoggedInAs(TEST_OTHER_USER_ID);
        $I->amOnPage(['access-request/index']);
        $I->dontSee($accessRequest->subject);

        $I->grantCurrentUser($project, Permission::PERMISSION_ADMIN);
        $I->amOnPage(['access-request/index']);
        $I->see($project->title);
        $I->see(User::findOne(['id' => TEST_USER_ID])->name);
    }

    public function testAccessRequestToRespondTo(FunctionalTester $I)
    {
        $project = $I->haveProject();

        $I->amLoggedInAs(TEST_USER_ID);
        $accessRequest = $this->createAccessRequest($I, $project, null);

        $I->amLoggedInAs(TEST_OTHER_USER_ID);
        $I->amOnPage(['access-request/index']);
        $I->dontSee($accessRequest->subject);

        $I->grantCurrentUser($project, Permission::PERMISSION_ADMIN);
        $I->amOnPage(['access-request/index']);
        $I->see($project->title);
        $I->see(User::findOne(['id' => TEST_USER_ID])->name);
    }
}
