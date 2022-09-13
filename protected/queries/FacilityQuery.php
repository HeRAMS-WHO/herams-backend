<?php

declare(strict_types=1);

namespace prime\queries;

use prime\components\ActiveQuery;
use prime\values\WorkspaceId;

final class FacilityQuery extends ActiveQuery
{
    public function inWorkspace(WorkspaceId $id): self
    {
        return $this->andWhere([
            'workspace_id' => $id,
        ]);
    }

    public function useInList(): self
    {
        return $this->andWhere([
            'use_in_list' => true,
        ]);
    }
}
