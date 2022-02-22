<?php

declare(strict_types=1);

namespace prime\tests\unit\models\ar;

/**
 * @covers \prime\models\ar\SurveyResponse
 */
class SurveyResponseTest extends ActiveRecordTest
{
    public function invalidSamples(): array
    {
        return [
            'invalid' => [
                'attributes' => [],
            ]
        ];
    }

    public function validSamples(): array
    {
        return [
            [
                [
                    'created_by' => TEST_USER_ID,
                    'data' => [
                        'q1' => 'Yes'
                    ],
                    'facility_id' => 1,
                    'survey_id' => 1,
                ],
            ],
        ];
    }
}
