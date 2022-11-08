<?php

declare(strict_types=1);

$definitions = require(__DIR__ . '/di.php');


$definitions[\herams\common\interfaces\AccessCheckInterface::class] = \prime\helpers\JobAccessCheck::class;

return $definitions;
