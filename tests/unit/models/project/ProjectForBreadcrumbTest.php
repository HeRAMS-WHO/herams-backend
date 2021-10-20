<?php

declare(strict_types=1);

namespace prime\tests\unit\models\project;

use Codeception\Test\Unit;
use prime\models\ar\Project;
use prime\models\project\ProjectForBreadcrumb;

/**
 * @covers \prime\models\project\ProjectForBreadcrumb
 */
class ProjectForBreadcrumbTest extends Unit
{
    public function testConstructor(): void
    {
        $projectId = 12345;
        $label = 'Project label';

        $project = new Project();
        $project->id = $projectId;
        $project->title = $label;

        $forBreadcrumb = new ProjectForBreadcrumb($project);
        $this->assertEquals(['/project/view', 'id' => $projectId], $forBreadcrumb->getUrl());
        $this->assertEquals($label, $forBreadcrumb->getLabel());
    }
}
