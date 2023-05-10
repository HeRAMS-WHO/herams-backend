<?php
declare(strict_types=1);

namespace prime\models\ar;

use yii\db\ActiveQuery;

class WorkspaceQuery extends ActiveQuery
{
    public function withDetails(int $toolId, int $userId, int $limit = 10)
    {
        $this->alias('w')
            ->select([
                'w.*',
                'r.latestUpdate',
                'r.facilityCount',
                'r.responseCount',
                'p.contributorCount',
            ])
            ->leftJoin(
                '(SELECT workspace_id, MAX(last_updated) AS latestUpdate, COUNT(DISTINCT hf_id) AS facilityCount, COUNT(*) AS responseCount FROM prime2_response GROUP BY workspace_id) r',
                'r.workspace_id = w.id'
            )
            ->leftJoin(
                '(SELECT target_id, COUNT(DISTINCT source_id) AS contributorCount FROM prime2_permission WHERE target = \'prime\\\\models\\\\ar\\\\Workspace\' AND source = \'prime\\\\models\\\\ar\\\\User\' GROUP BY target_id) p',
                'p.target_id = w.id'
            )
            ->where(['w.tool_id' => $toolId])
            ->orderBy([
                'w.id NOT IN (SELECT target_id FROM prime2_favorite WHERE target_class = \'prime\\\\models\\\\ar\\\\Workspace\' AND user_id = :userId)' => SORT_ASC,
                'r.latestUpdate' => SORT_DESC
            ])
            ->limit($limit)
            ->addParams([':userId' => $userId]);

        return $this;
    }

    public function withFields(array $fields)
    {
        $this->select($fields);
        return $this;
    }
}
