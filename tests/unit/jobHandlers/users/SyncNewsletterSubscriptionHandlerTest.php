<?php

declare(strict_types=1);

namespace prime\tests\unit\jobHandlers\users;

use Codeception\Test\Unit;
use prime\components\NewsletterService;
use prime\jobHandlers\users\SyncNewsletterSubscriptionHandler;
use prime\jobs\users\SyncNewsletterSubscriptionJob;
use prime\models\ar\User;
use prime\repositories\UserRepository;

/**
 * @covers \prime\jobHandlers\users\SyncNewsletterSubscriptionHandler
 * @covers \prime\jobs\users\SyncNewsletterSubscriptionJob
 */
class SyncNewsletterSubscriptionHandlerTest extends Unit
{
    public function test()
    {
        $user = new User([
            'id' => 999999,
        ]);
        $insert = false;
        $userRepository = $this->getMockBuilder(UserRepository::class)->getMock();
        $userRepository->expects($this->once())
            ->method('retrieveOrThrow')
            ->with($user->id)
            ->willReturn($user);
        $newsLetterService = $this->getMockBuilder(NewsletterService::class)->disableOriginalConstructor()->getMock();
        $newsLetterService->expects($this->once())
            ->method('syncToExternal')
            ->with($user, $insert);

        $handler = new SyncNewsletterSubscriptionHandler($userRepository, $newsLetterService);
        $handler->handle(new SyncNewsletterSubscriptionJob($user->id, $insert));
    }
}
