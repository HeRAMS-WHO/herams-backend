<?php

declare(strict_types=1);

namespace prime\tests\functional\repositories;

use prime\helpers\ModelHydrator;
use prime\interfaces\AccessCheckInterface;
use prime\models\ar\SurveyResponse;
use prime\repositories\SurveyResponseRepository;
use prime\tests\FunctionalTester;
use prime\values\FacilityId;

/**
 * @covers \prime\repositories\SurveyResponseRepository
 */
class SurveyResponseRepositoryCest
{
    private function createRepository(
        AccessCheckInterface $accessCheck = null,
        ModelHydrator $modelHydrator = null,
    ): SurveyResponseRepository {
        $accessCheck = \Yii::createObject(AccessCheckInterface::class);
        $modelHydrator = $modelHydrator ?? new ModelHydrator();

        return new SurveyResponseRepository(
            accessCheck: $accessCheck,
            hydrator: $modelHydrator,
        );
    }

    public function testSearchAdminInFacility(FunctionalTester $I): void
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $repository = $this->createRepository();

        // This creates an admin response already
        $facility = $I->haveFacility();

        $dataProvider = $repository->searchAdminInFacility(new FacilityId((string) $facility->id));
        $I->assertEquals(1, $dataProvider->getTotalCount());

        $dataResponse = new SurveyResponse([
            'data' => [
                'name' => 'Test',
            ],
            'facility_id' => $facility->id,
            'survey_id' => $facility->workspace->project->data_survey_id,
        ]);
        $I->assertTrue($dataResponse->save());

        $dataResponse = new SurveyResponse([
            'data' => [
                'name' => 'Test',
            ],
            'facility_id' => $facility->id,
            'survey_id' => $facility->workspace->project->admin_survey_id,
        ]);
        $I->assertTrue($dataResponse->save());

        $dataProvider = $repository->searchAdminInFacility(new FacilityId((string) $facility->id));
        $I->assertEquals(2, $dataProvider->getTotalCount());
    }

    public function testSearchDataInFacility(FunctionalTester $I): void
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $repository = $this->createRepository();

        // This creates an admin response already
        $facility = $I->haveFacility();

        $dataProvider = $repository->searchDataInFacility(new FacilityId((string) $facility->id));
        $I->assertEquals(0, $dataProvider->getTotalCount());

        $dataResponse = new SurveyResponse([
            'data' => [
                'name' => 'Test',
            ],
            'facility_id' => $facility->id,
            'survey_id' => $facility->workspace->project->data_survey_id,
        ]);
        $I->assertTrue($dataResponse->save());

        $dataProvider = $repository->searchDataInFacility(new FacilityId((string) $facility->id));
        $I->assertEquals(1, $dataProvider->getTotalCount());
    }
}
