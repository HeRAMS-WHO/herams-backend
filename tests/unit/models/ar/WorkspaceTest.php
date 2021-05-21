<?php
namespace prime\tests\unit\models\ar;

use Carbon\Carbon;
use prime\models\ar\Project;

/**
 * @covers \prime\models\ar\Workspace
 */
class WorkspaceTest extends ActiveRecordTest
{
    public function validSamples(): array
    {
        return [
            [
                [
                    'title' => 'test',
                    'token' => 'abcdef',
                    'closed' => Carbon::now(),
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
                [
                    'closed' => 'abc',
                    'token' => 'test123',

                ]
            ],
            [
                [
                    'token' => 'test123',
                    'tool_id' => 1
                ],
                null,
                // This is a pre inserted record, so it has to be valid.
                [
                    'token' => 'test123',
                    'title' => 'test',
                    'tool_id' => 1
                ]
            ]
        ];
    }
}
