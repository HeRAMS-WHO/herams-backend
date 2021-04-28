<?php
declare(strict_types=1);

namespace prime\repositories;

use prime\models\ar\AccessRequest;
use yii\base\InvalidArgumentException;

class AccessRequestRepository
{
    public function retrieve(int $id): ?AccessRequest
    {
        return AccessRequest::findOne(['id' => $id]);
    }

    public function retrieveOrThrow(int $id): ?AccessRequest
    {
        $result = $this->retrieve($id);

        if (!$result) {
            throw new InvalidArgumentException('No such Access Request.');
        }

        return $result;
    }
}
