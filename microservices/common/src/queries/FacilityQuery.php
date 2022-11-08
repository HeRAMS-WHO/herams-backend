<?php

declare(strict_types=1);

namespace herams\common\queries;

use herams\common\values\WorkspaceId;

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
