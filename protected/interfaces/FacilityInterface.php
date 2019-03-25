<?php


namespace prime\interfaces;


interface FacilityInterface
{
    public function getId(): string;

    public function getName(): string;

    public function getServiceList(): ServiceList;

    public function getWorkspace(): WorkspaceInterface;
}