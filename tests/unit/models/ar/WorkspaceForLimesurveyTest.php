<?php

namespace prime\tests\unit\models\ar;

use Carbon\Carbon;
use prime\models\ar\Project;

/**
 * @covers \prime\models\ar\WorkspaceForLimesurvey
 */
class WorkspaceForLimesurveyTest extends ActiveRecordTest
{
    public function validSamples(): array
    {
        return [
            [
                [
                    'title' => 'test',
                    'token' => 'abcdef',
                    'closed_at' => Carbon::now(),
                    'project_id' => function () {
                        $project = new Project();
                        $project->title = 'Test Project';
                        $project->base_survey_eid = '12345';
                        $this->assertTrue($project->save(), print_r($project->errors, true));
                        return $project->id;
                    },
                ],
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
                    'closed_at' => 'abc',
                    'token' => 'test123',

                ],
            ],
            [
                [
                    'token' => 'test123',
                    'project_id' => 1,
                ],
                null,
                // This is a pre inserted record, so it has to be valid.
                [
                    'token' => 'test123',
                    'title' => 'test',
                    'project_id' => 1,
                ],
            ],
        ];
    }
}
