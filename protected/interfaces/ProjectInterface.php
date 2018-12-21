<?php


namespace prime\interfaces;


interface ProjectInterface extends \Serializable
{
    public function getId(): string;

    public function getWorkspaces(): WorkspaceListInterface;



}