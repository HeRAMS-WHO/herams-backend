<?php

declare(strict_types=1);

namespace herams\common\helpers;

use herams\common\interfaces\CurrentUserIdProviderInterface;
use herams\common\values\UserId;

class CurrentUserIdProvider implements CurrentUserIdProviderInterface
{
    public function getUserId(): UserId
    {
        if (\Yii::$app?->user?->getId() !== null) {
            return new UserId(\Yii::$app->user->getId());
        }
        throw new \RuntimeException("No current user");
    }
}
