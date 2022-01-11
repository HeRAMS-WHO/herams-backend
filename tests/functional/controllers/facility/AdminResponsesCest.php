<?php

declare(strict_types=1);

namespace prime\tests\functional\controllers\facility;

use prime\models\ar\Facility;
use prime\models\ar\Permission;
use prime\models\ar\Workspace;
use prime\tests\FunctionalTester;
use yii\helpers\Url;

/**
 * @covers \prime\controllers\facility\AdminResponses
 */
class AdminResponsesCest
{
    private function getFacility(FunctionalTester $I): Facility
    {
        $facility = $I->haveFacility();
        $I->grantCurrentUser($facility->workspace, Permission::PERMISSION_SURVEY_DATA);
        return $facility;
    }

    public function testPageLoad(FunctionalTester $I): void
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $facility = $this->getFacility($I);
        $I->amOnPage(['facility/admin-responses', 'id' => $facility->id]);
        $I->seeResponseCodeIsSuccessful();
    }
}
