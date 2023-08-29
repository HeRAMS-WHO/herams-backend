<?php

declare(strict_types=1);

namespace herams\common\domain\favorite;

final class FavoriteRepository
{
    public function deleteAll(array $condition): void
    {
        Favorite::deleteAll($condition);
    }
}
