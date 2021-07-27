<?php
namespace prime\tests\unit\models\ar;

use prime\models\ar\Favorite;
use prime\models\ar\User;

/**
 * @covers \prime\models\ar\Survey
 */
class SurveyTest extends ActiveRecordTest
{
    public function validSamples(): array
    {
        return [
            [
                [
                    'config' => [
                        'pages' => []
                    ],
                ]
            ],
        ];
    }

    public function invalidSamples(): array
    {
        return [
            [
                'attributes' => [],
                'scenario' => User::SCENARIO_DEFAULT
            ]
        ];
    }

    public function testRelations(): void
    {
//        $this->testRelation('project', Favorite::class);
    }
}
