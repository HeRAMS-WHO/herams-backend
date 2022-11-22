<?php

declare(strict_types=1);

namespace prime\tests\unit\jobHandlers\permissions;

use Codeception\Test\Unit;
use herams\common\domain\element\Element;
use herams\common\domain\permission\PermissionRepository;
use herams\common\domain\user\User;
use herams\common\jobs\accessRequests\ImplicitlyGrantedNotificationJob;
use herams\common\jobs\permissions\CheckImplicitAccessRequestGrantedJob;
use herams\common\models\Permission;
use herams\common\models\Project;
use JCIT\jobqueue\interfaces\JobQueueInterface;
use prime\jobHandlers\permissions\CheckImplicitAccessRequestGrantedHandler;
use prime\models\ar\AccessRequest;
use prime\queries\AccessRequestQuery;
use prime\repositories\AccessRequestRepository;
use SamIT\abac\AuthManager;
use SamIT\abac\interfaces\Resolver;
use SamIT\abac\values\Authorizable;

/**
 * @covers \prime\jobHandlers\permissions\CheckImplicitAccessRequestGrantedHandler
 */
class CheckImplicitAccessRequestGrantedHandlerTest extends Unit
{
    public function test()
    {
        $permissionId = 1;
        $accessRequestId = 2;
        $permissionCreatedById = 3;
        $projectId = 4;
        $response = \Yii::t('app', 'Implicitly granted');
        $accessRequestPermissions = [
            AccessRequest::PERMISSION_READ,
            AccessRequest::PERMISSION_WRITE,
            AccessRequest::PERMISSION_OTHER,
        ];

        $project = new Project([
            'id' => $projectId,
        ]);
        $user = new User([
            'id' => $permissionCreatedById,
        ]);
        $resolver = $this->getMockBuilder(Resolver::class)->getMock();
        $resolver->expects($this->once())
            ->method('toSubject')
            ->willReturn($project);
        $permission = $this->getMockBuilder(Permission::class)->getMock();
        $permission->expects($this->once())
            ->method('targetAuthorizable')
            ->willReturn(new Authorizable((string) $projectId, Project::class));
        $permission->expects($this->once())
            ->method('__get')
            ->with('created_by')
            ->willReturn($permissionCreatedById);
        $permissionRepository = $this->getMockBuilder(PermissionRepository::class)->getMock();
        $permissionRepository->expects($this->once())
            ->method('retrieve')
            ->with($permissionId)
            ->willReturn($permission);

        $accessRequest = $this->getMockBuilder(AccessRequest::class)->getMock();
        $accessRequest->expects($this->exactly(6))
            ->method('__get')
            ->withConsecutive(['permissions'], ['createdByUser'], ['target'], ['createdByUser'], ['target'], ['id'])
            ->willReturnOnConsecutiveCalls($accessRequestPermissions, $user, $project, $user, $project, $accessRequestId);
        $accessRequest->expects($this->exactly(3))
            ->method('__set')
            ->withConsecutive(['response', $response], ['responded_by', $permissionCreatedById], ['accepted', true]);
        $accessRequest->expects($this->once())
            ->method('save');
        $accessRequestQuery = $this->getMockBuilder(AccessRequestQuery::class)->disableOriginalConstructor()
            ->onlyMethods(['notExpired', 'withoutResponse', 'each'])
            ->getMock();
        $accessRequestQuery->expects($this->any())
            ->method('notExpired')
            ->willReturnSelf();
        $accessRequestQuery->expects($this->any())
            ->method('withoutResponse')
            ->willReturnSelf();
        $accessRequestQuery->expects($this->once())
            ->method('each')
            ->willReturn([$accessRequest]);
        $accessRequestRepository = $this->getMockBuilder(AccessRequestRepository::class)->getMock();
        $accessRequestRepository->expects($this->once())
            ->method('find')
            ->willReturn($accessRequestQuery);

        $jobQueue = $this->getMockBuilder(JobQueueInterface::class)->getMock();
        $jobQueue->expects($this->once())
            ->method('putJob')
            ->with($this->equalTo(new ImplicitlyGrantedNotificationJob($accessRequestId, true)));

        $authManager = $this->getMockBuilder(AuthManager::class)->disableOriginalConstructor()->onlyMethods(['check'])->getMock();
        $authManager->expects($this->exactly(2))
            ->method('check')
            ->willReturnOnConsecutiveCalls(true, false);

        $handler = new CheckImplicitAccessRequestGrantedHandler($authManager, $accessRequestRepository, $jobQueue, $permissionRepository, $resolver);
        $handler->handle(new CheckImplicitAccessRequestGrantedJob($permissionId));
    }

    public function testOtherPermission()
    {
        $permissionId = 1;
        $elementId = 4;

        $element = new Element([
            'id' => $elementId,
        ]);
        $resolver = $this->getMockBuilder(Resolver::class)->getMock();
        $resolver->expects($this->once())
            ->method('toSubject')
            ->willReturn($element);
        $permission = $this->getMockBuilder(Permission::class)->getMock();
        $permission->expects($this->once())
            ->method('targetAuthorizable')
            ->willReturn(new Authorizable((string) $elementId, Element::class));
        $permissionRepository = $this->getMockBuilder(PermissionRepository::class)->getMock();
        $permissionRepository->expects($this->once())
            ->method('retrieve')
            ->with($permissionId)
            ->willReturn($permission);

        $jobQueue = $this->getMockBuilder(JobQueueInterface::class)->getMock();
        $jobQueue->expects($this->never())
            ->method('putJob');

        $accessRequestRepository = $this->getMockBuilder(AccessRequestRepository::class)->getMock();
        $authManager = $this->getMockBuilder(AuthManager::class)->disableOriginalConstructor()->onlyMethods(['check'])->getMock();

        $handler = new CheckImplicitAccessRequestGrantedHandler($authManager, $accessRequestRepository, $jobQueue, $permissionRepository, $resolver);
        $handler->handle(new CheckImplicitAccessRequestGrantedJob($permissionId));
    }
}
