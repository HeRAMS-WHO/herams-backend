<?php

declare(strict_types=1);

namespace prime\tests\unit\models\response;

use Codeception\Test\Unit;
use prime\models\ar\ResponseForLimesurvey;
use prime\models\response\ResponseForBreadcrumb;
use prime\values\WorkspaceId;

/**
 * @covers \prime\models\response\ResponseForBreadcrumb
 */
class ResponseForBreadcrumbTest extends Unit
{
    public function testConstructor()
    {
        $label = 'Response label';
        $responseId = 12345;
        $workspaceId = 23456;

        $response = $this->getMockBuilder(ResponseForLimesurvey::class)->getMock();
        $response->id = $responseId;
        $response->expects($this->once())
            ->method('getName')
            ->willReturn($label);
        $response->expects($this->exactly(2))
            ->method('__get')
            ->withConsecutive(['workspace_id'], ['id'])
            ->willReturnOnConsecutiveCalls($workspaceId, $responseId);

        $forBreadcrumb = new ResponseForBreadcrumb($response);
        $this->assertEquals(['/response/compare', 'id' => $responseId], $forBreadcrumb->getUrl());
        $this->assertEquals(new WorkspaceId($workspaceId), $forBreadcrumb->getWorkspaceId());
        $this->assertEquals($label, $forBreadcrumb->getLabel());
    }
}
