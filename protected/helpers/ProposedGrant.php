<?php

declare(strict_types=1);

namespace prime\helpers;

class ProposedGrant
{
    private $source;
    private $target;
    private $permission;
    public function __construct(object $source, object $target, string $permission)
    {
        $this->source = $source;
        $this->target = $target;
        $this->permission = $permission;
    }

    public function getTarget(): object
    {
        return $this->target;
    }

    public function getPermission(): string
    {
        return $this->permission;
    }

    public function getSource(): object
    {
        return $this->source;
    }
}
