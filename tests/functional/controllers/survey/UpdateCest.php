<?php

declare(strict_types=1);

namespace prime\tests\functional\controllers\survey;

use prime\tests\FunctionalTester;

/**
 * @covers \prime\controllers\survey\Update
 */
class UpdateCest
{
    public function testPageLoad(FunctionalTester $I): void
    {
        $I->amLoggedInAs(TEST_ADMIN_ID);
        $survey = $I->haveSurvey();
        $I->amOnPage(['/survey/update', 'id' => $survey->id]);
        $I->seeResponseCodeIsSuccessful();
    }
}
