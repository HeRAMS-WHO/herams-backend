<?php


namespace prime\models\ar;


use prime\interfaces\FacilityInterface;
use prime\interfaces\ServiceList;
use prime\interfaces\WorkspaceInterface;
use yii\base\Arrayable;
use yii\base\ArrayableTrait;

class Facility implements FacilityInterface, Arrayable
{
    use ArrayableTrait;
    private $name;

    public function __construct(string $name, string $id)
    {
        $this->name = $name;
        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getServiceList(): ServiceList
    {
        // TODO: Implement getServiceList() method.
    }

    public function getWorkspace(): WorkspaceInterface
    {
        // TODO: Implement getWorkspace() method.
    }


    public function fields()
    {
        return ['id', 'name'];
    }

}