<?php
declare(strict_types=1);

namespace prime\queries;

use prime\components\ActiveQuery;
use prime\models\ar\User;
use prime\models\ar\Workspace;

class FavoriteQuery extends ActiveQuery
{
    public function filterTargetClass(string $class): self
    {
        return $this->andWhere(['target_class' => $class]);
    }
    public function workspaces(): self
    {
        return $this->filterTargetClass(Workspace::class);
    }

    public function user(User $user): self
    {
        return $this->andWhere(['user_id' => $user->id]);
    }
}
