<?php

declare(strict_types=1);

namespace prime\tests\unit\models\ar;

use prime\models\ar\Favorite;
use prime\models\ar\Survey;
use prime\models\ar\SurveyResponse;
use prime\models\ar\User;

/**
 * @covers \prime\models\ar\Survey
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
