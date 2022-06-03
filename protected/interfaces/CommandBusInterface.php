<?php
declare(strict_types=1);

namespace prime\interfaces;

interface CommandBusInterface
{


    public function handle(object $command): void;
}
