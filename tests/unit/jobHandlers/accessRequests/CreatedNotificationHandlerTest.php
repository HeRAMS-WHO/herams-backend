<?php
declare(strict_types=1);

namespace prime\tests\unit\jobHandlers\accessRequests;

use Codeception\Test\Unit;
use prime\components\ActiveQuery;
use prime\jobHandlers\accessRequests\CreatedNotificationHandler;
use prime\jobs\accessRequests\CreatedNotificationJob;
use prime\models\ar\AccessRequest;
use prime\models\ar\Project;
use prime\repositories\AccessRequestRepository;
use yii\mail\MailerInterface;
use yii\mail\MessageInterface;

/**
 * @covers \prime\jobHandlers\accessRequests\CreatedNotificationHandler
 * @covers \prime\jobs\accessRequests\CreatedNotificationJob
 * @covers \prime\jobs\accessRequests\AccessRequestJob
 */
class CreatedNotificationHandlerTest extends Unit
{
    public function test()
    {
        $emails = ['testemail@email.com'];
        $id = 1;
        $mail = $this->getMockBuilder(MessageInterface::class)->getMock();
        $mailer = $this->getMockBuilder(MailerInterface::class)->getMock();
        $mail
            ->expects($this->once())
            ->method('setBcc')
            ->with($emails)
            ->willReturnSelf();
        $mail->expects($this->once())
            ->method('send');
        $mailer->expects($this->once())
            ->method('compose')
            ->willReturn($mail);

        $userQuery = $this->getMockBuilder(ActiveQuery::class)->disableOriginalConstructor()->getMock();
        $userQuery->expects($this->any())
            ->method('select')
            ->willReturn($userQuery);
        $userQuery->expects($this->once())
            ->method('andWhere')
            ->willReturnSelf();
        $userQuery->expects($this->once())
            ->method('column')
            ->willReturn($emails);
        $project = $this->getMockBuilder(Project::class)->getMock();
        $project->expects($this->once())
            ->method('getLeads')
            ->willReturn($userQuery);
        $accessRequest = $this->getMockBuilder(AccessRequest::class)->getMock();
        $accessRequest->expects($this->any())
            ->method('__get')
            ->withConsecutive([$this->equalTo('id')], [$this->equalTo('target')])
            ->willReturnOnConsecutiveCalls($id, $project);

        $accessRequestRepository = $this->getMockBuilder(AccessRequestRepository::class)->getMock();
        $accessRequestRepository->expects($this->once())
            ->method('retrieveOrThrow')
            ->with($id)
            ->willReturn($accessRequest);

        $handler = new CreatedNotificationHandler($mailer, $accessRequestRepository);
        $handler->handle(new CreatedNotificationJob($id));
    }
}
