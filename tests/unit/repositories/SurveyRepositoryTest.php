<?php

declare(strict_types=1);

namespace prime\tests\unit\repositories;

use Codeception\Test\Unit;
use herams\common\domain\survey\Survey;
use herams\common\domain\survey\SurveyForList;
use herams\common\domain\survey\SurveyRepository;
use herams\common\helpers\ModelHydrator;
use herams\common\helpers\StrategyActiveRecordHydrator;
use herams\common\helpers\surveyjs\FacilityTypeQuestionParser;
use herams\common\helpers\surveyjs\SurveyParser;
use herams\common\interfaces\AccessCheckInterface;
use herams\common\models\Project;
use herams\common\models\Workspace;
use herams\common\values\SurveyId;
use herams\common\values\WorkspaceId;
use prime\helpers\ArrayHelper;
use prime\interfaces\survey\SurveyForSurveyJsInterface;
use prime\models\forms\survey\CreateForm;
use prime\models\forms\survey\UpdateForm;
use prime\models\search\SurveySearch;
use yii\base\InvalidArgumentException;
use yii\data\DataProviderInterface;

/**
 * @covers \herams\common\domain\survey\SurveyRepository
 */
class SurveyRepositoryTest extends Unit
{
    private function createRepository(
        AccessCheckInterface $accessCheck = null,
        ModelHydrator $modelHydrator = null,
    ): SurveyRepository {
        $accessChecker = $accessCheck ?? $this->getMockBuilder(AccessCheckInterface::class)->disableOriginalConstructor()->getMock();
        $modelHydrator = $modelHydrator ?? new ModelHydrator();

        $facilityTypeQuestionParser = $this->getMockBuilder(FacilityTypeQuestionParser::class)->disableOriginalConstructor()->getMock();
        $surveyParser = new SurveyParser($facilityTypeQuestionParser);
        return new SurveyRepository($surveyParser, $accessChecker, new StrategyActiveRecordHydrator(), $modelHydrator);
    }

    private function createSurvey(array $config = []): Survey
    {
        $survey = new Survey();
        $survey->config = ArrayHelper::merge([
            'pages' => [
                [
                    'name' => 'page1',
                    'elements' => [
                        [
                            'type' => 'text',
                            'name' => 'question1',
                            'title' => 'title1',
                        ],
                    ],
                ],
            ],
        ], $config);
        $this->assertTrue($survey->save());
        return $survey;
    }

    public function testCreate(): void
    {
        $model = new CreateForm();
        $model->config = [
            'pages' => [
                [
                    'name' => 'page1',
                    'elements' => [
                        [
                            'type' => 'text',
                            'name' => 'question1',
                            'title' => 'title1',
                        ],
                    ],
                ],
            ],
        ];

        $repository = $this->createRepository();
        $repository->create($model);
    }

    public function testCreateFailed(): void
    {
        $model = new CreateForm();

        $repository = $this->createRepository();
        $this->expectException(InvalidArgumentException::class);
        $repository->create($model);
    }

    public function testRetrieveAdminSurveyForWorkspaceForSurveyJs(): void
    {
        $survey = $this->createSurvey();
        $project = new Project([
            'title' => 'Test project',
            'admin_survey_id' => $survey->id,
            'data_survey_id' => $survey->id,
        ]);
        $this->assertTrue($project->save());

        $workspace = new Workspace([
            'project_id' => $project->id,
            'title' => 'Test workspace',
        ]);
        $this->assertTrue($workspace->save());

        $repository = $this->createRepository();
        $surveyForSurveyJs = $repository->retrieveAdminSurveyForWorkspaceForSurveyJs(new WorkspaceId($workspace->id));

        $this->assertEquals(new SurveyId($survey->id), $surveyForSurveyJs->getId());
        $this->assertEquals($survey->config, $surveyForSurveyJs->getConfig());
    }

