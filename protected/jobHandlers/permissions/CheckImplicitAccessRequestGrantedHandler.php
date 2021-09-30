<?php
declare(strict_types=1);

namespace prime\jobHandlers\permissions;

use JCIT\jobqueue\interfaces\JobInterface;
use JCIT\jobqueue\interfaces\JobQueueInterface;
use prime\jobs\accessRequests\ImplicitlyGrantedNotificationJob;
use prime\jobs\permissions\CheckImplicitAccessRequestGrantedJob;
use prime\models\ar\AccessRequest;
use prime\models\ar\Project;
use prime\models\ar\WorkspaceForLimesurvey;
use prime\repositories\AccessRequestRepository;
use prime\repositories\PermissionRepository;
use SamIT\abac\AuthManager;
use SamIT\abac\interfaces\Resolver;

class CheckImplicitAccessRequestGrantedHandler
{
    public function __construct(
        private AuthManager $abacManager,
        private AccessRequestRepository $accessRequestRepository,
        private JobQueueInterface $jobQueue,
        private PermissionRepository $permissionRepository,
        private Resolver $resolver,
    ) {
    }

    /**
     * @param CheckImplicitAccessRequestGrantedJob $job
     */
    public function handle(JobInterface $job): void
    {
        $permission = $this->permissionRepository->retrieveOrThrow($job->getPermissionId());
        $target = $this->resolver->toSubject($permission->targetAuthorizable());

        if (!$target
            || !(
                $target instanceof Project
                || $target instanceof WorkspaceForLimesurvey
            )
        ) {
            return;
        }
        $permissionMap = AccessRequest::permissionMap($target);

        // Assumed there are not that many access requests we just loop over all of them to check if it was granted
        $accessRequestsQuery = $this->accessRequestRepository->find()->notExpired()->withoutResponse();
        /** @var AccessRequest $accessRequest */
        foreach ($accessRequestsQuery->each() as $accessRequest) {
            $result = false;
            $partial = false;
            foreach ($accessRequest->permissions as $requestedPermission) {
                // The requested permission cannot be checked automatically (like "other")
                if (!isset($permissionMap[$requestedPermission])) {
                    $partial = true;
                } elseif ($this->abacManager->check($accessRequest->createdByUser, $accessRequest->target, $permissionMap[$requestedPermission])) {
                    $result = true;
                } else {
                    $partial = true;
                }
            }

            if ($result) {
                $accessRequest->response = \Yii::t('app', 'Implicitly granted');
                $accessRequest->responded_by = $permission->created_by;
                $accessRequest->accepted = true;
                $accessRequest->save();
                $accessRequest->touch('responded_at');
                $this->jobQueue->putJob(new ImplicitlyGrantedNotificationJob($accessRequest->id, $partial));
            }
        }
    }
}
