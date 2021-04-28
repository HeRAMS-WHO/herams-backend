<?php
declare(strict_types=1);

namespace prime\jobHandlers\accessRequests;

use JCIT\jobqueue\interfaces\JobInterface;
use prime\jobs\accessRequests\ResponseNotificationJob;
use prime\models\ar\AccessRequest;
use prime\models\ar\User;
use yii\mail\MailerInterface;
use function iter\rewindable\map;
use function iter\toArray;

class CreatedNotificationHandler extends AccessRequestHandler
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
            'access_request_created_notification',
            [
                'respondUrl' => ['/access-request/respond', 'id' => $accessRequest->id],
                'accessRequest' => $accessRequest,
            ]
        )
            ->setBcc($this->getTargetEmails($accessRequest))
            ->send()
        ;
    }

    /**
     * @return string[]
     */
    private function getTargetEmails(AccessRequest $accessRequest): array
    {
        $target = $accessRequest->target;
        return $target->getLeads()->select('email')->column();
    }
}
