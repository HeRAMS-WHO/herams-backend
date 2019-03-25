<?php


namespace prime\lists;


use prime\interfaces\FacilityInterface;
use prime\interfaces\FacilityListInterface;

class FacilityList implements FacilityListInterface
{
    private $facilities = [];

    /**
     * WorkspaceList constructor.
     * @param array $workspaces
     */
    public function __construct(array $facilities)
    {
        foreach($facilities as $facility) {
            if (!$facility instanceof FacilityInterface) {
                throw new \InvalidArgumentException('Expected instance of FacilityInterface, got: ' . get_class($facility));
            }
            $this->facilities[] = $facility;
        }
    }

    public function getLength(): int
    {
        return count($this->facilities);
    }

    public function get(int $i): FacilityInterface
    {
        return $this->facilities[$i];
    }
}