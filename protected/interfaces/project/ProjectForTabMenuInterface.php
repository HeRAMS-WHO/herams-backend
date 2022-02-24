<?php

declare(strict_types=1);

namespace prime\interfaces\project;

use prime\values\ProjectId;

interface ProjectForTabMenuInterface
{
    public function getLabel(): string;
    public function getId(): ProjectId;
    public function getWorkspaceCount(): int;
    public function getPermissionSourceCount(): int;
}
