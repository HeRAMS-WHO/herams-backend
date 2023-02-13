<?php

declare(strict_types=1);

use herams\common\domain\facility\FacilityRepository;
use herams\common\interfaces\AccessCheckInterface;
use herams\common\interfaces\EnvironmentInterface;
use herams\console\helpers\DisabledAccessCheck;
use Lcobucci\Clock\SystemClock;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Validation\Constraint\IssuedBy;
use Lcobucci\JWT\Validation\Constraint\PermittedFor;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Lcobucci\JWT\Validation\Constraint\StrictValidAt;
use yii\di\Container;

return function(EnvironmentInterface $env, Container $container): void {
   $container->set(AccessCheckInterface::class, DisabledAccessCheck::class);

};
