<?php

declare(strict_types=1);

namespace herams\common\domain\user;

use herams\common\queries\ActiveQuery;
use yii\base\InvalidArgumentException;

final class UserRepository
{
    public function find(): ActiveQuery
    {
        return User::find();
    }

    public function retrieve(int $id): ?User
    {
        return User::findOne([
            'id' => $id,
        ]);
    }

    public function retrieveAll(): array
    {
        return User::find()
            ->select(['id', 'email', 'name'])
            ->all();
    }

    public function retrieveOrThrow(int $id): User
    {
        $result = $this->retrieve($id);

        if (! $result) {
            throw new InvalidArgumentException('No such User.');
        }

        return $result;
    }

    public function updateAll(array $attributes, array|string $condition = '', array $params = []): int
    {
        return User::updateAll($attributes, $condition, $params);
    }
}
