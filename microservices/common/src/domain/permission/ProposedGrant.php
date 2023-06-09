<?php

declare(strict_types=1);

namespace herams\common\domain\permission;

class ProposedGrant
{
    public function __construct(
        private object $source,
        private object $target,
        private string $permission
    ) {
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
