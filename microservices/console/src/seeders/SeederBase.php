<?php
declare(strict_types=1);

namespace herams\console\seeders;

abstract class SeederBase
{
    abstract public function run(): void;
}