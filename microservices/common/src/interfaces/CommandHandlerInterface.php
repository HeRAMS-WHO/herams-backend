<?php
declare(strict_types=1);

namespace herams\common\interfaces;

interface CommandHandlerInterface
{

    public function handle(object $command): void;

}
