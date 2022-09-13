<?php

declare(strict_types=1);

namespace prime\queries;

use prime\components\ActiveQuery;
use prime\values\ProjectId;

class WorkspaceQuery extends ActiveQuery
{
    public function forProject(ProjectId $id): self
    {
        return $this->andWhere([
            'project_id' => $id,
        ]);
    }
}
