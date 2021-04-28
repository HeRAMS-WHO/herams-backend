<?php
declare(strict_types=1);

namespace prime\jobHandlers\accessRequests;

use JCIT\jobqueue\interfaces\JobInterface;
use prime\jobs\accessRequests\ResponseNotificationJob;
use yii\mail\MailerInterface;

class ResponseNotificationHandler extends AccessRequestHandler
{
    public function __construct(
        private MailerInterface $mailer
    ) {
    }

    /**
     * @param ResponseNotificationJob $job
     */
    public function handle(JobInterface $job): void
    {
        $accessRequest = $this->getAccessRequestOrThrow($job->getAccessRequestId());

        $this->mailer->compose(
            'access_request_response_notification',
            [
                'continueRoute' => ['/'],
                'accessRequest' => $accessRequest,
            ]
        )
            ->setTo($accessRequest->createdByUser->email)
            ->send()
        ;
    }
}
