<?php
declare(strict_types=1);

namespace prime\queries;

use prime\components\ActiveQuery;
use prime\interfaces\HeramsResponseInterface;
use prime\models\ar\Project;
use prime\models\ar\Workspace;

/**
 * Class ResponseQuery
 * @package prime\queries
 * @method HeramsResponseInterface[] each()
 */
class ResponseQuery extends ActiveQuery
{

    public function project(Project $project): self
    {

        return $this->andWhere(["{$this->getPrimaryTableName()}.[[workspace_id]]" => $project->getWorkspaces()->select('id')->column()]);
    }

    public function workspace(Workspace $workspace): self
    {
        return $this->andWhere(["{$this->getPrimaryTableName()}.[[workspace_id]]" => $workspace->id]);
    }
}
