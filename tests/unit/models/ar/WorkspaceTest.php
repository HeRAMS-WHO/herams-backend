<?php
namespace prime\tests\unit\models\ar;

use prime\models\ar\Project;

class WorkspaceTest extends ActiveRecordTest
{
    public function validSamples(): array
    {
        return [
            [
                [
                    'title' => 'test',
                    'tool_id' => function () {
                        $tool = new Project();
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
