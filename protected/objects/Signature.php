<?php

namespace prime\objects;

use prime\interfaces\SignatureInterface;

class Signature implements SignatureInterface
{
    private $email;
    private $id;
    private $name;
    private $time;

    public function __construct($email, $id, $name, \DateTimeImmutable $time = null) {
        $this->email = $email;
        $this->id = $id;
        $this->name = $name;
        if(!isset($time)) {
            $time = new \DateTimeImmutable();
        }
        $this->time = $time;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getTime()
    {
        return $this->time;
    }
}