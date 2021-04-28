<?php
declare(strict_types=1);

namespace prime\tests\functional\controllers\workspace;

use prime\models\ar\AccessRequest;
use prime\tests\FunctionalTester;

class RequestAccessCest
{
    public function testCreate(FunctionalTester $I)
    {
        $workspace = $I->haveWorkspace();

        $I->amLoggedInAs(TEST_USER_ID);
        $I->amOnPage(['workspace/request-access', 'id' => $workspace->id]);
        $I->seeResponseCodeIs(200);
        $I->fillField('Subject', 'Test access request subject');
        $I->fillField('Body', 'Test access request body');
        $I->checkOption('Download data');
        $I->click('Request');

        $I->seeRecord(AccessRequest::class, ['target_class' => get_class($workspace), 'target_id' => $workspace->id]);
    }
}
