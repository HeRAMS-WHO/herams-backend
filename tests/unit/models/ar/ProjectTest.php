<?php

declare(strict_types=1);

namespace prime\tests\unit\models\ar;

use prime\models\ar\Page;
use prime\models\ar\Permission;
use prime\models\ar\Project;
use prime\objects\enums\ProjectStatus;
use prime\objects\enums\ProjectVisibility;

/**
 * @covers \prime\models\ar\Project
 */
class ProjectTest extends ActiveRecordTest
{
    public function validSamples(): array
    {
        return [
            [
                [
                    'title' => __CLASS__,
                    'admin_survey_id' => null,
                    'base_survey_eid' => 12345,
                    'data_survey_id' => null,
                    'hidden' => true,
                    'country' => 'NLD',
                    'latitude' => 4,
                    'longitude' => 5,
                    'status' => ProjectStatus::target()->value,
                    'visibility' => ProjectVisibility::public()->value,
                    'typemap' => [],
                    'overrides' => ['contributorCount' => 15],
                    'manage_implies_create_hf' => false,
                    'i18n' => [],
                    'languages' => ['fr-FR']
                ],
            ],
            [
                [
                    'title' => __CLASS__,
                    'admin_survey_id' => 1,
                    'base_survey_eid' => null,
                    'data_survey_id' => 1,
                    'hidden' => true,
                    'country' => 'NLD',
                    'latitude' => 4,
                    'longitude' => 5,
                    'status' => ProjectStatus::target()->value,
                    'visibility' => ProjectVisibility::public()->value,
                    'typemap' => [],
                    'overrides' => ['contributorCount' => 15],
                    'manage_implies_create_hf' => false,
                    'i18n' => [],
                    'languages' => ['fr-FR']
                ],
            ],
        ];
    }

    public function invalidSamples(): array
    {
        return [
            [
                [
                    'hidden' => 15,
                    'country' => 'test',
                    'latitude' => 'a',
                    'longitude' => 'b',
                    'status' => 16,
                    'visibility' => 'tes',
                    'typemap' => 'a',
                    'manage_implies_create_hf' => 'dontknow',
                    'overrides' => 'b',
                    'i18n' => 'test',
                    'languages' => ['nl-NL']
                ]
            ],
            [
                [
                    'title' => __CLASS__,
                    'admin_survey_id' => null,
                    'base_survey_eid' => null,
                    'data_survey_id' => null,
                    'hidden' => true,
                    'country' => 'NLD',
                    'latitude' => 4,
                    'longitude' => 5,
                    'status' => ProjectStatus::target()->value,
                    'visibility' => ProjectVisibility::public()->value,
                    'typemap' => [],
                    'overrides' => ['contributorCount' => 15],
                    'manage_implies_create_hf' => false,
                    'i18n' => [],
                    'languages' => ['nl-NL']
                ],
            ],
            [
                [

                ]
            ]
        ];
    }

    public function testGetPages(): void
    {
        $this->testRelation('pages', Page::class);
    }

    public function testGetMainPages(): void
    {
        $this->testRelation('mainPages', Page::class);
    }

    public function testGetPermissions(): void
    {
        $this->testRelation('permissions', Permission::class);
    }
}
