<?php
namespace prime\tests\unit\models\ar;

use prime\models\ar\Tool;

class ProjectTest extends ActiveRecordTest
{
    public function validSamples(): array
    {
        return [
            [
                [
                    'title' => 'test',
                    'owner_id' => TEST_USER_ID,
                    'tool_id' => function() {
                        $tool = new Tool();
                        $tool->title = 'Test Tool';
                        $tool->base_survey_eid = '12345';
                        $this->assertTrue($tool->save(), print_r($tool->errors, true));
                        return $tool->id;
                    }
                ]
            ],
//            [
//                []
//            ]
        ];
    }

    public function invalidSamples(): array
    {
        return [
            [
                []
            ]
        ];
    }


}