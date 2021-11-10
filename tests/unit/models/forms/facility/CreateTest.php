<?php

declare(strict_types=1);

namespace prime\tests\unit\models\forms\facility;

use Codeception\Test\Unit;
use prime\interfaces\survey\SurveyForSurveyJsInterface;
use prime\models\forms\facility\CreateForm;
use prime\objects\LanguageSet;
use prime\tests\_helpers\AllFunctionsMustHaveReturnTypes;
use prime\tests\_helpers\AttributeValidationByExample;
use prime\tests\_helpers\YiiLoadMustBeDisabled;
use prime\values\WorkspaceId;

/**
 * @covers \prime\models\forms\facility\CreateForm
 */
class CreateTest extends Unit
{
    use AllFunctionsMustHaveReturnTypes;
    use AttributeValidationByExample;
    use YiiLoadMustBeDisabled;

    private function getLanguages(): LanguageSet
    {
        return $this->getMockBuilder(LanguageSet::class)->getMock();
    }

    private function getModel(): CreateForm
    {
        return new CreateForm(
            $this->getLanguages(),
            $this->getSurvey(),
            $this->getWorkspaceId(),
        );
    }

    private function getSurvey(): SurveyForSurveyJsInterface
    {
        return $this->getMockBuilder(SurveyForSurveyJsInterface::class)->getMock();
    }

    private function getWorkspaceId(): WorkspaceId
    {
        return $this->getMockBuilder(WorkspaceId::class)->disableOriginalConstructor()->getMock();
    }

    public function testGetLanguages(): void
    {
        $languages = $this->getLanguages();

        $model = new CreateForm(
            $languages,
            $this->getSurvey(),
            $this->getWorkspaceId(),
        );

        $this->assertSame($languages, $model->getLanguages());
    }

    public function testGetSurvey(): void
    {
        $survey = $this->getSurvey();

        $model = new CreateForm(
            $this->getLanguages(),
            $survey,
            $this->getWorkspaceId(),
        );

        $this->assertSame($survey, $model->getSurvey());
    }

    public function testGetWorkspaceId(): void
    {
        $id = new WorkspaceId(1);

        $model = new CreateForm(
            $this->getLanguages(),
            $this->getSurvey(),
            $id,
        );

        // We care about the value, not the instance.
        $this->assertSame($id->getValue(), $model->getWorkspaceId()->getValue());
    }

    public function validSamples(): iterable
    {
        yield [
            [
                'data' => [
                    'name' => 'cool stuff',
                ]
            ]
        ];
    }

    public function invalidSamples(): iterable
    {
        yield [
            [
                'data' => [],
            ]
        ];
    }
}