    public function testRetrieveForSurveyJs(): void
    {
        $survey = $this->createSurvey();

        $repository = $this->createRepository();

        $surveyId = new SurveyId($survey->id);
        $surveyForSurveyJs = $repository->retrieveForSurveyJs($surveyId);
        $this->assertInstanceOf(SurveyForSurveyJsInterface::class, $surveyForSurveyJs);
        $this->assertEquals($surveyId, $surveyForSurveyJs->getId());
        $this->assertEquals($survey->config, $surveyForSurveyJs->getConfig());
    }

    public function testRetrieveForUpdate(): void
    {
        $survey = $this->createSurvey();

        $accessChecker = $this->getMockBuilder(AccessCheckInterface::class)->disableOriginalConstructor()->getMock();
        $accessChecker->expects($this->once())
            ->method('requirePermission');
        $modelHydrator = $this->getMockBuilder(ModelHydrator::class)->disableOriginalConstructor()->getMock();

        $repository = $this->createRepository($accessChecker, $modelHydrator);
        $model = $repository->retrieveForUpdate(new SurveyId($survey->id));

        $this->assertEquals($survey->config, $model->config);
    }

    public function testSearchEmpty(): void
    {
        $surveySearch = new SurveySearch();
        $this->createSurvey();

        $accessChecker = $this->getMockBuilder(AccessCheckInterface::class)->disableOriginalConstructor()->getMock();
        $modelHydrator = $this->getMockBuilder(ModelHydrator::class)->disableOriginalConstructor()->getMock();

        $repository = $this->createRepository($accessChecker, $modelHydrator);
        $surveyProvider = $repository->search($surveySearch);
        $this->assertInstanceOf(DataProviderInterface::class, $surveyProvider);
        $this->assertEquals(Survey::find()->count(), $surveyProvider->getTotalCount());
    }

    public function testSearchName(): void
    {
        $surveySearch = new SurveySearch();
        $this->createSurvey();
        $title = 'Test questionnaire';
        $survey = $this->createSurvey([
            'title' => $title,
        ]);
        $surveySearch->title = $title;

        $accessChecker = $this->getMockBuilder(AccessCheckInterface::class)->disableOriginalConstructor()->getMock();
        $modelHydrator = new ModelHydrator();

        $repository = $this->createRepository($accessChecker, $modelHydrator);
        $surveyProvider = $repository->search($surveySearch);
        $this->assertEquals([$modelHydrator->hydrateConstructor($survey, SurveyForList::class)], $surveyProvider->getModels());
    }

    public function testUpdate(): void
    {
        $survey = $this->createSurvey();

        $accessChecker = $this->getMockBuilder(AccessCheckInterface::class)->disableOriginalConstructor()->getMock();
        $accessChecker->expects($this->once())
            ->method('requirePermission');
        $modelHydrator = new ModelHydrator();

        $repository = $this->createRepository($accessChecker, $modelHydrator);

        $model = new UpdateForm(new SurveyId($survey->id));
        $newConfig = [
            'pages' => [
                0 => [
                    'name' => 'page2',
                    'elements' => [
                        0 =>
                            [
                                'type' => 'text',
                                'name' => 'question1',
                                'title' => 'title1',
                            ],
                    ],
                ],
            ],
        ];
        $model->config = $newConfig;
        $repository->update($model);

        $survey->refresh();
        $this->assertEquals($newConfig, $survey->config);
    }

    public function testUpdateFailed(): void
    {
        $survey = $this->createSurvey();

        $accessChecker = $this->getMockBuilder(AccessCheckInterface::class)->disableOriginalConstructor()->getMock();
        $accessChecker->expects($this->once())
            ->method('requirePermission');
        $repository = $this->createRepository($accessChecker);

        $model = new UpdateForm(new SurveyId($survey->id));
        $model->config = [];
        $this->expectException(InvalidArgumentException::class);
        $repository->update($model);
    }
}
