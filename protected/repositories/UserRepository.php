<?php
declare(strict_types=1);

namespace prime\repositories;

use prime\components\ActiveQuery;
use prime\models\ar\User;
use yii\base\InvalidArgumentException;

class UserRepository
{
    public function find(): ActiveQuery
    {
        return User::find();
    }

    public function retrieve(int $id): ?User
    {
        return User::findOne(['id' => $id]);
    }

    public function retrieveOrThrow(int $id): ?User
    {
        $result = $this->retrieve($id);

        if (!$result) {
            throw new InvalidArgumentException('No such User.');
        }

        return $result;
    }
}
