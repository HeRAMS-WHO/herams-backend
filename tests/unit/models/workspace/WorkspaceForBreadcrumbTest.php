<?php
declare(strict_types=1);

namespace prime\tests\unit\models\workspace;

use Codeception\Test\Unit;
use prime\models\ar\Workspace;
use prime\models\workspace\WorkspaceForBreadcrumb;
use prime\values\ProjectId;

/**
 * @covers \prime\models\workspace\WorkspaceForBreadcrumb
 */
class WorkspaceForBreadcrumbTest extends Unit
{
    public function testConstructor(): void
    {
        $label = 'Project label';
        $projectId = 23456;
        $workspaceId = 12345;

        $workspace = new Workspace();
        $workspace->id = $workspaceId;
        $workspace->title = $label;
        $workspace->tool_id = $projectId;

        $forBreadcrumb = new WorkspaceForBreadcrumb($workspace);
        $this->assertEquals(['/workspace/responses', 'id' => $workspaceId], $forBreadcrumb->getUrl());
        $this->assertEquals(new ProjectId($projectId), $forBreadcrumb->getProjectId());
        $this->assertEquals($label, $forBreadcrumb->getLabel());
    }
}
