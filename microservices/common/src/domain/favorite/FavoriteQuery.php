<?php

declare(strict_types=1);

namespace herams\common\domain\favorite;

use herams\common\models\Project;
use herams\common\models\Workspace;
use herams\common\queries\ActiveQuery;
use herams\common\values\UserId;

class FavoriteQuery extends ActiveQuery
{
    public function filterTargetClass(string $class): self
    {
        return $this->andWhere([
            'target_class' => $class,
        ]);
    }

    public function projects(): self
    {
        return $this->filterTargetClass(Project::class);
    }

    public function user(UserId $user): self
    {
        return $this->andWhere([
            'user_id' => $user->getValue(),
        ]);
    }

    public function workspaces(): self
    {
        return $this->filterTargetClass(Workspace::class);
    }
}
