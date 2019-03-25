<?php


namespace prime\interfaces;


interface WorkspaceListInterface
{
    public function getLength(): int;

    public function get(int $i): WorkspaceInterface;

}