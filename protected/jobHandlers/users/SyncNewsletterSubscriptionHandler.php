<?php

declare(strict_types=1);

namespace prime\jobHandlers\users;

use herams\common\domain\user\UserRepository;
use JCIT\jobqueue\interfaces\JobHandlerInterface;
use JCIT\jobqueue\interfaces\JobInterface;
use prime\components\NewsletterService;
use prime\jobs\users\SyncNewsletterSubscriptionJob;

class SyncNewsletterSubscriptionHandler implements JobHandlerInterface
{
    public function __construct(
        private UserRepository $userRepository,
        private NewsletterService $newsletterService
    ) {
    }

    public function handle(JobInterface|SyncNewsletterSubscriptionJob $job): void
    {
        $user = $this->userRepository->retrieveOrThrow($job->getUserId());
        $this->newsletterService->syncToExternal($user, $job->getInsert());
    }
}
