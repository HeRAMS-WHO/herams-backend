<?php


namespace prime\interfaces;


interface WorkspaceInterface
{
    public function getId(): string;

    public function getName(): string;

    public function getProject(): ProjectInterface;

    public function getFacilities(): FacilityListInterface;

}