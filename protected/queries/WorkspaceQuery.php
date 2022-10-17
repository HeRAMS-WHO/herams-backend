<?php

declare(strict_types=1);

namespace prime\queries;

use prime\components\ActiveQuery;
use prime\models\ar\Favorite;
use prime\values\ProjectId;
use prime\values\UserId;

class WorkspaceQuery extends ActiveQuery
{
    public function forProject(ProjectId $id): self
    {
        return $this->andWhere([
            'project_id' => $id,
        ]);
    }

    public function isFavoriteOfUser(UserId $id): self
    {
        return $this->andWhere([
            'exists', Favorite::find()
                ->user($id)
                ->workspaces(),

        ]);
    }
}
