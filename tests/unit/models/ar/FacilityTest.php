<?php

declare(strict_types=1);

namespace prime\tests\unit\models\ar;

use prime\models\ar\Project;
use prime\models\ar\Survey;
use prime\models\ar\SurveyResponse;
use prime\models\ar\Workspace;
use prime\values\FacilityId;

/**
 * @covers \prime\models\ar\Facility
 */
class FacilityTest extends ActiveRecordTest
{
    public function validSamples(): iterable
    {
        return [
            [
                [
                    'workspace_id' => 1,
                    'name' => 'Test facility',
                    'alternative_name' => 'Test facility alternative',
                    'code' => 'abc123',
                    'latitude' => 1,
                    'longitude' => 1,
                ],
            ],
        ];
    }

    public function invalidSamples(): iterable
    {
        return [
            [
                [],
            ],
            [
                [
                    'workspace_id' => null,
                    'name' => 'Test facility',
                    'alternative_name' => new FacilityId('1'),
                    'code' => new FacilityId('1'),
                ],
            ],
            [
                [
                    'workspace_id' => 1,
                    'name' => '',
                    'latitude' => 'test',
                    'longitude' => 'test',
                ],
            ],
        ];
    }

    public function testGetAdminSurvey(): void
    {
        $this->testRelation('adminSurvey', Survey::class);
    }

    public function testGetDataSurvey(): void
    {
        $this->testRelation('adminSurvey', Survey::class);
    }

    public function testGetProject(): void
    {
        $this->testRelation('project', Project::class);
    }

    public function testGetSurveyResponses(): void
    {
        $this->testRelation('surveyResponses', SurveyResponse::class);
    }

    public function testGetWorkspace(): void
    {
        $this->testRelation('workspace', Workspace::class);
    }
}
