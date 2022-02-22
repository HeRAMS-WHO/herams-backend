<?php

declare(strict_types=1);

namespace prime\repositories;

use prime\models\ar\AccessRequest;
use prime\queries\AccessRequestQuery;
use yii\base\InvalidArgumentException;

class AccessRequestRepository
{
    public function find(): AccessRequestQuery
    {
        return AccessRequest::find();
    }

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
