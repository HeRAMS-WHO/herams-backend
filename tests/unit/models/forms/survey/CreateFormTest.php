<?php

declare(strict_types=1);

namespace prime\tests\unit\models\forms\survey;

use prime\models\survey\SurveyForCreate;
use prime\tests\unit\models\ModelTest;

/**
 * @covers \prime\models\survey\SurveyForCreate
 */
class CreateFormTest extends ModelTest
{
    protected function getModel(): SurveyForCreate
    {
        return new SurveyForCreate();
    }

    public function invalidSamples(): iterable
    {
        return [
            'emptyConfig' => [
                'attributes' => [
                    'config' => [],
                ],
            ]
        ];
    }

    public function validSamples(): iterable
    {
        return [
            'correctConfig' => [
                'attributes' => [
                    'config' => [
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
                    ]
                ]
            ],
        ];
    }
}
