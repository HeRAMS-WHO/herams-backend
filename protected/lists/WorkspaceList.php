<?php


namespace prime\lists;


use prime\interfaces\WorkspaceInterface;
use prime\interfaces\WorkspaceListInterface;

class WorkspaceList implements WorkspaceListInterface
{
    private $workspaces = [];

    /**
     * WorkspaceList constructor.
     * @param array $workspaces
     */
    public function __construct(array $workspaces)
    {
        foreach($workspaces as $workspace) {
            if (!$workspace instanceof WorkspaceInterface) {
                throw new \InvalidArgumentException('Expected instance of WorkspaceInterface, got: ' . get_class($workspace));
            }
            $this->workspaces[] = $workspace;
        }
    }

    public function getLength(): int
    {
        return count($this->workspaces);
    }

    public function get(int $i): WorkspaceInterface
    {
        return $this->workspaces[$i];
    }
}