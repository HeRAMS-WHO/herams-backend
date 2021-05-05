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
 * @covers \prime\controllers\accessRequest\Respond
 * @covers \prime\controllers\AccessRequestController
 * @covers \prime\models\forms\accessRequest\Respond
 */
class RespondCest
{
    protected function createAccessRequest(FunctionalTester $I, Project|Workspace $target): AccessRequest
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

    public function testNotAllowed(FunctionalTester $I)
    {
        $project = $I->haveProject();

        $I->amLoggedInAs(TEST_USER_ID);
        $accessRequest = $this->createAccessRequest($I, $project);

        $I->amLoggedInAs(TEST_OTHER_USER_ID);
        $I->amOnPage(['access-request/respond', 'id' => $accessRequest->id]);
        $I->seeResponseCodeIs(403);
    }

    public function testReject(FunctionalTester $I)
    {
        $project = $I->haveProject();

        $I->amLoggedInAs(TEST_USER_ID);
        $accessRequest = $this->createAccessRequest($I, $project);

        $I->amLoggedInAs(TEST_OTHER_USER_ID);
        $I->grantCurrentUser($project, Permission::PERMISSION_ADMIN);

        $I->dontSeeRecord(Permission::class, ['source' => User::class, 'source_id' => TEST_USER_ID, 'target' => $project::class, 'target_id' => $project->id]);

        $I->amOnPage(['access-request/respond', 'id' => $accessRequest->id]);
        $I->seeResponseCodeIsSuccessful();

        $I->fillField('Explanation of grant or deny', 'Sorry no access');
        $I->click('Respond');

        $accessRequest->refresh();
        $I->assertFalse((bool) $accessRequest->accepted);

        $I->dontSeeRecord(Permission::class, ['source' => User::class, 'source_id' => TEST_USER_ID, 'target' => $project::class, 'target_id' => $project->id]);
    }

    public function testGrant(FunctionalTester $I)
    {
        $project = $I->haveProject();

        $I->amLoggedInAs(TEST_USER_ID);
        $accessRequest = $this->createAccessRequest($I, $project);

        $I->amLoggedInAs(TEST_OTHER_USER_ID);
        $I->grantCurrentUser($project, Permission::PERMISSION_ADMIN);

        $I->dontSeeRecord(Permission::class, ['source' => User::class, 'source_id' => TEST_USER_ID, 'target' => $project::class, 'target_id' => $project->id]);

        $I->amOnPage(['access-request/respond', 'id' => $accessRequest->id]);
        $I->seeResponseCodeIsSuccessful();

        $I->fillField('Explanation of grant or deny', 'Go ahead');
        $I->checkOption('Edit data');
        $I->click('Respond');

        $accessRequest->refresh();
        $I->assertTrue((bool) $accessRequest->accepted);

        $I->seeRecord(Permission::class, ['source' => User::class, 'source_id' => TEST_USER_ID, 'target' => $project::class, 'target_id' => $project->id]);
    }
}
