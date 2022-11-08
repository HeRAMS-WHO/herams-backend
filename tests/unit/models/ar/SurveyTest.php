<?php

declare(strict_types=1);

namespace prime\tests\unit\models\ar;

use herams\common\domain\survey\Survey;
use herams\common\domain\user\User;
use herams\common\models\SurveyResponse;

/**
 * @covers \herams\common\domain\survey\Survey
 */
class SurveyTest extends ActiveRecordTest
{
    public function invalidSamples(): array
    {
        return [
            [
                'attributes' => [],
                'scenario' => User::SCENARIO_DEFAULT,
            ],
        ];
    }

    public function testGetSurveyResponses(): void
    {
        $this->testRelation('surveyResponses', SurveyResponse::class);
    }

    public function testTitle(): void
    {
        $survey = new Survey([
            'id' => 12345,
        ]);
        $this->assertEquals('Survey without title, id 12345', $survey->getTitle());

        $title = 'Survey test title';
        $survey->config = [
            'title' => $title,
        ];
        $this->assertEquals($title, $survey->getTitle());
    }

    public function validSamples(): array
    {
        return [
            [
                [
                    'config' => [
                        'pages' => [],
                    ],
                ],
            ],
        ];
    }
}
