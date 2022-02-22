<?php

declare(strict_types=1);

namespace prime\interfaces;

/**
 * This interface should be implemented by items that support being requested access to.
 */
interface RequestableInterface
{
    public function getTitle(): string;
    public function getRoute(): array;

    /**
     * Since most of our entities are in a hierarchy below a project we require this for now.
     * @return string
     */
    public function getProjectTitle(): string;
}
