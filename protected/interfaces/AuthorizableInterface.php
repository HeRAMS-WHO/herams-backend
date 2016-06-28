<?php


namespace prime\interfaces;


interface AuthorizableInterface
{
    /**
     * @return string The name to use when saving / reading permissions.
     */
    public function getAuthName();
}