<?php
declare(strict_types=1);

namespace prime\queries;

use prime\components\ActiveQuery;

class FavoriteQuery extends ActiveQuery
{
    public function filterTargetClass(string $class)
    {
        return $this->andWhere(['target_class' => $class]);
    }
}
