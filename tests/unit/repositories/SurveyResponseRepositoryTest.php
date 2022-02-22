<?php

declare(strict_types=1);

namespace prime\tests\unit\repositories;

use Codeception\Test\Unit;
use InvalidArgumentException;
use prime\helpers\ModelHydrator;
use prime\interfaces\AccessCheckInterface;
use prime\models\ar\Facility;
use prime\models\ar\SurveyResponse;
use prime\models\forms\surveyResponse\CreateForm;
use prime\repositories\SurveyResponseRepository;
use prime\values\FacilityId;
use prime\values\SurveyId;
use yii\web\NotFoundHttpException;

/**
 * @covers \prime\repositories\SurveyResponseRepository
 */
class SurveyResponseRepositoryTest extends Unit
{
    private function createRepository(
        AccessCheckInterface $accessCheck = null,
        ModelHydrator $modelHydrator = null,
    ): SurveyResponseRepository {
        $accessCheck = $accessCheck ?? $this->getMockBuilder(AccessCheckInterface::class)->disableOriginalConstructor()->getMock();
        $modelHydrator = $modelHydrator ?? new ModelHydrator();

        return new SurveyResponseRepository(
            accessCheck: $accessCheck,
            hydrator: $modelHydrator,
        );
    }

    public function testCreate(): void
    {
        $surveyId = new SurveyId(1);
        $facilityId = new FacilityId('1');
        $model = new CreateForm($surveyId, $facilityId);
        $model->data = ['test' => 'test123'];

        $repository = $this->createRepository();
        $surveyResponseId = $repository->create($model);

        $surveyResponse = SurveyResponse::findOne(['id' => $surveyResponseId->getValue()]);
        $this->assertEquals($surveyId->getValue(), $surveyResponse->survey_id);
        $this->assertEquals($facilityId->getValue(), $surveyResponse->facility_id);
        $this->assertEquals($model->data, $surveyResponse->data);
    }

    public function testCreateInvalid(): void
    {
        $surveyId = new SurveyId(1);
        $facilityId = new FacilityId('1');
        $model = new CreateForm($surveyId, $facilityId);
        $model->data = [];

        $repository = $this->createRepository();
        $this->expectException(InvalidArgumentException::class);
        $repository->create($model);
    }

    public function testCreateFormModel(): void
    {
        $repository = $this->createRepository();

        $surveyId = new SurveyId(12345);
        $facilityId = new FacilityId('23456');

        $model = $repository->createFormModel($surveyId, $facilityId);

        $this->assertEquals($surveyId, $model->getSurveyId());
        $this->assertEquals($facilityId, $model->getFacilityId());
    }

    public function testRetrieveDataSurveyResponseForFacilitySituationUpdate()
    {
        $facility = Facility::find()->one();

        $surveyResponse1 = new SurveyResponse(['survey_id' => $facility->dataSurvey->id, 'facility_id' => $facility->id, 'data' => ['name' => 'Name 1', 'useForSituationUpdate' => 0], 'created_at' => '2021-11-10 15:40:00']);
        $this->assertTrue($surveyResponse1->save());
        $surveyResponse2 = new SurveyResponse(['survey_id' => $facility->dataSurvey->id, 'facility_id' => $facility->id, 'data' => ['name' => 'Name 2', 'useForSituationUpdate' => 1], 'created_at' => '2021-11-10 15:30:00']);
        $this->assertTrue($surveyResponse2->save());
        $surveyResponse3 = new SurveyResponse(['survey_id' => $facility->dataSurvey->id, 'facility_id' => $facility->id, 'data' => ['name' => 'Name 3', 'useForSituationUpdate' => 1], 'created_at' => '2021-11-10 15:20:00']);
        $this->assertTrue($surveyResponse3->save());

        $repository = $this->createRepository();
        $lastResponse = $repository->retrieveDataSurveyResponseForFacilitySituationUpdate(new FacilityId((string) $facility->id));

        $this->assertEquals($surveyResponse2->id, $lastResponse->getId()->getValue());
    }

    public function testRetrieveDataSurveyResponseForFacilitySituationUpdateNoSurveyResponse(): void
    {
        $facility = Facility::find()->one();
        $facilityId = new FacilityId((string) $facility->id);
        $repository = $this->createRepository();

        $lastResponse = $repository->retrieveDataSurveyResponseForFacilitySituationUpdate($facilityId);
        $this->assertNull($lastResponse);
    }

    public function testRetrieveLastAdminSurveyResponseForFacility(): void
    {
        $facility = Facility::find()->one();

        $surveyResponse1 = new SurveyResponse(['survey_id' => $facility->adminSurvey->id, 'facility_id' => $facility->id, 'data' => ['name' => 'Name 1'], 'created_at' => '2021-11-10 15:40:00']);
        $this->assertTrue($surveyResponse1->save());
        $surveyResponse2 = new SurveyResponse(['survey_id' => $facility->adminSurvey->id, 'facility_id' => $facility->id, 'data' => ['name' => 'Name 2'], 'created_at' => '2021-11-10 15:30:00']);
        $this->assertTrue($surveyResponse2->save());

        $repository = $this->createRepository();
        $lastResponse = $repository->retrieveAdminSurveyResponseForFacilityUpdate(new FacilityId((string) $facility->id));

        $this->assertEquals($surveyResponse1->id, $lastResponse->getId()->getValue());
    }

    public function testRetrieveLastAdminSurveyResponseForFacilityNoSurveyResponse(): void
    {
        $facility = Facility::find()->one();
        $facilityId = new FacilityId((string) $facility->id);
        $repository = $this->createRepository();

        $lastResponse = $repository->retrieveAdminSurveyResponseForFacilityUpdate($facilityId);
        $this->assertNull($lastResponse);
    }

    public function testRetrieveLastAdminSurveyResponseForFacilityNotFound(): void
    {
        $facilityId = new FacilityId('12345');
        $repository = $this->createRepository();

        $this->expectException(NotFoundHttpException::class);
        $repository->retrieveAdminSurveyResponseForFacilityUpdate($facilityId);
    }
}
