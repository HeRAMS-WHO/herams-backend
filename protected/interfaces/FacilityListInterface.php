<?php


namespace prime\interfaces;


interface FacilityListInterface
{
    public function getLength(): int;

    public function get(int $i): FacilityInterface;
}