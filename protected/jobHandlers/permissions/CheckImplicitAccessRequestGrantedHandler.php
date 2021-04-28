<?php
declare(strict_types=1);

namespace prime\jobHandlers\permissions;

use JCIT\jobqueue\interfaces\JobInterface;
use JCIT\jobqueue\interfaces\JobQueueInterface;
use prime\jobs\accessRequests\ImplicitlyGrantedJob;
use prime\jobs\permissions\CheckImplicitAccessRequestGrantedJob;
use prime\models\ar\AccessRequest;
use prime\models\ar\Permission;
use prime\models\ar\Project;
use prime\models\ar\Workspace;
use SamIT\abac\AuthManager;
use SamIT\abac\interfaces\Resolver;

class CheckImplicitAccessRequestGrantedHandler extends PermissionHandler
{
    public array $permissionMap = [
        AccessRequest::PERMISSION_READ => Permission::PERMISSION_READ,
        AccessRequest::PERMISSION_WRITE => Permission::PERMISSION_WRITE,
        AccessRequest::PERMISSION_EXPORT => Permission::PERMISSION_EXPORT,
    ];

    public function __construct(
        private AuthManager $abacManager,
        private Resolver $resolver,
        private JobQueueInterface $jobQueue
    ) {
    }

    /**
     * @param CheckImplicitAccessRequestGrantedJob $job
     */
    public function handle(JobInterface $job): void
    {
        $permission = $this->getPermissionOrThrow($job->getPermissionId());
        $target = $this->resolver->toSubject($permission->targetAuthorizable());

        if (!$target
            || !(
                $target instanceof Project
                || $target instanceof Workspace
            )
        ) {
            return;
        }

        // Assumed there are not that many access requests we just loop over all of them to check if it was granted
        $accessRequestsQuery = AccessRequest::find()->notExpired()->withoutResponse();
        /** @var AccessRequest $accessRequest */
        foreach ($accessRequestsQuery->each() as $accessRequest) {
            $result = false;
            $partial = false;
            foreach ($accessRequest->permissions as $requestedPermission) {
                // The requested permission cannot be checked automatically (like "other")
                if (!isset($this->permissionMap[$requestedPermission])) {
                    $partial = true;
                } elseif ($this->abacManager->check($accessRequest->createdByUser, $accessRequest->target, $this->permissionMap[$requestedPermission])) {
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
                $this->jobQueue->putJob(new ImplicitlyGrantedJob($accessRequest->id, $partial));
            }
        }
    }
}
