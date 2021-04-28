<?php
declare(strict_types=1);

namespace prime\jobHandlers\accessRequests;

use JCIT\jobqueue\interfaces\JobInterface;
use prime\jobs\accessRequests\ImplicitlyGrantedJob;
use yii\mail\MailerInterface;

class ImplicitlyGrantedHandler extends AccessRequestHandler
{
    public function __construct(
        private MailerInterface $mailer
    ) {
    }

    /**
     * @param ImplicitlyGrantedJob $job
     */
    public function handle(JobInterface $job): void
    {
        $accessRequest = $this->getAccessRequestOrThrow($job->getAccessRequestId());

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
