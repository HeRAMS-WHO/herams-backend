<?php

declare(strict_types=1);

namespace herams\common\values;

use herams\common\models\Project;

class ProjectId extends IntegerId
{
    public static function fromProject(Project $project): static
    {
        if (! is_integer($project->id)) {
            throw new \InvalidArgumentException('Project must have an id');
        }
        return new ProjectId($project->id);
    }
}
