<?php


namespace prime\interfaces;


interface ServiceInterface
{
    public function getId(): string;

    public function getName(): string;

    public function getFacility(): FacilityInterface;

}