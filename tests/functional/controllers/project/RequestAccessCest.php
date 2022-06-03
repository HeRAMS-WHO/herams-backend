<?php

declare(strict_types=1);

namespace prime\tests\functional\controllers\project;

use prime\models\ar\AccessRequest;
use prime\tests\FunctionalTester;

/**
 * @covers \prime\controllers\project\RequestAccess
 * @covers \prime\controllers\ProjectController
 * @covers \prime\models\forms\accessRequest\Create
 */
class RequestAccessCest
{
    public function testCreate(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $project = $I->haveProjectForLimesurvey();

        $I->amOnPage([
            'project/request-access',
            'id' => $project->id,
        ]);
        $I->seeResponseCodeIs(200);
        $I->fillField('Subject', 'Test access request subject');
        $I->fillField('Body', 'Test access request body');
        $I->checkOption('Download data');
        $I->click('Request');
        $I->seeRecord(AccessRequest::class, [
            'target_class' => get_class($project),
            'target_id' => $project->id,
        ]);
    }
}
