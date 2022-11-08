<?php

declare(strict_types=1);

namespace prime\jobHandlers\accessRequests;

use herams\common\domain\user\User;
use JCIT\jobqueue\interfaces\JobInterface;
use prime\jobs\accessRequests\CreatedNotificationJob;
use prime\models\ar\AccessRequest;
use prime\repositories\AccessRequestRepository;
use yii\helpers\Url;
use yii\mail\MailerInterface;
use function iter\func\index;
use function iter\rewindable\filter;
use function iter\rewindable\map;
use function iter\toArray;

class CreatedNotificationHandler
{
    public function __construct(
        private MailerInterface $mailer,
        private AccessRequestRepository $accessRequestRepository
    ) {
    }

    public function handle(JobInterface|CreatedNotificationJob $job): void
    {
        $accessRequest = $this->accessRequestRepository->retrieveOrThrow($job->getAccessRequestId());
        $this->mailer->compose(
            'access_request_created_notification',
            [
                'respondUrl' => Url::to([
                    '/access-request/respond',
                    'id' => $accessRequest->id,
                ], true),
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
        $leads = $target->getLeads();
        $leads = filter(static fn (User $user) => $user->id != $accessRequest->created_by, $leads);
        return toArray(map(index('email'), $leads));
    }
}
