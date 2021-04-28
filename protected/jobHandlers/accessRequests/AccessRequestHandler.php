<?php
declare(strict_types=1);

namespace prime\jobHandlers\accessRequests;

use JCIT\jobqueue\exceptions\PermanentException;
use JCIT\jobqueue\interfaces\JobHandlerInterface;
use prime\models\ar\AccessRequest;

abstract class AccessRequestHandler implements JobHandlerInterface
{
    protected function getAccessRequest(int $accessRequestId): ?AccessRequest
    {
        return AccessRequest::findOne(['id' => $accessRequestId]);
    }

    protected function getAccessRequestOrThrow(int $accessRequestId): AccessRequest
    {
        $result = $this->getAccessRequest($accessRequestId);

        if (!$result) {
            throw new PermanentException('No such Access Request.');
        }

        return $result;
    }
}
