<?php

declare(strict_types=1);

namespace prime\tests\unit\repositories;

use Codeception\Test\Unit;
use Collecthor\SurveyjsParser\SurveyParser;
use prime\helpers\ModelHydrator;
use prime\interfaces\AccessCheckInterface;
use prime\models\ar\Facility;
use prime\models\ar\Permission;
use prime\models\ar\SurveyResponse;
use prime\models\forms\facility\CreateForm;
use prime\models\forms\facility\UpdateForm;
use prime\models\forms\facility\UpdateSituationForm;
use prime\models\forms\surveyResponse\CreateForm as SurveyResponseCreateForm;
use prime\models\survey\SurveyForSurveyJs;
use prime\models\surveyResponse\SurveyResponseForSurveyJs;
use prime\models\workspace\WorkspaceForCreateOrUpdateFacility;
use prime\objects\enums\ProjectType;
use prime\objects\LanguageSet;
use prime\repositories\FacilityRepository;
use prime\repositories\SurveyRepository;
use prime\repositories\SurveyResponseRepository;
use prime\repositories\WorkspaceRepository;
use prime\tests\_helpers\Survey;
use prime\values\FacilityId;
use prime\values\ProjectId;
use prime\values\SurveyId;
use prime\values\SurveyResponseId;
use prime\values\WorkspaceId;

/**
 * @covers \prime\repositories\FacilityRepository
 */
class FacilityRepositoryTest extends Unit
{
    private function createRepository(
        AccessCheckInterface $accessCheck = null,
        ModelHydrator $modelHydrator = null,
        SurveyRepository $surveyRepository = null,
        SurveyResponseRepository $surveyResponseRepository = null,
        WorkspaceRepository $workspaceRepository = null,
    ): FacilityRepository {
        $newAccessCheck = $this->getMockBuilder(AccessCheckInterface::class)->getMock();
        $newAccessCheck->expects($this->any())
            ->method('checkPermission')
            ->willReturn(true);

        if (is_null($accessCheck)) {
            $accessCheck = $newAccessCheck;
        }

        return new FacilityRepository(
            $accessCheck,
            $modelHydrator ?? new ModelHydrator(),
            $surveyRepository ?? new SurveyRepository(new SurveyParser(), $newAccessCheck, new ModelHydrator()),
            $surveyResponseRepository ?? new SurveyResponseRepository($newAccessCheck, new ModelHydrator()),
            $workspaceRepository ?? new WorkspaceRepository($newAccessCheck, new ModelHydrator()),
        );
    }

    public function isOfTypeDataProvider(): array
    {
        return [
            [
                1,
                ProjectType::surveyJs(),
                true,
            ],
            [
                'LS_1_a1b',
                ProjectType::limesurvey(),
                true,
            ],
            [
                1,
                ProjectType::limesurvey(),
                false,
            ],
            [
                'LS_1_a1b',
                ProjectType::surveyJs(),
                false,
            ],
        ];
    }

    public function testCreate(): void
    {
        $surveyForSurveyJs = new SurveyForSurveyJs(new SurveyId(12345), ['pages' => []]);
        $model = new CreateForm(
            LanguageSet::fullSet(),
            $surveyForSurveyJs,
            new WorkspaceId(1),
        );
        $name = 'Test new created facility';
        $model->data = ['name' => $name];

        $surveyResponseCreateFormModel = new SurveyResponseCreateForm($surveyForSurveyJs->getId(), new FacilityId('34567'));
        $surveyResponseRepository = $this->getMockBuilder(SurveyResponseRepository::class)->disableOriginalConstructor()->getMock();
        $surveyResponseRepository->expects($this->once())
            ->method('createFormModel')
            ->willReturn($surveyResponseCreateFormModel);
        $surveyResponseRepository->expects($this->once())
            ->method('create');

        $repository = $this->createRepository(surveyResponseRepository: $surveyResponseRepository);
        $facilityId = $repository->create($model);
        $this->assertTrue(Facility::find()->andWhere(['id' => $facilityId])->exists());
    }

