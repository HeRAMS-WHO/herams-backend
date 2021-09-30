<?php
declare(strict_types=1);

namespace prime\tests\unit\repositories;

use Codeception\Test\Unit;
use prime\helpers\ModelHydrator;
use prime\interfaces\AccessCheckInterface;
use prime\models\ar\Project;
use prime\models\ar\ResponseForLimesurvey;
use prime\models\ar\WorkspaceForLimesurvey;
use prime\repositories\ProjectRepository;
use prime\repositories\ResponseRepository;
use prime\repositories\WorkspaceRepository;
use prime\values\ProjectId;
use prime\values\ResponseId;
use prime\values\WorkspaceId;
use yii\db\Expression;

/**
 * @covers \prime\repositories\WorkspaceRepository
 */
class WorkspaceRepositoryTest extends Unit
{
    public function testFindForBreadcrumb(): void
    {
        $model = new WorkspaceForLimesurvey([
            'title' => 'Test workspace',
            'token' => '12345',
            'tool_id' => 1,
        ]);
        $this->assertTrue($model->save());

        $accessChecker = $this->getMockBuilder(AccessCheckInterface::class)->disableOriginalConstructor()->getMock();
        $modelHydrator = $this->getMockBuilder(ModelHydrator::class)->disableOriginalConstructor()->getMock();

        $repository = new WorkspaceRepository($accessChecker, $modelHydrator);
        $breadcrumb = $repository->retrieveForBreadcrumb(new WorkspaceId($model->id));

        $this->assertEquals($model->title, $breadcrumb->getLabel());
        $this->assertEquals(['/workspace/responses', 'id' => $model->id], $breadcrumb->getUrl());
        $this->assertEquals(new ProjectId($model->tool_id), $breadcrumb->getProjectId());
    }
}
