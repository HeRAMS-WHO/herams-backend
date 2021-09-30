<?php
declare(strict_types=1);

namespace prime\tests\unit\repositories;

use Codeception\Test\Unit;
use prime\helpers\ArrayHelper;
use prime\helpers\ModelHydrator;
use prime\interfaces\AccessCheckInterface;
use prime\models\ar\Survey;
use prime\models\search\SurveySearch;
use prime\models\survey\SurveyForCreate;
use prime\models\survey\SurveyForList;
use prime\models\survey\SurveyForUpdate;
use prime\repositories\SurveyRepository;
use prime\values\SurveyId;
use yii\base\InvalidArgumentException;
use yii\data\DataProviderInterface;

/**
 * @covers \prime\repositories\SurveyRepository
 */
class SurveyRepositoryTest extends Unit
{
    protected function createSurvey(array $config = []): Survey
    {
        $survey = new Survey();
        $survey->config = ArrayHelper::merge([
            'pages' => [
                0 => [
                    'name' => 'page1',
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
        ], $config);
        $this->assertTrue($survey->save());
        return $survey;
    }

    public function testCreate(): void
    {
        $model = new SurveyForCreate();
        $model->config = [
            'pages' => [
                0 => [
                    'name' => 'page1',
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

        $accessChecker = $this->getMockBuilder(AccessCheckInterface::class)->disableOriginalConstructor()->getMock();
        $accessChecker->expects($this->once())
            ->method('requirePermission');
        $modelHydrator = new ModelHydrator();

        $repository = new SurveyRepository($accessChecker, $modelHydrator);
        $repository->create($model);
    }

    public function testCreateFailed(): void
    {
        $model = new SurveyForCreate();

        $accessChecker = $this->getMockBuilder(AccessCheckInterface::class)->disableOriginalConstructor()->getMock();
        $accessChecker->expects($this->once())
            ->method('requirePermission');
        $modelHydrator = new ModelHydrator();

        $repository = new SurveyRepository($accessChecker, $modelHydrator);
        $this->expectException(InvalidArgumentException::class);
        $repository->create($model);
    }

    public function testRetrieveForUpdate(): void
    {
        $survey = $this->createSurvey();

        $accessChecker = $this->getMockBuilder(AccessCheckInterface::class)->disableOriginalConstructor()->getMock();
        $accessChecker->expects($this->once())
            ->method('requirePermission');
        $modelHydrator = $this->getMockBuilder(ModelHydrator::class)->disableOriginalConstructor()->getMock();

        $repository = new SurveyRepository($accessChecker, $modelHydrator);
        $model = $repository->retrieveForUpdate(new SurveyId($survey->id));

        $this->assertEquals($survey->config, $model->config);
    }

    public function testSearchEmpty(): void
    {
        $surveySearch = new SurveySearch();
        $this->createSurvey();

        $accessChecker = $this->getMockBuilder(AccessCheckInterface::class)->disableOriginalConstructor()->getMock();
        $modelHydrator = $this->getMockBuilder(ModelHydrator::class)->disableOriginalConstructor()->getMock();

        $repository = new SurveyRepository($accessChecker, $modelHydrator);
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

        $repository = new SurveyRepository($accessChecker, $modelHydrator);
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

        $repository = new SurveyRepository($accessChecker, $modelHydrator);

        $model = new SurveyForUpdate(new SurveyId($survey->id));
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
        $modelHydrator = new ModelHydrator();

        $repository = new SurveyRepository($accessChecker, $modelHydrator);

        $model = new SurveyForUpdate(new SurveyId($survey->id));
        $model->config = [];
        $this->expectException(InvalidArgumentException::class);
        $repository->update($model);
    }
}
