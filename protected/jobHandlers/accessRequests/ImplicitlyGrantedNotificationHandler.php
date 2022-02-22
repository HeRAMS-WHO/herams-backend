<?php

declare(strict_types=1);

namespace prime\jobHandlers\accessRequests;

use JCIT\jobqueue\interfaces\JobInterface;
use prime\jobs\accessRequests\ImplicitlyGrantedNotificationJob;
use prime\repositories\AccessRequestRepository;
use yii\mail\MailerInterface;

class ImplicitlyGrantedNotificationHandler
{
    public function __construct(
        private MailerInterface $mailer,
        private AccessRequestRepository $accessRequestRepository
    ) {
    }

    /**
     * @param ImplicitlyGrantedNotificationJob $job
     */
    public function handle(JobInterface $job): void
    {
        $accessRequest = $this->accessRequestRepository->retrieveOrThrow($job->getAccessRequestId());

        $this->mailer->compose(
            'access_request_implicitly_granted_notification',
            [
                'continueRoute' => ['/'],
                'accessRequest' => $accessRequest,
                'partial' => $job->getPartial(),
            ]
        )
            ->setTo($accessRequest->createdByUser->email)
            ->send()
        ;
    }
}
