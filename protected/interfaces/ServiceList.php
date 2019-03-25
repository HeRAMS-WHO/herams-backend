<?php

namespace prime\interfaces;

interface ServiceList
{
    public function getLength(): int;

    public function get(int $i): ServiceInterface;
}