    public function testCreateFormModel(): void
    {
        $languageSet = LanguageSet::fullSet();
        $workspace = new WorkspaceForCreateOrUpdateFacility(
            new SurveyId(1),
            new WorkspaceId(1),
            $languageSet,
            new ProjectId(1),
            'Test project title',
            'Test title',
        );

        $accessCheck = $this->getMockBuilder(AccessCheckInterface::class)->getMock();
        $accessCheck->expects($this->once())
            ->method('requirePermission')
            ->with(new Facility(['workspace_id' => $workspace->getId()->getValue()]), Permission::PERMISSION_CREATE);

        $repository = $this->createRepository($accessCheck);
        $createModel = $repository->createFormModel($workspace);

        $this->assertSame($workspace->getLanguages(), $createModel->getLanguages());
        $this->assertEquals($workspace->getId(), $createModel->getWorkspaceId());
    }

    /**
     * @dataProvider isOfTypeDataProvider
     */
    public function testIsOfProjectType(string|int $facilityId, ProjectType $projectType, $result): void
    {
        $repository = $this->createRepository();
        $this->assertEquals($result, $repository->isOfProjectType(new FacilityId((string) $facilityId), $projectType));
    }

    public function testRetrieveForUpdate(): void
    {
        $facility = Facility::find()->one();
        $facilityId = new FacilityId((string) $facility->id);

        $accessCheck = $this->getMockBuilder(AccessCheckInterface::class)->getMock();
        $accessCheck->expects($this->once())
            ->method('requirePermission');

        $workspaceRepository = $this->getMockBuilder(WorkspaceRepository::class)->disableOriginalConstructor()->getMock();
        $workspaceRepository->expects($this->once())
            ->method('retrieveForNewFacility')
            ->with(new WorkspaceId($facility->workspace_id))
            ->willReturn(new WorkspaceForCreateOrUpdateFacility(
                new SurveyId($facility->adminSurvey->id),
                new WorkspaceId($facility->workspace_id),
                $facility->project->getLanguageSet(),
                new ProjectId($facility->workspace->project_id),
                $facility->project->title,
                $facility->name,
            ));

        $survey = new SurveyForSurveyJs(new SurveyId($facility->adminSurvey->id), $facility->adminSurvey->config);
        $surveyRepository = $this->getMockBuilder(SurveyRepository::class)->disableOriginalConstructor()->getMock();
        $surveyRepository->expects($this->once())
            ->method('retrieveAdminSurveyForWorkspaceForSurveyJs')
            ->with(new WorkspaceId($facility->workspace_id))
            ->willReturn($survey);

        $surveyResponse = new SurveyResponseForSurveyJs(
            ['name' => 'Test facility update'],
            new SurveyResponseId(12345)
        );
        $surveyResponseRepository = $this->getMockBuilder(SurveyResponseRepository::class)->disableOriginalConstructor()->getMock();
        $surveyResponseRepository->expects($this->once())
            ->method('retrieveAdminSurveyResponseForFacilityUpdate')
            ->with($facilityId)
            ->willReturn($surveyResponse);

        $repository = $this->createRepository(
            accessCheck: $accessCheck,
            surveyRepository: $surveyRepository,
            surveyResponseRepository: $surveyResponseRepository,
            workspaceRepository: $workspaceRepository,
        );
        $model = $repository->retrieveForUpdate($facilityId);

        $this->assertEquals($model->getSurvey(), $survey);
        $this->assertEquals($model->data, $surveyResponse->getData());
        $this->assertEquals($model->getLanguages(), $facility->project->getLanguageSet());
    }

