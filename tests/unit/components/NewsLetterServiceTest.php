<?php
declare(strict_types=1);

namespace prime\tests\unit\components;

use Codeception\Test\Unit;
use DrewM\MailChimp\MailChimp;
use PHPUnit\Framework\MockObject\MockObject;
use prime\components\ActiveQuery;
use prime\components\NewsletterService;
use prime\models\ar\User;
use prime\repositories\UserRepository;
use yii\web\Request;

/**
 * @covers \prime\components\NewsletterService
 */
class NewsLetterServiceTest extends Unit
{
    protected function getUserRepository(): UserRepository|MockObject
    {
        return $this->getMockBuilder(UserRepository::class)->getMock();
    }

    protected function getMailChimp(): MailChimp|MockObject
    {
        return $this->getMockBuilder(MailChimp::class)->disableOriginalConstructor()->getMock();
    }

    /**
     * @dataProvider webhookDataProvider
     */
    public function testHandleWebhook(string $type, bool $subscription)
    {
        $userRepository = $this->getUserRepository();
        $user = $this->getMockBuilder(User::class)->getMock();
        $userEmail = 'test@test.com';
        $user->expects($this->once())
            ->method('updateAttributes')
            ->with(['newsletter_subscription' => $subscription]);
        $activeQuery = $this->getMockBuilder(ActiveQuery::class)->disableOriginalConstructor()->getMock();
        $activeQuery->expects(($this->once()))
            ->method('andWhere')
            ->with(['email' => $userEmail])
            ->willReturnSelf();
        $activeQuery->expects($this->once())
            ->method('one')
            ->willReturn($user);
        $userRepository->expects($this->once())
            ->method('find')
            ->willReturn($activeQuery);

        $mailChimp = $this->getMailChimp();
        $newsletterService = new NewsletterService($mailChimp, $userRepository);
        $request = new Request();
        $request->setRawBody(http_build_query([
            'type' => $type,
            'data' => [
                'email' => $userEmail
            ],
        ]));
        $newsletterService->handleWebhook($request);
    }

    public function syncExternalToDataProvider(): array
    {
        return [
            'cleaned' => ['cleaned', false],
            'pending' => ['pending', false],
            'subscribed' => ['subscribed', true],
            'transactional' => ['transactional', false],
            'unsubscribed' => ['unsubscribed', false],
        ];
    }

    public function syncToExternalDataProvider(): array
    {
        return [
            'subscribed' => ['test1@test.nl', true],
            'unsubscribed' => ['test2@test.nl', false],
        ];
    }

    /**
     * @dataProvider syncExternalToDataProvider
     */
    public function testSyncExternalToDatabase(string $status, bool $subscribed)
    {
        $userEmail = 'test@test.com';
        $userRepository = $this->getUserRepository();
        $userRepository->expects($this->once())
            ->method('updateAll')
            ->with(['newsletter_subscription' => $subscribed], ['email' => $userEmail]);
        $mailChimp = $this->getMailChimp();
        $mailChimp->expects($this->once())
            ->method('get')
            ->willReturn([
                'members' => [
                    [
                        'email_address' => $userEmail,
                        'status' => $status,
                    ]
                ]
            ]);
        $newsletterService = new NewsletterService($mailChimp, $userRepository);
        $newsletterService->mailchimpListId = 'testListId';

        $newsletterService->initSyncExternalToDatabase();
    }

    /**
     * @dataProvider syncToExternalDataProvider
     */
    public function testSyncToExternal(string $email, bool $subscribed)
    {
        $user = new User(['email' => $email, 'newsletter_subscription' => $subscribed]);
        $userRepository = $this->getUserRepository();
        $mailChimp = $this->getMailChimp();
        $listId = 'testListId';
        $tag = 'testTag';

        $userHash = md5($user->email);
        $link = "lists/{$listId}/members/{$userHash}";
        $mailChimp->expects($this->once())
            ->method('put')
            ->with($link, [
                'email_address' => $user->email,
                'status_if_new' => $subscribed ? 'subscribed' : 'unsubscribed',
                'status' => $subscribed ? 'subscribed' : 'unsubscribed',
                'tags' => [
                    $tag,
                ]
            ]);

        $newsletterService = new NewsletterService($mailChimp, $userRepository);
        $newsletterService->mailchimpListId = $listId;
        $newsletterService->mailchimpTag = $tag;

        $newsletterService->syncToExternal($user, false);
    }

    public function webhookDataProvider(): array
    {
        return [
            'subscribe' => ['subscribe', true],
            'unsubscribe' => ['unsubscribe', false],
        ];
    }
}
