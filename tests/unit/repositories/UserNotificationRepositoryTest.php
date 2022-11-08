<?php

declare(strict_types=1);

namespace prime\tests\unit\repositories;

use Codeception\Test\Unit;
use herams\common\domain\user\User;
use herams\common\models\Permission;
use prime\models\ar\AccessRequest;
use prime\queries\AccessRequestQuery;
use prime\repositories\AccessRequestRepository;
use prime\repositories\UserNotificationRepository;
use SamIT\abac\AuthManager;

/**
 * @covers \prime\repositories\UserNotificationRepository
 */
class UserNotificationRepositoryTest extends Unit
{
    /**
     * @dataProvider scenarios
     */
    public function test(bool $canRespond, int $notificationCount)
    {
        $accessRequest = new AccessRequest([
            'id' => 1,
        ]);
        $activeQuery = $this->getMockBuilder(AccessRequestQuery::class)->disableOriginalConstructor()->getMock();
        $activeQuery->expects($this->once())
            ->method('notExpired')
            ->willReturnSelf();
        $activeQuery->expects($this->once())
            ->method('withoutResponse')
            ->willReturnSelf();
        $activeQuery->expects($this->once())
            ->method('each')
            ->willReturn([$accessRequest]);
        $accessRequestRepository = $this->getMockBuilder(AccessRequestRepository::class)->getMock();
        $accessRequestRepository->expects($this->once())
            ->method('find')
            ->willReturn($activeQuery);
        $user = new User([
            'id' => 2,
        ]);
        $abacManager = $this->getMockBuilder(AuthManager::class)->disableOriginalConstructor()->getMock();
        $abacManager->expects($this->once())
            ->method('check')
            ->with($user, $accessRequest, Permission::PERMISSION_RESPOND)
            ->willReturn($canRespond);

        $userNotificationRepository = new UserNotificationRepository(
            $abacManager,
            $accessRequestRepository
        );
        $this->assertEquals($notificationCount, $userNotificationRepository->getNotificationsForUser($user)->getTotalCount());
    }

    public function scenarios(): array
    {
        return [
            'withAccessRequestToRespondTo' => [true, 1],
            'withoutAccessRequestToRespondTo' => [false, 0],
        ];
    }
}
