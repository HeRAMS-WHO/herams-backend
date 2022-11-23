<?php

declare(strict_types=1);

namespace prime\tests\unit\repositories;

use Codeception\Test\Unit;
use Collecthor\SurveyjsParser\Parsers\SingleChoiceQuestionParser;
use herams\common\domain\facility\Facility;
use herams\common\domain\facility\FacilityRepository;
use herams\common\domain\survey\SurveyRepository;
use herams\common\domain\surveyResponse\SurveyResponseRepository;
use herams\common\domain\variableSet\HeramsVariableSetRepository;
use herams\common\domain\workspace\WorkspaceRepository;
use herams\common\helpers\ModelHydrator;
use herams\common\helpers\surveyjs\FacilityTypeQuestionParser;
use herams\common\helpers\surveyjs\SurveyParser;
use herams\common\interfaces\AccessCheckInterface;
use herams\common\interfaces\SurveyRepositoryInterface;
use herams\common\models\Permission;
use herams\common\values\FacilityId;
use herams\common\values\ProjectId;
use herams\common\values\SurveyId;
use herams\common\values\SurveyResponseId;
use herams\common\values\WorkspaceId;
use prime\models\forms\facility\CreateForm;
use prime\models\forms\facility\UpdateSituationForm;
use prime\models\forms\surveyResponse\CreateForm as SurveyResponseCreateForm;
use prime\models\survey\SurveyForSurveyJs;
use prime\models\surveyResponse\SurveyResponseForSurveyJs;
use prime\models\workspace\WorkspaceForCreateOrUpdateFacility;
use prime\objects\LanguageSet;

/**
 * @covers \herams\common\domain\facility\FacilityRepository
 */
class FacilityRepositoryTest extends Unit
{
    private function createRepository(
        AccessCheckInterface $accessCheck = null,
        ModelHydrator $modelHydrator = null,
        SurveyRepositoryInterface $surveyRepository = null,
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
            $surveyRepository ?? new SurveyRepository(new SurveyParser(new FacilityTypeQuestionParser(new SingleChoiceQuestionParser())), $newAccessCheck, new ModelHydrator()),
            $surveyResponseRepository ?? new SurveyResponseRepository($newAccessCheck, new ModelHydrator()),
            $workspaceRepository ?? \Yii::$container->get(WorkspaceRepository::class, [$newAccessCheck]),
            new HeramsVariableSetRepository()
        );
    }

    public function testCreate(): void
    {
        $surveyForSurveyJs = new SurveyForSurveyJs(new SurveyId(12345), [
            'pages' => [],
        ]);
        $model = new CreateForm(
            LanguageSet::fullSet(),
            $surveyForSurveyJs,
            new WorkspaceId(1),
        );
        $name = 'Test new created facility';
        $model->data = [
            'name' => $name,
        ];

        $surveyResponseCreateFormModel = new SurveyResponseCreateForm($surveyForSurveyJs->getId(), new FacilityId('34567'));
        $surveyResponseRepository = $this->getMockBuilder(SurveyResponseRepository::class)->disableOriginalConstructor()->getMock();
        $surveyResponseRepository->expects($this->once())
            ->method('createFormModel')
            ->willReturn($surveyResponseCreateFormModel);
        $surveyResponseRepository->expects($this->once())
            ->method('create');

        $repository = $this->createRepository(surveyResponseRepository: $surveyResponseRepository);
        $facilityId = $repository->create($model);
        $this->assertTrue(Facility::find()->andWhere([
            'id' => $facilityId,
        ])->exists());
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
            ->with(new Facility([
                'workspace_id' => $workspace->getId()->getValue(),
            ]), Permission::PERMISSION_CREATE);

        $repository = $this->createRepository($accessCheck);
        $createModel = $repository->createFormModel($workspace);

        $this->assertSame($workspace->getLanguages(), $createModel->getLanguages());
        $this->assertEquals($workspace->getId(), $createModel->getWorkspaceId());
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
        $surveyRepository = $this->getMockBuilder(SurveyRepositoryInterface::class)->disableOriginalConstructor()->getMock();
        $surveyRepository->expects($this->once())
            ->method('retrieveAdminSurveyForWorkspaceForSurveyJs')
            ->with(new WorkspaceId($facility->workspace_id))
            ->willReturn($survey);

        $surveyResponse = new SurveyResponseForSurveyJs(
            [
                'name' => 'Test facility update',
            ],
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
        $surveyRepository = $this->getMockBuilder(SurveyRepositoryInterface::class)->disableOriginalConstructor()->getMock();
        $surveyRepository->expects($this->once())
            ->method('retrieveDataSurveyForWorkspaceForSurveyJs')
            ->with(new WorkspaceId($facility->workspace_id))
            ->willReturn($survey);

        $surveyResponse = new SurveyResponseForSurveyJs(
            [
                'Q1' => 'A1',
            ],
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
        $model->data = [
            'Q1' => 'A1',
        ];
        $updatedFacilityId = $repository->saveUpdateSituation($model);
        $facility->refresh();

        $this->assertEquals($facilityId, $updatedFacilityId);
    }
}
