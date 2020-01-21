<?php
declare(strict_types=1);

namespace prime\helpers;


use SamIT\abac\interfaces\Authorizable;
use SamIT\abac\interfaces\Resolver;
use SamIT\abac\values\Grant;

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

    public function getTarget():object
    {
        return $this->target;
    }

    public function getPermission(): string
    {
        return $this->permission;
    }

    public function createGrant(Resolver $resolver): Grant
    {
        return new Grant($resolver->fromSubject($this->source), $resolver->fromSubject($this->target), $this->permission);
    }

}