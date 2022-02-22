<?php

declare(strict_types=1);

namespace prime\tests\unit\models\forms\facility;

use Codeception\Test\Unit;
use prime\interfaces\survey\SurveyForSurveyJsInterface;
use prime\models\forms\facility\UpdateForm;
use prime\objects\LanguageSet;
use prime\tests\_helpers\AllFunctionsMustHaveReturnTypes;
use prime\tests\_helpers\AttributeValidationByExample;
use prime\tests\_helpers\YiiLoadMustBeDisabled;
use prime\values\FacilityId;

/**
 * @covers \prime\models\forms\facility\UpdateForm
 */
class UpdateTest extends Unit
{
    use AllFunctionsMustHaveReturnTypes;
    use AttributeValidationByExample;
    use YiiLoadMustBeDisabled;

    private function getFacilityId(): FacilityId
    {
        return $this->getMockBuilder(FacilityId::class)->disableOriginalConstructor()->getMock();
    }

    private function getLanguages(): LanguageSet
    {
        return $this->getMockBuilder(LanguageSet::class)->getMock();
    }

    private function getModel(): UpdateForm
    {
        return new UpdateForm(
            $this->getFacilityId(),
            $this->getLanguages(),
            $this->getSurvey(),
        );
    }

    private function getSurvey(): SurveyForSurveyJsInterface
    {
        return $this->getMockBuilder(SurveyForSurveyJsInterface::class)->getMock();
    }

    public function testGetFacilityId(): void
    {
        $id = new FacilityId('1');

        $model = new UpdateForm(
            $id,
            $this->getLanguages(),
            $this->getSurvey(),
        );

        // We care about the value, not the instance.
        $this->assertSame($id->getValue(), $model->getFacilityId()->getValue());
    }

    public function testGetLanguages(): void
    {
        $languages = $this->getLanguages();

        $model = new UpdateForm($this->getFacilityId(), $languages, $this->getSurvey());

        $this->assertSame($languages, $model->getLanguages());
    }

    public function testGetSurvey(): void
    {
        $survey = $this->getSurvey();

        $model = new UpdateForm($this->getFacilityId(), $this->getLanguages(), $survey);

        $this->assertSame($survey, $model->getSurvey());
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
