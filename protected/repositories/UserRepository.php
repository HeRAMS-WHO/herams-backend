<?php

declare(strict_types=1);

namespace prime\repositories;

use prime\components\ActiveQuery;
use prime\models\ar\User;
use prime\models\user\UserForSelect2;
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

    /**
     * @return UserForSelect2[]
     */
    public function retrieveForSelect2(string $q, ?int $excludeUserId = null, $page = 0, $perPage = 5): array
    {
        $query = $this->find()
            ->andFilterWhere(['not', ['id' => $excludeUserId]])
            ->andWhere(['OR', ['like', 'name', $q], ['like', 'email', $q]])
            ->offset($page * $perPage)
            ->limit($perPage);

        $result = [];
        foreach ($query->each() as $user) {
            $result[] = new UserForSelect2($user);
        }

        return $result;
    }

    public function retrieveOrThrow(int $id): ?User
    {
        $result = $this->retrieve($id);

        if (!$result) {
            throw new InvalidArgumentException('No such User.');
        }

        return $result;
    }

    public function updateAll(array $attributes, array|string $condition = '', array $params = []): int
    {
        return User::updateAll($attributes, $condition, $params);
    }
}
