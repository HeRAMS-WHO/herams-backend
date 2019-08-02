<?php

namespace app\queries;

use prime\components\ActiveQuery;
use prime\models\ar\Project;
use prime\models\permissions\Permission;

class WorkspaceQuery extends ActiveQuery
{
    /**
     * @return self
     */
    public function closed()
    {
        return $this->andWhere(['not', ['closed' => null]]);
    }

    /**
     * @return self
     */
    public function notClosed()
    {
        return $this->andWhere(['closed' => null]);
    }

    public function inProject(Project $project)
    {
        return $this->andWhere(['tool_id' => $project->id]);
    }

    public function readable()
    {
        return $this->userCan(Permission::PERMISSION_READ);
    }

    public function notReadable()
    {
        return $this->userCannot(Permission::PERMISSION_READ);
    }
}