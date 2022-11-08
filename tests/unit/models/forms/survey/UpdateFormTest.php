<?php

declare(strict_types=1);

namespace prime\tests\unit\models\forms\survey;

use herams\common\values\SurveyId;
use prime\models\forms\survey\UpdateForm;
use prime\tests\unit\models\ModelTest;

/**
 * @covers \prime\models\forms\survey\UpdateForm
 */
class UpdateFormTest extends ModelTest
{
    protected int $id = 1;

    protected function getModel(): UpdateForm
    {
        $result = new UpdateForm(new SurveyId($this->id));
        $result->config = [];
        return $result;
    }

    public function invalidSamples(): iterable
    {
        return [
            'emptyConfig' => [
                'attributes' => [
                    'config' => [],
                ],
            ],
        ];
    }

    public function testId(): void
    {
        $model = $this->getModel();
        $this->assertEquals(new SurveyId($this->id), $model->getSurveyId());
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
                    ],
                ],
            ],
        ];
    }
}
