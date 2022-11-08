<?php

declare(strict_types=1);

namespace herams\common\interfaces;

use herams\common\values\ProjectId;

interface ProjectForTabMenuInterface
{
    public function getId(): ProjectId;

    public function getWorkspaceCount(): int;

    public function getPermissionSourceCount(): int;
}
