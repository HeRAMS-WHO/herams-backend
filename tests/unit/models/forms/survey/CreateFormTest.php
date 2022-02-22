<?php

declare(strict_types=1);

namespace prime\tests\unit\models\forms\survey;

use prime\models\forms\survey\CreateForm;
use prime\tests\unit\models\ModelTest;

/**
 * @covers \prime\models\forms\survey\CreateForm
 */
class CreateFormTest extends ModelTest
{
    protected function getModel(): CreateForm
    {
        return new CreateForm();
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
