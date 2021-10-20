<?php

declare(strict_types=1);

namespace prime\tests\unit\jobHandlers\accessRequests;

use Codeception\Test\Unit;
use prime\jobHandlers\accessRequests\ResponseNotificationHandler;
use prime\jobs\accessRequests\ResponseNotificationJob;
use prime\models\ar\AccessRequest;
use prime\models\ar\User;
use prime\repositories\AccessRequestRepository;
use yii\mail\MailerInterface;
use yii\mail\MessageInterface;

/**
 * @covers \prime\jobHandlers\accessRequests\ResponseNotificationHandler
 * @covers \prime\jobs\accessRequests\ResponseNotificationJob
 */
class ResponseNotificationHandlerTest extends Unit
{
    public function test()
    {
        $email = 'testemail@email.com';
        $id = 1;
        $mail = $this->getMockBuilder(MessageInterface::class)->getMock();
        $mailer = $this->getMockBuilder(MailerInterface::class)->getMock();
        $mail
            ->expects($this->once())
            ->method('setTo')
            ->with($email)
            ->willReturnSelf();
        $mail->expects($this->once())
            ->method('send');
        $mailer->expects($this->once())
            ->method('compose')
            ->willReturn($mail);

        $user = $this->getMockBuilder(User::class)->getMock();
        $user->expects($this->once())
            ->method('__get')
            ->with('email')
            ->willReturn($email);

        $accessRequest = $this->getMockBuilder(AccessRequest::class)->getMock();
        $accessRequest->expects($this->once())
            ->method('__get')
            ->with('createdByUser')
            ->willReturnOnConsecutiveCalls($user);

        $accessRequestRepository = $this->getMockBuilder(AccessRequestRepository::class)->getMock();
        $accessRequestRepository->expects($this->once())
            ->method('retrieveOrThrow')
            ->with($id)
            ->willReturn($accessRequest);

        $handler = new ResponseNotificationHandler($mailer, $accessRequestRepository);
        $handler->handle(new ResponseNotificationJob($id));
    }
}
