<?php

declare(strict_types=1);

namespace prime\tests\functional\controllers\facility;

use herams\common\domain\facility\Facility;
use herams\common\models\PermissionOld;
use prime\tests\FunctionalTester;
use yii\helpers\Url;

/**
 * @covers \prime\controllers\facility\UpdateSituation
 */
class UpdateSituationCest
{
    private function getFacility(FunctionalTester $I): Facility
    {
        $facility = $I->haveFacility();
        $workspace = $facility->workspace;
        $I->grantCurrentUser($workspace, PermissionOld::PERMISSION_SURVEY_DATA);

        return $facility;
    }

    public function testPageLoad(FunctionalTester $I): void
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $facility = $this->getFacility($I);
        $I->amOnPage([
            'facility/update-situation',
            'id' => $facility->id,
        ]);
        $I->seeResponseCodeIsSuccessful();
    }

    public function testPost(FunctionalTester $I): void
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $facility = $this->getFacility($I);
        $I->assertEquals(0, $facility->dataSurvey->getSurveyResponses()->andWhere([
            'facility_id' => $facility->id,
        ])->count());
        $I->sendPostWithCsrf(Url::to([
            '/facility/update-situation',
            'id' => $facility->id,
        ]), [
            'data' => [
                'page1' => [
                    'Q1' => 'A1',

                ],
            ],
        ]);
        $I->seeResponseCodeIsSuccessful();

        $facility->refresh();
        $I->assertEquals(1, $facility->dataSurvey->getSurveyResponses()->andWhere([
            'facility_id' => $facility->id,
        ])->count());
    }

    public function testPostInvalid(FunctionalTester $I): void
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $facility = $this->getFacility($I);
        $I->assertEquals(0, $facility->dataSurvey->getSurveyResponses()->andWhere([
            'facility_id' => $facility->id,
        ])->count());
        $I->sendPostWithCsrf(Url::to([
            '/facility/update-situation',
            'id' => $facility->id,
        ]), [
            'data' => '{}',
        ]);
        $I->seeResponseCodeIs(422);
    }
}
