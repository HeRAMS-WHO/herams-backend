<?php

declare(strict_types=1);

$definitions = require(__DIR__ . '/di.php');


$definitions[\prime\interfaces\AccessCheckInterface::class] = \prime\helpers\JobAccessCheck::class;

return $definitions;
