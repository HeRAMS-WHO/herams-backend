<?php

declare(strict_types=1);

namespace herams\common\queries;

use herams\common\domain\favorite\Favorite;
use herams\common\values\ProjectId;
use herams\common\values\UserId;

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
