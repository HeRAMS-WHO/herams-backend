<?php

declare(strict_types=1);

namespace prime\tests\unit\models\facility;

use Codeception\Test\Unit;
use prime\models\ar\Facility;
use prime\models\ar\ResponseForLimesurvey;
use prime\models\facility\FacilityForBreadcrumb;
use prime\values\WorkspaceId;

/**
 * @covers \prime\models\facility\FacilityForBreadcrumb
 */
class FacilityForBreadcrumbTest extends Unit
{
    public function testConstructorForFacility(): void
    {
        $facilityId = 12345;
        $label = 'Facility label';
        $workspaceId = 23456;

        $facility = new Facility();
        $facility->id = $facilityId;
        $facility->name = $label;
        $facility->workspace_id = $workspaceId;

        $forBreadcrumb = new FacilityForBreadcrumb($facility);
        $this->assertEquals(['/facility/responses', 'id' => $facilityId], $forBreadcrumb->getUrl());
        $this->assertEquals(new WorkspaceId($workspaceId), $forBreadcrumb->getWorkspaceId());
        $this->assertEquals($label, $forBreadcrumb->getLabel());
    }

    public function testConstructorForResponse(): void
    {
        $facilityId = 12345;
        $workspaceId = 23456;

        $response = new ResponseForLimesurvey();
        $response->hf_id = $facilityId;
        $response->workspace_id = $workspaceId;

        $forBreadcrumb = new FacilityForBreadcrumb($response);
        $this->assertEquals(['/facility/responses', 'id' => $facilityId], $forBreadcrumb->getUrl());
        $this->assertEquals(new WorkspaceId($workspaceId), $forBreadcrumb->getWorkspaceId());
        $this->assertEquals($facilityId, $forBreadcrumb->getLabel());
    }
}
