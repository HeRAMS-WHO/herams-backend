<?php

declare(strict_types=1);

namespace prime\tests\functional\controllers\facility;

use prime\models\ar\Facility;
use prime\models\ar\Permission;
use prime\models\ar\Workspace;
use prime\tests\FunctionalTester;
use yii\helpers\Url;

/**
 * @covers \prime\controllers\facility\Update
 */
class UpdateCest
{
    private function getFacility(FunctionalTester $I): Facility
    {
        $facility = $I->haveFacility();
        $workspace = $facility->workspace;
        $I->grantCurrentUser($workspace, Permission::PERMISSION_SURVEY_DATA);

        return $facility;
    }

    public function testPageLoad(FunctionalTester $I): void
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $facility = $this->getFacility($I);
        $I->amOnPage([
            'facility/update',
            'id' => $facility->id,
        ]);
        $I->seeResponseCodeIsSuccessful();
    }

    public function testPost(FunctionalTester $I): void
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $facility = $this->getFacility($I);
        $name = 'Updated facility name';
        $I->assertEquals(1, $facility->adminSurvey->getSurveyResponses()->andWhere([
            'facility_id' => $facility->id,
        ])->count());
        $I->sendPostWithCsrf(Url::to([
            '/facility/update',
            'id' => $facility->id,
        ]), [
            'data' => [
                'name' => $name,

            ],
        ]);
        $I->seeResponseCodeIsSuccessful();

        $facility->refresh();
        $I->assertEquals($name, $facility->name);
        $I->assertEquals(2, $facility->adminSurvey->getSurveyResponses()->andWhere([
            'facility_id' => $facility->id,
        ])->count());
    }

    public function testPostInvalid(FunctionalTester $I): void
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $facility = $this->getFacility($I);
        $I->assertEquals(1, $facility->adminSurvey->getSurveyResponses()->andWhere([
            'facility_id' => $facility->id,
        ])->count());
        $I->sendPostWithCsrf(Url::to([
            '/facility/update',
            'id' => $facility->id,
        ]), [
            'data' => '{}',
        ]);
        $I->seeResponseCodeIs(422);
    }
}