    public function testRetrieveForUpdateSituation(): void
    {
        $facility = Facility::find()->one();
        $facilityId = new FacilityId((string) $facility->id);

        $accessCheck = $this->getMockBuilder(AccessCheckInterface::class)->getMock();
        $accessCheck->expects($this->once())
            ->method('requirePermission');

        $workspaceRepository = $this->getMockBuilder(WorkspaceRepository::class)->disableOriginalConstructor()->getMock();
        $workspaceRepository->expects($this->once())
            ->method('retrieveForNewFacility')
            ->with(new WorkspaceId($facility->workspace_id))
            ->willReturn(new WorkspaceForCreateOrUpdateFacility(
                new SurveyId($facility->adminSurvey->id),
                new WorkspaceId($facility->workspace_id),
                $facility->project->getLanguageSet(),
                new ProjectId($facility->workspace->project_id),
                $facility->project->title,
                $facility->name,
            ));

        $survey = new SurveyForSurveyJs(new SurveyId($facility->adminSurvey->id), $facility->adminSurvey->config);
        $surveyRepository = $this->getMockBuilder(SurveyRepository::class)->disableOriginalConstructor()->getMock();
        $surveyRepository->expects($this->once())
            ->method('retrieveDataSurveyForWorkspaceForSurveyJs')
            ->with(new WorkspaceId($facility->workspace_id))
            ->willReturn($survey);

        $surveyResponse = new SurveyResponseForSurveyJs(
            ['Q1' => 'A1'],
            new SurveyResponseId(23456)
        );
        $surveyResponseRepository = $this->getMockBuilder(SurveyResponseRepository::class)->disableOriginalConstructor()->getMock();
        $surveyResponseRepository->expects($this->once())
            ->method('retrieveDataSurveyResponseForFacilitySituationUpdate')
            ->with($facilityId)
            ->willReturn($surveyResponse);

        $repository = $this->createRepository(
            accessCheck: $accessCheck,
            surveyRepository: $surveyRepository,
            surveyResponseRepository: $surveyResponseRepository,
            workspaceRepository: $workspaceRepository,
        );
        $model = $repository->retrieveForUpdateSituation($facilityId);

        $this->assertEquals($model->getSurvey(), $survey);
        $this->assertEquals($model->data, $surveyResponse->getData());
        $this->assertEquals($model->getLanguages(), $facility->project->getLanguageSet());
    }

    public function testSaveUpdate(): void
    {
        $facility = Facility::find()->one();
        $facilityId = new FacilityId((string) $facility->id);
        $survey = new SurveyForSurveyJs(new SurveyId($facility->adminSurvey->id), $facility->adminSurvey->config);
        $model = new UpdateForm($facilityId, $facility->project->getLanguageSet(), $survey);
        $updatedName = 'Facility name after tested update';
        $model->data = ['name' => $updatedName];

        $accessCheck = $this->getMockBuilder(AccessCheckInterface::class)->getMock();
        $accessCheck->expects($this->once())
            ->method('requirePermission');

        $surveyResponseCreateFormModel = new SurveyResponseCreateForm(new SurveyId($facility->adminSurvey->id), $facilityId);
        $surveyResponseRepository = $this->getMockBuilder(SurveyResponseRepository::class)->disableOriginalConstructor()->getMock();
        $surveyResponseRepository->expects($this->once())
            ->method('createFormModel')
            ->with(new SurveyId($facility->adminSurvey->id), $facilityId)
            ->willReturn($surveyResponseCreateFormModel);
        $surveyResponseRepository->expects($this->once())
            ->method('create');

        $repository = $this->createRepository(
            accessCheck: $accessCheck,
            surveyResponseRepository: $surveyResponseRepository
        );
        $updatedFacilityId = $repository->saveUpdate($model);
        $facility->refresh();

        $this->assertEquals($facilityId, $updatedFacilityId);
        $this->assertEquals($facility->name, $updatedName);
    }

    public function testSaveUpdateSituation(): void
    {
        $facility = Facility::find()->one();
        $facilityId = new FacilityId((string) $facility->id);
        $survey = new SurveyForSurveyJs(new SurveyId($facility->dataSurvey->id), $facility->dataSurvey->config);
        $model = new UpdateSituationForm($facilityId, $facility->project->getLanguageSet(), $survey);

        $accessCheck = $this->getMockBuilder(AccessCheckInterface::class)->getMock();
        $accessCheck->expects($this->once())
            ->method('requirePermission');

        $surveyResponseCreateFormModel = new SurveyResponseCreateForm(new SurveyId($facility->dataSurvey->id), $facilityId);
        $surveyResponseRepository = $this->getMockBuilder(SurveyResponseRepository::class)->disableOriginalConstructor()->getMock();
        $surveyResponseRepository->expects($this->once())
            ->method('createFormModel')
            ->with(new SurveyId($facility->dataSurvey->id), $facilityId)
            ->willReturn($surveyResponseCreateFormModel);
        $surveyResponseRepository->expects($this->once())
            ->method('create');

        $repository = $this->createRepository(
            accessCheck: $accessCheck,
            surveyResponseRepository: $surveyResponseRepository
        );
        $model->data = ['Q1' => 'A1'];
        $updatedFacilityId = $repository->saveUpdateSituation($model);
        $facility->refresh();

        $this->assertEquals($facilityId, $updatedFacilityId);
    }
}
