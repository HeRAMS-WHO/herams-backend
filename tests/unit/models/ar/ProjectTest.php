<?php

declare(strict_types=1);

namespace prime\tests\unit\models\ar;

use herams\common\enums\Language;
use herams\common\enums\ProjectVisibility;
use herams\common\models\Page;
use herams\common\models\PermissionOld;

/**
 * @covers \herams\common\models\Project
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
                    'visibility' => ProjectVisibility::Public->value,
                    'typemap' => [],
                    'overrides' => [
                        'contributorCount' => 15,
                    ],
                    'manage_implies_create_hf' => false,
                    'i18n' => [],
                    'languages' => [Language::frFR->value,
                    ],
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
                    'visibility' => ProjectVisibility::Public->value,
                    'typemap' => [],
                    'overrides' => [
                        'contributorCount' => 15,
                    ],
                    'manage_implies_create_hf' => false,
                    'i18n' => [],
                    'languages' => [Language::frFR->value,
                    ],
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
                    'languages' => ['nl-NL'],
                ],
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
                    'visibility' => ProjectVisibility::Public->value,
                    'typemap' => [],
                    'overrides' => [
                        'contributorCount' => 15,
                    ],
                    'manage_implies_create_hf' => false,
                    'i18n' => [],
                    'languages' => ['nl-NL',
                    ],
                ],
            ],
            [
                [

                ],
            ],
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
        $this->testRelation('permissions', PermissionOld::class);
    }
}
