<?php

declare(strict_types=1);

namespace prime\tests\unit\models\forms\surveyResponse;

use herams\common\values\FacilityId;
use herams\common\values\SurveyId;
use prime\models\forms\surveyResponse\CreateForm;
use prime\tests\unit\models\ModelTest;

/**
 * @covers \prime\models\forms\surveyResponse\CreateForm
 */
class CreateFormTest extends ModelTest
{
    protected function getModel(): CreateForm
    {
        return new CreateForm(new SurveyId(1), new FacilityId('1'));
    }

    public function invalidSamples(): iterable
    {
        return [
            [
                [
                    'data' => [],
                ],
            ],
        ];
    }

    public function validSamples(): iterable
    {
        return [
            [
                [
                    'data' => [
                        'name' => 'test',
                    ],
                ],
            ],
        ];
    }
}
