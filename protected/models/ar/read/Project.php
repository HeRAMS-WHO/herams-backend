<?php

declare(strict_types=1);

namespace prime\models\ar\read;

use herams\common\interfaces\ProjectForTabMenuInterface;
use herams\common\models\ActiveRecord;
use herams\common\traits\ReadOnlyTrait;
use herams\common\values\ProjectId;

class Project extends \herams\common\models\Project implements ProjectForTabMenuInterface
{
    use ReadOnlyTrait;

    public static function instantiate($row): static
    {
        return forward_static_call(ActiveRecord::instantiate(...), $row);
    }

    public function getId(): ProjectId
    {
        return new ProjectId($this->getAttribute('id'));
    }

    public function getWorkspaceCount(): int
    {
        return $this->getVirtualField('workspaceCount');
    }

    public function getPermissionSourceCount(): int
    {
        return $this->getVirtualField('permissionSourceCount');
    }

    public function getLabel(): string
    {
        return $this->title;
    }
}
